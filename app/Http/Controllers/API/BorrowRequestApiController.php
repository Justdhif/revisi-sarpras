<?php

namespace App\Http\Controllers\Api;

use App\Models\BorrowRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BorrowRequestApiController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $requests = BorrowRequest::with('borrowDetail.itemUnit.item')
            ->where('user_id', $userId)
            ->latest()
            ->get();

        $data = $requests->map(function ($req) {
            return [
                'id' => $req->id,
                'status' => $req->status,
                'return_date_expected' => $req->return_date_expected,
                'borrow_date_expected' => $req->borrow_date_expected,
                'reason' => $req->reason,
                'borrow_detail' => $req->borrowDetail->map(function ($d) {
                    return [
                        'id' => $d->id,
                        'item_unit_id' => $d->item_unit_id,
                        'quantity' => $d->quantity,
                        'item_unit' => [
                            'sku' => $d->itemUnit->sku ?? '-',
                            'item' => [
                                'name' => $d->itemUnit->item->name ?? 'Barang',
                            ],
                        ],
                    ];
                }),
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'List permintaan peminjaman Anda',
            'data' => $data,
        ]);
    }

    public function show($id)
    {
        $request = BorrowRequest::with('borrowDetail.itemUnit.item')
            ->where('user_id', Auth::id())
            ->find($id);

        if (!$request) {
            return response()->json([
                'success' => false,
                'message' => 'Permintaan tidak ditemukan',
            ], 404);
        }

        $data = [
            'id' => $request->id,
            'status' => $request->status,
            'return_date_expected' => $request->return_date_expected,
            'borrow_date_expected' => $request->borrow_date_expected,
            'reason' => $request->reason,
            'borrow_detail' => $request->borrowDetail->map(function ($d) {
                return [
                    'id' => $d->id,
                    'item_unit_id' => $d->item_unit_id,
                    'quantity' => $d->quantity,
                    'item_unit' => [
                        'sku' => $d->itemUnit->sku ?? '-',
                        'item' => [
                            'name' => $d->itemUnit->item->name ?? 'Barang',
                        ],
                    ],
                ];
            }),
        ];

        return response()->json([
            'success' => true,
            'message' => 'Detail permintaan peminjaman',
            'data' => $data,
        ]);
    }
}
