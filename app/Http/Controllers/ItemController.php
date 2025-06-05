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
    public function index(Request $request)
    {
        $query = Item::with('category');

        // Filter pencarian
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Filter jenis
        if ($request->has('type') && !empty($request->type)) {
            $query->where('type', $request->type);
        }

        // Filter kategori
        if ($request->has('category') && !empty($request->category)) {
            $query->where('category_id', $request->category);
        }

        // Sorting
        if ($request->has('sort')) {
            $sort = explode('_', $request->sort);
            if (count($sort) == 2) {
                $query->orderBy($sort[0], $sort[1]);
            }
        } else {
            $query->orderBy('name', 'asc');
        }

        $items = $query->paginate(10);
        $categories = Category::all();

        if ($request->ajax()) {
            return response()->json([
                'data' => $items->items(),
                'current_page' => $items->currentPage(),
                'last_page' => $items->lastPage(),
                'from' => $items->firstItem(),
                'to' => $items->lastItem(),
                'total' => $items->total(),
                'links' => $items->links()->elements,
            ]);
        }

        return view('items.index', compact('categories'));
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
        $item->load(['category', 'itemUnits.warehouse']);
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
            // Delete old image if exists
            if ($item->image_url && file_exists(public_path($item->image_url))) {
                unlink(public_path($item->image_url));
            }

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
        // Delete associated image if exists
        if ($item->image_url && file_exists(public_path($item->image_url))) {
            unlink(public_path($item->image_url));
        }

        $item->itemUnits()->delete();
        $item->delete();

        return redirect()->route('items.index')->with('success', 'Barang berhasil dihapus.');
    }
}
