<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemUnit;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use App\Exports\ItemUnitsExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ItemUnitController extends Controller
{
    public function exportExcel()
    {
        return Excel::download(new ItemUnitsExport, 'data-units.xlsx');
    }

    public function exportPdf()
    {
        $itemUnits = ItemUnit::with(['item', 'warehouse'])->latest()->get();
        $pdf = Pdf::loadView('item_units.pdf', compact('itemUnits'));
        return $pdf->download('data-units.pdf');
    }

    public function index(Request $request)
    {
        $query = ItemUnit::with(['item', 'warehouse']);

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('sku', 'like', "%{$search}%")
                    ->orWhereHas('item', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('warehouse', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->has('condition') && !empty($request->condition)) {
            $query->where('condition', $request->condition);
        }

        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        if ($request->has('warehouse') && !empty($request->warehouse)) {
            $query->where('warehouse_id', $request->warehouse);
        }

        $units = $query->paginate(10);
        $warehouses = Warehouse::all();

        if ($request->ajax()) {
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

        return view('item_units.index', compact('warehouses'));
    }

    public function create()
    {
        $items = Item::all();
        $warehouses = Warehouse::where('capacity', '>', ItemUnit::sum('quantity'))->get();
        return view('item_units.create', compact('items', 'warehouses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sku' => 'required|unique:item_units',
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
        $totalQuantity = $warehouse->itemUnits->sum('quantity');

        if ($warehouse->capacity - $totalQuantity < $validated['quantity']) {
            return back()->with('error', 'Kapasitas gudang tidak mencukupi.')->withInput();
        }

        $validated['qr_image_url'] = $this->generateAndSaveQr($validated['sku']);

        ItemUnit::create($validated);

        return redirect()->route('item-units.index')->with('success', 'Unit barang berhasil ditambahkan.');
    }

    public function show(ItemUnit $itemUnit)
    {
        $itemUnit->load(['item', 'warehouse']);
        return view('item_units.show', compact('itemUnit'));
    }

    public function edit(ItemUnit $itemUnit)
    {
        $items = Item::all();
        $warehouses = Warehouse::all();
        return view('item_units.edit', compact('itemUnit', 'items', 'warehouses'));
    }

    public function update(Request $request, ItemUnit $itemUnit)
    {
        $oldQuantity = $itemUnit->quantity;

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

        if ($validated['sku'] !== $itemUnit->sku) {
            $validated['qr_image_url'] = $this->generateAndSaveQr($validated['sku']);
        }

        $warehouse = Warehouse::findOrFail($request->warehouse_id);

        $currentTotal = $warehouse->itemUnits()->sum('quantity');
        $adjustedTotal = $currentTotal - $oldQuantity + $validated['quantity'];

        if ($adjustedTotal > $warehouse->capacity) {
            return back()->with('error', 'Update gagal: kapasitas gudang tidak mencukupi.')->withInput();
        }

        $itemUnit->update($validated);

        return redirect()->route('item-units.index')->with('success', 'Unit barang berhasil diperbarui.');
    }

    public function destroy(ItemUnit $itemUnit)
    {
        $itemUnit->delete();
        return redirect()->route('item-units.index')->with('success', 'Unit barang berhasil dihapus.');
    }

    private function generateAndSaveQr($sku)
    {
        $qrCode = QrCode::format('svg')->size(300)->generate($sku);
        $fileName = 'qr_codes/' . $sku . '.svg';
        Storage::disk('public')->put($fileName, $qrCode);
        return 'storage/' . $fileName;
    }
}
