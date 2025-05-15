<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Exports\ItemsExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ItemController extends Controller
{
    /**
     * Export data barang ke file Excel.
     */
    public function exportExcel()
    {
        return Excel::download(new ItemsExport, 'data-barang.xlsx');
    }

    /**
     * Export data barang ke file PDF.
     */
    public function exportPdf()
    {
        $items = Item::with('category')->latest()->get();
        $pdf = Pdf::loadView('items.pdf', compact('items'));
        return $pdf->download('data-barang.pdf');
    }

    /**
     * Menampilkan daftar barang.
     */
    public function index()
    {
        $items = Item::with('category')->latest()->get();
        return view('items.index', compact('items'));
    }

    /**
     * Menampilkan form untuk menambahkan barang baru.
     */
    public function create()
    {
        $categories = Category::all();
        return view('items.create', compact('categories'));
    }

    /**
     * Menyimpan barang baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:consumable,non-consumable',
            'description' => 'nullable|string',
            'image_url' => 'nullable|image|max:2048',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($request->hasFile('image_url')) {
            $imagePath = $request->file('image_url')->store('items', 'public');
            $validated['image_url'] = 'storage/' . $imagePath;
        }

        Item::create($validated);

        return redirect()->route('items.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail barang tertentu.
     */
    public function show(Item $item)
    {
        $item->load(['category', 'itemUnits.warehouse']); // Memuat relasi
        return view('items.show', compact('item'));
    }

    /**
     * Menampilkan form edit barang.
     */
    public function edit(Item $item)
    {
        $categories = Category::all();
        return view('items.edit', compact('item', 'categories'));
    }

    /**
     * Memperbarui data barang.
     */
    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:consumable,non-consumable',
            'description' => 'nullable|string',
            'image_url' => 'nullable|image|max:2048',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($request->hasFile('image_url')) {
            $imagePath = $request->file('image_url')->store('items', 'public');
            $validated['image_url'] = 'storage/' . $imagePath;
        }

        $item->update($validated);

        return redirect()->route('items.index')->with('success', 'Barang berhasil diperbarui.');
    }

    /**
     * Menghapus barang dari database.
     */
    public function destroy(Item $item)
    {
        $item->delete();
        $item->itemUnits()->delete();

        return redirect()->route('items.index')->with('success', 'Barang berhasil dihapus.');
    }
}
