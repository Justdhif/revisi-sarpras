<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\BorrowDetail;
use App\Models\BorrowRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function store(Request $request)
    {
        $data = $request->validate([
            'return_date_expected' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $userId = Auth::id();

        // Ambil data cart milik user
        $cartItems = Cart::where('user_id', $userId)->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Keranjang peminjaman kosong',
            ], 400);
        }

        $borrowRequest = DB::transaction(function () use ($data, $userId, $cartItems) {
            $request = BorrowRequest::create([
                'user_id' => $userId,
                'return_date_expected' => $data['return_date_expected'],
                'borrow_date_expected' => $data['borrow_date_expected'],
                'status' => 'pending',
                'reason' => $data['reason'],
                'notes' => $data['notes'] ?? null,
            ]);

            $details = $cartItems->map(function ($cart) use ($request) {
                return [
                    'borrow_request_id' => $request->id,
                    'item_unit_id' => $cart->item_unit_id,
                    'quantity' => $cart->quantity,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            });

            BorrowDetail::insert($details->toArray());

            // Hapus isi cart setelah berhasil
            Cart::where('user_id', $userId)->delete();

            return $request;
        });

        // Load relasi dan format respons
        $borrowRequest->load('borrowDetail.itemUnit.item');

        $responseData = [
            'id' => $borrowRequest->id,
            'status' => $borrowRequest->status,
            'return_date_expected' => $borrowRequest->return_date_expected,
            'borrow_date_expected' => $borrowRequest->borrow_date_expected,
            'reason' => $borrowRequest->reason,
            'borrow_detail' => $borrowRequest->borrowDetail->map(function ($d) {
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
            'message' => 'Permintaan peminjaman berhasil dibuat',
            'data' => $responseData,
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
