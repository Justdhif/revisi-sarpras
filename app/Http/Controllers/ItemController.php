<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Support\Str;
use App\Exports\ItemsExport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    const PAGINATION_COUNT = 10;
    const EXPORT_FILENAME = 'data-barang';
    const IMAGE_DISK = 'public';
    const IMAGE_PATH = 'items';
    const MAX_IMAGE_SIZE = 2048;

    public function exportExcel(Request $request)
    {
        $filters = $request->only(['search', 'category', 'type']);

        $filenameParts = [self::EXPORT_FILENAME];

        // Tambahkan kategori ke nama file
        if (!empty($filters['category'])) {
            $category = \App\Models\Category::find($filters['category']);
            if ($category) {
                $filenameParts[] = 'kategori-' . Str::slug($category->name);
            }
        }

        // Tambahkan tipe ke nama file
        if (!empty($filters['type'])) {
            $filenameParts[] = 'tipe-' . Str::slug($filters['type']);
        }

        // Tambahkan kata kunci pencarian jika ada
        if (!empty($filters['search'])) {
            $filenameParts[] = 'cari-' . Str::slug($filters['search']);
        }

        // Tambahkan tanggal
        $filenameParts[] = now()->format('Y-m-d') . '.xlsx';

        $filename = implode('-', $filenameParts);

        return Excel::download(
            new ItemsExport($filters),
            $filename
        );
    }

    public function exportPdf()
    {
        $items = Item::with('category')->latest()->get();
        $filename = self::EXPORT_FILENAME . '-' . now()->format('Y-m-d') . '.pdf';

        $pdf = PDF::loadView('items.pdf', compact('items'));
        return $pdf->download($filename);
    }

    public function index(Request $request)
    {
        $sortField = $request->get('sort', 'name');
        $sortDirection = $request->get('direction', 'asc');

        $query = Item::with('category')
            ->when($request->search, function ($query) use ($request) {
                return $query->where('name', 'like', '%' . $request->search . '%');
            })
            ->when($request->type, function ($query) use ($request) {
                return $query->where('type', $request->type);
            })
            ->when($request->category, function ($query) use ($request) {
                return $query->where('category_id', $request->category);
            })
            ->orderBy($sortField, $sortDirection);

        $items = $query->latest()->paginate(10);

        $categories = Category::all();

        return view('items.index', [
            'items' => $items,
            'categories' => $categories,
            'sortField' => $sortField,
            'sortDirection' => $sortDirection,
        ]);
    }

    public function create()
    {
        return view('items.create', [
            'categories' => Category::all()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:consumable,non-consumable',
            'description' => 'nullable|string',
            'image_url' => 'nullable|image|max:' . self::MAX_IMAGE_SIZE,
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($request->hasFile('image_url')) {
            $imagePath = $request->file('image_url')->store(self::IMAGE_PATH, self::IMAGE_DISK);
            $validated['image_url'] = Storage::url($imagePath);
        }

        Item::create($validated);

        return redirect()->route('items.index')
            ->with('success', 'Barang berhasil ditambahkan.');
    }

    public function show(Item $item)
    {
        $item->load(['category', 'itemUnits.warehouse']);
        return view('items.show', compact('item'));
    }

    public function edit(Item $item)
    {
        return view('items.edit', [
            'item' => $item,
            'categories' => Category::all()
        ]);
    }

    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:consumable,non-consumable',
            'description' => 'nullable|string',
            'image_url' => 'nullable|image|max:' . self::MAX_IMAGE_SIZE,
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($request->hasFile('image_url')) {
            if ($item->image_url) {
                $relativePath = str_replace('/storage/', '', $item->image_url);
                Storage::disk(self::IMAGE_DISK)->delete($relativePath);
            }

            $imagePath = $request->file('image_url')->store(self::IMAGE_PATH, self::IMAGE_DISK);
            $validated['image_url'] = Storage::url($imagePath);
        }

        $item->update($validated);

        return redirect()->route('items.index')
            ->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Item $item)
    {
        if ($item->image_url) {
            $relativePath = str_replace('/storage/', '', $item->image_url);
            Storage::disk(self::IMAGE_DISK)->delete($relativePath);
        }

        $item->itemUnits()->delete();
        $item->delete();

        return redirect()->route('items.index')
            ->with('success', 'Barang berhasil dihapus.');
    }
}
