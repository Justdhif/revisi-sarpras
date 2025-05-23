<?php

namespace App\Http\Controllers\Api;

use App\Models\BorrowDetail;
use Illuminate\Http\Request;
use App\Models\BorrowRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BorrowRequestApiController extends Controller
{
    // Menampilkan semua permintaan peminjaman milik user yang sedang login
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

    // Menyimpan permintaan peminjaman baru
    public function store(Request $request)
    {
        $data = $request->validate([
            'return_date_expected' => 'required|date',
            'notes' => 'nullable|string',
            'borrow_details' => 'required|array',
            'borrow_details.*.item_unit_id' => 'required|exists:item_units,id',
            'borrow_details.*.quantity' => 'required|integer|min:1',
        ]);

        $userId = Auth::id();

        $borrowRequest = DB::transaction(function () use ($data, $userId) {
            $request = BorrowRequest::create([
                'user_id' => $userId,
                'return_date_expected' => $data['return_date_expected'],
                'notes' => $data['notes'] ?? null,
            ]);

            $details = collect($data['borrow_details'])->map(function ($detail) use ($request) {
                return [
                    'borrow_request_id' => $request->id,
                    'item_unit_id' => $detail['item_unit_id'],
                    'quantity' => $detail['quantity'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            });

            BorrowDetail::insert($details->toArray());

            return $request;
        });

        // Load relasi dan transform agar sesuai format index
        $borrowRequest->load('borrowDetail.itemUnit.item');
        $responseData = [
            'id' => $borrowRequest->id,
            'status' => $borrowRequest->status,
            'return_date_expected' => $borrowRequest->return_date_expected,
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

    // Menampilkan detail permintaan peminjaman tertentu
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

        // Mapping agar formatnya konsisten dengan index
        $data = [
            'id' => $request->id,
            'status' => $request->status,
            'return_date_expected' => $request->return_date_expected,
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
