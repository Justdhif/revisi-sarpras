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
        $sortField = $request->get('sort', 'sku');
        $sortDirection = $request->get('direction', 'asc');

        $query = ItemUnit::with(['item', 'warehouse'])
            ->when($request->search, function ($query) use ($request) {
                return $query->where('sku', 'like', '%' . $request->search . '%')
                    ->orWhereHas('item', function ($q) use ($request) {
                        $q->where('name', 'like', '%' . $request->search . '%');
                    });
            })
            ->when($request->condition, function ($query) use ($request) {
                return $query->where('condition', $request->condition);
            })
            ->when($request->status, function ($query) use ($request) {
                return $query->where('status', $request->status);
            })
            ->when($request->warehouse, function ($query) use ($request) {
                return $query->where('warehouse_id', $request->warehouse);
            })
            ->orderBy($sortField, $sortDirection);

        $itemUnits = $query->paginate(10);
        $warehouses = Warehouse::all();

        return view('item_units.index', [
            'itemUnits' => $itemUnits,
            'warehouses' => $warehouses,
            'sortField' => $sortField,
            'sortDirection' => $sortDirection,
        ]);
    }

    public function create()
    {
        return view('item_units.create', [
            'items' => Item::all(),
            'warehouses' => Warehouse::where('capacity', '>', ItemUnit::sum('quantity'))->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sku' => 'required|unique:item_units,sku',
            'condition' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'acquisition_source' => 'required|string|max:255',
            'acquisition_date' => 'required|date',
            'acquisition_notes' => 'nullable|string',
            'status' => 'required|in:available,borrowed,unknown',
            'quantity' => 'required|integer|min:1',
            'item_id' => 'required|exists:items,id',
            'warehouse_id' => 'required|exists:warehouses,id',
        ]);

        $warehouse = Warehouse::findOrFail($validated['warehouse_id']);
        $totalQuantity = $warehouse->itemUnits()->sum('quantity');

        if (($warehouse->capacity - $totalQuantity) < $validated['quantity']) {
            return back()->with('error', 'Kapasitas gudang tidak mencukupi.')->withInput();
        }

        $qrCode = QrCode::format(self::QR_CODE_FORMAT)
            ->size(self::QR_CODE_SIZE)
            ->generate($validated['sku']);

        $fileName = self::QR_CODE_DIR . '/' . $validated['sku'] . '.' . self::QR_CODE_FORMAT;
        Storage::disk('public')->put($fileName, $qrCode);
        $validated['qr_image_url'] = Storage::url($fileName);

        ItemUnit::create($validated);

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
        $validated = $request->validate([
            'sku' => 'required|unique:item_units,sku,' . $itemUnit->id,
            'condition' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'acquisition_source' => 'required|string|max:255',
            'acquisition_date' => 'required|date',
            'acquisition_notes' => 'nullable|string',
            'status' => 'required|in:available,borrowed,unknown',
            'quantity' => 'required|integer|min:1',
            'item_id' => 'required|exists:items,id',
            'warehouse_id' => 'required|exists:warehouses,id',
        ]);

        $warehouse = Warehouse::findOrFail($validated['warehouse_id']);
        $currentTotal = $warehouse->itemUnits()->sum('quantity');
        $adjustedTotal = $currentTotal - $itemUnit->quantity + $validated['quantity'];

        if ($adjustedTotal > $warehouse->capacity) {
            return back()->with('error', 'Update gagal: kapasitas gudang tidak mencukupi.')->withInput();
        }

        if ($validated['sku'] !== $itemUnit->sku) {
            $qrCode = QrCode::format(self::QR_CODE_FORMAT)
                ->size(self::QR_CODE_SIZE)
                ->generate($validated['sku']);

            $fileName = self::QR_CODE_DIR . '/' . $validated['sku'] . '.' . self::QR_CODE_FORMAT;
            Storage::disk('public')->put($fileName, $qrCode);
            $validated['qr_image_url'] = Storage::url($fileName);
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
}
