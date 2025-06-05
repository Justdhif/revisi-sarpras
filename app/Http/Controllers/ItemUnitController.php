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
    /**
     * Ekspor data unit barang ke file Excel.
     */
    public function exportExcel()
    {
        return Excel::download(new ItemUnitsExport, 'data-units.xlsx');
    }

    /**
     * Ekspor data unit barang ke file PDF.
     */
    public function exportPdf()
    {
        $itemUnits = ItemUnit::with(['item', 'warehouse'])->latest()->get();
        $pdf = Pdf::loadView('item_units.pdf', compact('itemUnits'));
        return $pdf->download('data-units.pdf');
    }

    /**
     * Menampilkan daftar semua unit barang.
     */
    public function index(Request $request)
    {
        $query = ItemUnit::with(['item', 'warehouse']);

        // Filter pencarian
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

        // Filter kondisi
        if ($request->has('condition') && !empty($request->condition)) {
            $query->where('condition', $request->condition);
        }

        // Filter status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Filter gudang
        if ($request->has('warehouse') && !empty($request->warehouse)) {
            $query->where('warehouse_id', $request->warehouse);
        }

        $itemUnits = $query->paginate(10);
        $warehouses = Warehouse::all();

        if ($request->ajax()) {
            return view('item_units.partials._units_table', compact('itemUnits', 'warehouses'));
        }

        return view('item_units.index', compact('itemUnits', 'warehouses'));
    }

    /**
     * Menampilkan form untuk membuat unit barang baru.
     * Hanya warehouse yang kapasitasnya belum penuh yang ditampilkan.
     */
    public function create()
    {
        $items = Item::all();
        $warehouses = Warehouse::where('capacity', '>', ItemUnit::sum('quantity'))->get();
        return view('item_units.create', compact('items', 'warehouses'));
    }

    /**
     * Menyimpan data unit barang baru ke dalam database.
     * Validasi dilakukan terhadap kapasitas gudang dan data input.
     */
    public function store(Request $request)
    {
        // Validasi input dari form
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

        // Cek kapasitas gudang
        $warehouse = Warehouse::findOrFail($request->warehouse_id);
        // count quantity semua item unit yang ada di gudang
        $totalQuantity = $warehouse->itemUnits()->sum('quantity');
        if ($warehouse->capacity - $totalQuantity < $validated['quantity']) {
            return back()->with('error', 'Kapasitas gudang tidak mencukupi.')->withInput();
        }

        // Generate QR code untuk SKU
        $validated['qr_image_url'] = $this->generateAndSaveQr($validated['sku']);

        $unit = ItemUnit::create($validated);

        return redirect()->route('item-units.index')->with('success', 'Unit barang berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail dari satu unit barang.
     */
    public function show(ItemUnit $itemUnit)
    {
        $itemUnit->load(['item', 'warehouse']);
        return view('item_units.show', compact('itemUnit'));
    }

    /**
     * Menampilkan form edit untuk unit barang.
     */
    public function edit(ItemUnit $itemUnit)
    {
        $items = Item::all();
        $warehouses = Warehouse::all();
        return view('item_units.edit', compact('itemUnit', 'items', 'warehouses'));
    }

    /**
     * Memperbarui data unit barang di database.
     * Termasuk validasi kapasitas gudang setelah perubahan.
     */
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

        // Perbarui QR code jika SKU berubah
        if ($validated['sku'] !== $itemUnit->sku) {
            $validated['qr_image_url'] = $this->generateAndSaveQr($validated['sku']);
        }

        // Validasi kapasitas gudang setelah perubahan jumlah
        $warehouse = Warehouse::findOrFail($request->warehouse_id);
        $newUsedCapacity = ($warehouse->used_capacity - $oldQuantity) + $validated['quantity'];

        if ($newUsedCapacity > $warehouse->capacity) {
            return back()->with('error', 'Update gagal: kapasitas gudang tidak mencukupi.')->withInput();
        }

        $itemUnit->update($validated);
        $warehouse->used_capacity = $newUsedCapacity;
        $warehouse->save();

        return redirect()->route('item-units.index')->with('success', 'Unit barang berhasil diperbarui.');
    }

    /**
     * Menghapus unit barang dan mengurangi kapasitas gudang.
     */
    public function destroy(ItemUnit $itemUnit)
    {
        $warehouse = $itemUnit->warehouse;
        $quantity = $itemUnit->quantity;

        $itemUnit->delete();

        if ($warehouse) {
            $warehouse->decrement('used_capacity', $quantity);
        }

        return redirect()->route('item-units.index')->with('success', 'Unit barang berhasil dihapus.');
    }

    /**
     * Generate dan simpan QR Code berdasarkan SKU, lalu kembalikan URL-nya.
     */
    private function generateAndSaveQr($sku)
    {
        $qrCode = QrCode::format('svg')->size(300)->generate($sku);
        $fileName = 'qr_codes/' . $sku . '.svg';
        Storage::disk('public')->put($fileName, $qrCode);
        return 'storage/' . $fileName;
    }
}
