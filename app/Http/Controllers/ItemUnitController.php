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
        return Excel::download(new ItemUnitsExport, 'item_units.xlsx');
    }

    public function exportPdf()
    {
        $itemUnits = ItemUnit::with(['item', 'warehouse'])->get();
        $pdf = Pdf::loadView('item_units.export_pdf', compact('itemUnits'));
        return $pdf->download('item_units.pdf');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $itemUnits = ItemUnit::with(['item', 'warehouse'])->latest()->get();
        $sku = $itemUnits->pluck('sku');
        return view('item_units.index', compact('itemUnits', 'sku'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $items = Item::all();
        $warehouses = Warehouse::whereRaw('capacity > used_capacity')->get();
        return view('item_units.create', compact('items', 'warehouses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sku' => 'required|unique:item_units',
            'condition' => 'required',
            'notes' => 'nullable',
            'acquisition_source' => 'required',
            'acquisition_date' => 'required|date',
            'acquisition_notes' => 'nullable',
            'status' => 'required|in:available,borrowed,unknown',
            'quantity' => 'required|integer|min:1',
            'item_id' => 'required|exists:items,id',
            'warehouse_id' => 'required|exists:warehouses,id',
        ]);

        $validated['qr_image_url'] = $this->generateAndSaveQr($validated['sku']);

        $warehouse = Warehouse::findOrFail($request->warehouse_id);

        // Cek apakah warehouse punya kapasitas cukup
        if (($warehouse->used_capacity + $request->quantity) > $warehouse->capacity) {
            return back()->with('error', 'Kapasitas gudang tidak mencukupi.');
        }

        // Simpan item unit
        $unit = ItemUnit::create($validated);

        // Update used_capacity
        $warehouse->used_capacity += $unit->quantity;
        $warehouse->save();

        return redirect()->route('item-units.index')->with('success', 'Unit barang berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ItemUnit $itemUnit)
    {
        $items = Item::all();
        $warehouses = Warehouse::all();
        return view('item_units.edit', compact('itemUnit', 'items', 'warehouses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ItemUnit $itemUnit)
    {
        $validated = $request->validate([
            'sku' => 'required|unique:item_units,sku,' . $itemUnit->id,
            'condition' => 'required',
            'notes' => 'nullable',
            'acquisition_source' => 'required',
            'acquisition_date' => 'required|date',
            'acquisition_notes' => 'nullable',
            'status' => 'required|in:available,borrowed,unknown',
            'quantity' => 'required|integer|min:1',
            'item_id' => 'required|exists:items,id',
            'warehouse_id' => 'required|exists:warehouses,id',
        ]);

        if ($validated['sku'] !== $itemUnit->sku) {
            $validated['qr_image_url'] = $this->generateAndSaveQr($validated['sku']);
        }

        $itemUnit->update($validated);

        return redirect()->route('item-units.index')->with('success', 'Unit barang berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ItemUnit $itemUnit)
    {
        $itemUnit->delete();
        return redirect()->route('item-units.index')->with('success', 'Item unit deleted.');
    }

    private function generateAndSaveQr($sku)
    {
        $qrCode = QrCode::format('svg')->size(300)->generate($sku);
        $fileName = 'qr_codes/' . $sku . '.png';
        Storage::disk('public')->put($fileName, $qrCode);
        return 'storage/' . $fileName;
    }

    public function printAllQRCodes()
    {
        $itemUnits = ItemUnit::with(['item', 'warehouse'])->get();

        return view('item_units.qr_print', compact('itemUnits'));
    }
}
