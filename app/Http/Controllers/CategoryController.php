<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    const PAGINATION_COUNT = 10;

    public function index()
    {
        $results = DB::select("CALL getAllCategories()");

        $currentPage = request()->get('page', 1);
        $perPage = self::PAGINATION_COUNT;
        $offset = ($currentPage - 1) * $perPage;

        $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
            array_slice($results, $offset, $perPage),
            count($results),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('categories.index', ['categories' => $paginated]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        DB::statement("CALL createCategory(?, ?)", [
            $validated['name'],
            $validated['description'] ?? null,
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function show(Category $category)
    {
        $category->load('items');
        return view('categories.show', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        DB::statement("CALL updateCategory(?, ?, ?)", [
            $category->id,
            $validated['name'],
            $validated['description'] ?? null,
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy(Category $category)
    {
        DB::statement("CALL deleteCategory(?)", [$category->id]);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil dihapus!');
    }
}
