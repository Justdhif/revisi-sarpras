<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ItemUnit;

class ItemUnitApiController extends Controller
{
    // Menampilkan semua item unit
    public function index()
    {
        $units = ItemUnit::with(['item', 'warehouse'])->where('status', 'available')->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'List semua unit barang',
            'data' => $units
        ]);
    }

    // Menampilkan detail unit berdasarkan ID
    public function show($id)
    {
        $unit = ItemUnit::with(['item', 'warehouse'])->find($id);

        if (!$unit) {
            return response()->json([
                'success' => false,
                'message' => 'Unit tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail unit barang',
            'data' => $unit
        ]);
    }

    // Ambil unit berdasarkan item_id
    public function getByItem($itemId)
    {
        $units = ItemUnit::with(['item', 'warehouse'])
            ->where('item_id', $itemId)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Unit untuk item ID ' . $itemId,
            'data' => $units
        ]);
    }
}
