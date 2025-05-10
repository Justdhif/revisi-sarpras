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
    public function filter(Request $request)
    {
        $query = Item::query()->with('category');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('sort')) {
            $query->orderBy('name', $request->sort);
        }

        $items = $query->get();

        return response()->json(['items' => $items]);
    }

    public function exportExcel()
    {
        return Excel::download(new ItemsExport, 'data-barang.xlsx');
    }

    public function exportPdf()
    {
        $items = Item::with('category')->get();
        $pdf = Pdf::loadView('items.pdf', compact('items'));
        return $pdf->download('data-barang.pdf');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Item::with('category')->latest()->get();
        return view('items.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('items.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'type' => 'required|in:consumable,non-consumable',
            'description' => 'nullable',
            'image_url' => 'nullable|image|max:2048',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($request->hasFile('image_url')) {
            $imagePath = $request->file('image_url')->store('items', 'public');
            $validated['image_url'] = 'storage/' . $imagePath;
        }

        Item::create($validated);
        return redirect()->route('items.index')->with('success', 'Item created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        $item->with(['category', 'itemUnits.warehouse']);
        return view('items.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        $categories = Category::all();
        return view('items.edit', compact('item', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'name' => 'required',
            'type' => 'required|in:consumable,non-consumable',
            'description' => 'nullable',
            'image_url' => 'nullable|image|max:2048',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($request->hasFile('image_url')) {
            $imagePath = $request->file('image_url')->store('items', 'public');
            $validated['image_url'] = 'storage/' . $imagePath;
        }

        $item->update($validated);
        return redirect()->route('items.index')->with('success', 'Item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()->route('items.index')->with('success', 'Item deleted.');
    }
}
