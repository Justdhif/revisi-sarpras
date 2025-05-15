<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ReturnDetail;
use App\Models\ItemUnit;
use Illuminate\Http\Request;

class ReturnDetailApiController extends Controller
{
    /**
     * Menampilkan semua detail pengembalian untuk 1 return request.
     */
    public function index($returnRequestId)
    {
        $details = ReturnDetail::with('itemUnit.item')
            ->where('return_request_id', $returnRequestId)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Detail pengembalian untuk request ID ' . $returnRequestId,
            'data' => $details,
        ]);
    }

    /**
     * Menyimpan detail pengembalian barang.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'condition' => 'required|string',
            'item_unit_id' => 'required|exists:item_units,id',
            'return_request_id' => 'required|exists:return_requests,id',
        ]);

        $itemUnit = ItemUnit::findOrFail($validated['item_unit_id']);

        // Update status barang tergantung kondisinya
        if (strtolower($validated['condition']) === 'rusak') {
            $itemUnit->status = 'damaged';
        } else {
            $itemUnit->status = 'available';
        }

        $itemUnit->save();

        $detail = ReturnDetail::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Detail pengembalian berhasil disimpan.',
            'data' => $detail,
        ]);
    }
}
