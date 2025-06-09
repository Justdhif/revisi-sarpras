<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemUnit;
use App\Models\StockMovement;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use App\Exports\ItemUnitsExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\PDF;

class ItemUnitController extends Controller
{
    const PAGINATION_COUNT = 10;
    const EXPORT_FILENAME = 'data-units';
    const QR_CODE_DIR = 'qr_codes';
    const QR_CODE_SIZE = 300;
    const QR_CODE_FORMAT = 'svg';

    public function exportExcel()
    {
        $filename = self::EXPORT_FILENAME . '-' . now()->format('Y-m-d') . '.xlsx';
        return Excel::download(new ItemUnitsExport, $filename);
    }

    public function exportPdf()
    {
        $itemUnits = ItemUnit::with(['item', 'warehouse'])->latest()->get();
        $filename = self::EXPORT_FILENAME . '-' . now()->format('Y-m-d') . '.pdf';

        $pdf = PDF::loadView('item_units.pdf', compact('itemUnits'));
        return $pdf->download($filename);
    }

    public function index(Request $request)
    {
        $units = $this->getFilteredItemUnits($request);
        $warehouses = Warehouse::all();

        if ($request->ajax()) {
            return $this->getAjaxResponse($units);
        }

        return view('item_units.index', compact('warehouses'));
    }

    public function create()
    {
        return view('item_units.create', [
            'items' => Item::all(),
            'warehouses' => $this->getAvailableWarehouses()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateItemUnitRequest($request);

        if (!$this->validateWarehouseCapacity($validated['warehouse_id'], $validated['quantity'])) {
            return back()->with('error', 'Kapasitas gudang tidak mencukupi.')->withInput();
        }

        $validated['qr_image_url'] = $this->generateQrCode($validated['sku']);
        ItemUnit::create($validated);

        StockMovement::create([
            'item_unit_id' => ItemUnit::latest()->first()->id,
            'type' => 'in',
            'quantity' => $validated['quantity'],
            'movement_date' => now(),
            'description' => 'Barang masuk ke gudang',
        ]);

        return redirect()->route('item-units.index')
            ->with('success', 'Unit barang berhasil ditambahkan.');
    }

    public function show(ItemUnit $itemUnit)
    {
        $itemUnit->load(['item', 'warehouse']);
        return view('item_units.show', compact('itemUnit'));
    }

    public function edit(ItemUnit $itemUnit)
    {
        return view('item_units.edit', [
            'itemUnit' => $itemUnit,
            'items' => Item::all(),
            'warehouses' => Warehouse::all()
        ]);
    }

    public function update(Request $request, ItemUnit $itemUnit)
    {
        $validated = $this->validateItemUnitRequest($request, $itemUnit);

        if (
            !$this->validateUpdatedWarehouseCapacity(
                $validated['warehouse_id'],
                $validated['quantity'],
                $itemUnit->quantity
            )
        ) {
            return back()->with('error', 'Update gagal: kapasitas gudang tidak mencukupi.')->withInput();
        }

        if ($validated['sku'] !== $itemUnit->sku) {
            $validated['qr_image_url'] = $this->generateQrCode($validated['sku']);
        }

        $itemUnit->update($validated);

        return redirect()->route('item-units.index')
            ->with('success', 'Unit barang berhasil diperbarui.');
    }

    public function destroy(ItemUnit $itemUnit)
    {
        $itemUnit->delete();
        return redirect()->route('item-units.index')
            ->with('success', 'Unit barang berhasil dihapus.');
    }

    private function getFilteredItemUnits(Request $request)
    {
        $query = ItemUnit::with(['item', 'warehouse']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('sku', 'like', "%{$request->search}%")
                    ->orWhereHas('item', fn($q) => $q->where('name', 'like', "%{$request->search}%"))
                    ->orWhereHas('warehouse', fn($q) => $q->where('name', 'like', "%{$request->search}%"));
            });
        }

        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('warehouse')) {
            $query->where('warehouse_id', $request->warehouse);
        }

        return $query->paginate(self::PAGINATION_COUNT);
    }

    private function getAjaxResponse($units)
    {
        return response()->json([
            'data' => $units->items(),
            'current_page' => $units->currentPage(),
            'last_page' => $units->lastPage(),
            'from' => $units->firstItem(),
            'to' => $units->lastItem(),
            'total' => $units->total(),
            'links' => $units->links()->elements,
        ]);
    }

    private function getAvailableWarehouses()
    {
        return Warehouse::where('capacity', '>', ItemUnit::sum('quantity'))->get();
    }

    private function validateItemUnitRequest(Request $request, ?ItemUnit $itemUnit = null)
    {
        $rules = [
            'sku' => 'required|unique:item_units' . ($itemUnit ? ',sku,' . $itemUnit->id : ''),
            'condition' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'acquisition_source' => 'required|string|max:255',
            'acquisition_date' => 'required|date',
            'acquisition_notes' => 'nullable|string',
            'status' => 'required|in:available,borrowed,unknown',
            'quantity' => 'required|integer|min:1',
            'item_id' => 'required|exists:items,id',
            'warehouse_id' => 'required|exists:warehouses,id',
        ];

        return $request->validate($rules);
    }

    private function validateWarehouseCapacity($warehouseId, $quantity)
    {
        $warehouse = Warehouse::findOrFail($warehouseId);
        $totalQuantity = $warehouse->itemUnits()->sum('quantity');
        return ($warehouse->capacity - $totalQuantity) >= $quantity;
    }

    private function validateUpdatedWarehouseCapacity($warehouseId, $newQuantity, $oldQuantity)
    {
        $warehouse = Warehouse::findOrFail($warehouseId);
        $currentTotal = $warehouse->itemUnits()->sum('quantity');
        $adjustedTotal = $currentTotal - $oldQuantity + $newQuantity;
        return $adjustedTotal <= $warehouse->capacity;
    }

    private function generateQrCode($sku)
    {
        $qrCode = QrCode::format(self::QR_CODE_FORMAT)
            ->size(self::QR_CODE_SIZE)
            ->generate($sku);

        $fileName = self::QR_CODE_DIR . '/' . $sku . '.' . self::QR_CODE_FORMAT;
        Storage::disk('public')->put($fileName, $qrCode);

        return Storage::url($fileName);
    }
}
