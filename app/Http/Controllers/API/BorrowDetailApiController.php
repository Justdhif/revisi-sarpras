<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BorrowDetail;
use App\Models\ItemUnit;
use Illuminate\Http\Request;

class BorrowDetailApiController extends Controller
{
    /**
     * Menampilkan semua detail peminjaman untuk 1 request.
     */
    public function index($borrowRequestId)
    {
        $details = BorrowDetail::with('itemUnit.item')
            ->where('borrow_request_id', $borrowRequestId)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Detail peminjaman untuk request ID ' . $borrowRequestId,
            'data' => $details,
        ]);
    }

    /**
     * Menambahkan detail barang ke dalam permintaan peminjaman.
     */
    public function store(Request $request)
    {
        $request->validate([
            'borrow_request_id' => 'required|exists:borrow_requests,id',
            'item_unit_id' => 'required|exists:item_units,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $unit = ItemUnit::findOrFail($request->item_unit_id);
        $item = $unit->item;

        if ($item->type === 'consumable') {
            if ($unit->quantity === null || $unit->quantity < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak mencukupi untuk item: ' . $item->name,
                ], 400);
            }

            $unit->quantity -= $request->quantity;
            if ($unit->quantity === 0) {
                $unit->status = 'unavailable';
            }
            $unit->save();
        } else {
            if ($unit->status !== 'available') {
                return response()->json([
                    'success' => false,
                    'message' => 'Item tidak tersedia untuk dipinjam.',
                ], 400);
            }

            $unit->status = 'borrowed';
            $unit->save();
        }

        $detail = BorrowDetail::create([
            'borrow_request_id' => $request->borrow_request_id,
            'item_unit_id' => $request->item_unit_id,
            'quantity' => $request->quantity,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil ditambahkan ke peminjaman',
            'data' => $detail,
        ]);
    }
}
