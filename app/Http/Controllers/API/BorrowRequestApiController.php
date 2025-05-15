<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BorrowRequest;
use Illuminate\Http\Request;
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

        return response()->json([
            'success' => true,
            'message' => 'List permintaan peminjaman Anda',
            'data' => $requests,
        ]);
    }

    // Menyimpan permintaan peminjaman baru
    public function store(Request $request)
    {
        $request->validate([
            'return_date_expected' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $borrowRequest = BorrowRequest::create([
            'user_id' => Auth::id(),
            'return_date_expected' => $request->return_date_expected,
            'notes' => $request->notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Permintaan peminjaman berhasil dibuat',
            'data' => $borrowRequest,
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

        return response()->json([
            'success' => true,
            'message' => 'Detail permintaan peminjaman',
            'data' => $request,
        ]);
    }
}
