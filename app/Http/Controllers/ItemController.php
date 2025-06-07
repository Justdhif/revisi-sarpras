<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Exports\ItemsExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    const PAGINATION_COUNT = 10;
    const EXPORT_FILENAME = 'data-barang';
    const IMAGE_DISK = 'public';
    const IMAGE_PATH = 'items';
    const MAX_IMAGE_SIZE = 2048;

    public function exportExcel()
    {
        $filename = self::EXPORT_FILENAME . '-' . now()->format('Y-m-d') . '.xlsx';
        return Excel::download(new ItemsExport, $filename);
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
        $items = $this->getFilteredItems($request);
        $categories = Category::all();

        if ($request->ajax()) {
            return $this->getAjaxResponse($items);
        }

        return view('items.index', compact('categories'));
    }

    public function create()
    {
        return view('items.create', [
            'categories' => Category::all()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateItemRequest($request);
        $validated = $this->handleImageUpload($request, $validated);

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
        $validated = $this->validateItemRequest($request);
        $validated = $this->handleImageUpload($request, $validated, $item);

        $item->update($validated);

        return redirect()->route('items.index')
            ->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Item $item)
    {
        $this->deleteItemAssets($item);
        $item->itemUnits()->delete();
        $item->delete();

        return redirect()->route('items.index')
            ->with('success', 'Barang berhasil dihapus.');
    }

    private function getFilteredItems(Request $request)
    {
        $query = Item::with('category');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('description', 'like', "%{$request->search}%")
                    ->orWhereHas('category', fn($q) => $q->where('name', 'like', "%{$request->search}%"));
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $sort = $request->filled('sort') ? explode('_', $request->sort) : ['name', 'asc'];
        if (count($sort) === 2) {
            $query->orderBy($sort[0], $sort[1]);
        }

        return $query->paginate(self::PAGINATION_COUNT);
    }

    private function getAjaxResponse($items)
    {
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

    private function validateItemRequest(Request $request)
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:consumable,non-consumable',
            'description' => 'nullable|string',
            'image_url' => 'nullable|image|max:' . self::MAX_IMAGE_SIZE,
            'category_id' => 'required|exists:categories,id',
        ]);
    }

    private function handleImageUpload(Request $request, array $data, ?Item $item = null)
    {
        if (!$request->hasFile('image_url')) {
            return $data;
        }

        // Delete old image if exists
        if ($item && $item->image_url) {
            $this->deleteImage($item->image_url);
        }

        $imagePath = $request->file('image_url')
            ->store(self::IMAGE_PATH, self::IMAGE_DISK);

        $data['image_url'] = Storage::url($imagePath);

        return $data;
    }

    private function deleteItemAssets(Item $item)
    {
        if ($item->image_url) {
            $this->deleteImage($item->image_url);
        }
    }

    private function deleteImage(string $path)
    {
        $relativePath = str_replace('/storage/', '', $path);
        Storage::disk(self::IMAGE_DISK)->delete($relativePath);
    }
}
