<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;

class ItemApiController extends Controller
{
    // Menampilkan semua item dengan kategori
    public function index()
    {
        $items = Item::with('category')->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'List semua barang',
            'data' => $items
        ]);
    }

    // Menampilkan detail item berdasarkan ID
    public function show($id)
    {
        $item = Item::with([
            'category',
            'itemUnits' => function ($query) {
                $query->where('status', 'available')
                    ->where('quantity', '>', 0)
                    ->with('warehouse');
            }
        ])->find($id);

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Item tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail barang',
            'data' => $item
        ]);
    }
}
