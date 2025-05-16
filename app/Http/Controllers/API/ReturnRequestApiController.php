<?php

namespace App\Http\Controllers\Api;

use App\Models\ReturnDetail;
use Illuminate\Http\Request;
use App\Models\BorrowRequest;
use App\Models\ReturnRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ReturnRequestApiController extends Controller
{
    /**
     * Menampilkan semua return request milik user yang sedang login.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $returnRequests = ReturnRequest::with('borrowRequest')
            ->whereHas('borrowRequest', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->withCount('returnDetails')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $returnRequests
        ]);
    }

    /**
     * Menampilkan detail satu return request (beserta returnDetails dan itemUnit).
     */
    public function show($id, Request $request)
    {
        $user = $request->user();

        $returnRequest = ReturnRequest::with('returnDetails.itemUnit.item', 'borrowRequest')
            ->where('id', $id)
            ->whereHas('borrowRequest', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $returnRequest,
        ]);
    }

    /**
     * Menyimpan return request dari user.
     */
    public function store(Request $request)
    {
        $request->validate([
            'borrow_request_id' => 'required|exists:borrow_requests,id',
            'notes' => 'nullable|string',
            'item_units' => 'required|array|min:1',
            'item_units.*.id' => 'required|exists:item_units,id',
            'item_units.*.condition' => 'required|string',
            'item_units.*.photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Cek bahwa borrow_request milik user yang sedang login (jika perlu otentikasi user)
        $borrowRequest = $request->user()
            ? $request->user()->borrowRequests()->findOrFail($request->borrow_request_id)
            : BorrowRequest::findOrFail($request->borrow_request_id); // fallback kalau tidak pakai auth

        // Buat return request
        $returnRequest = ReturnRequest::create([
            'borrow_request_id' => $borrowRequest->id,
            'notes' => $request->notes,
            'status' => 'pending',
        ]);

        foreach ($request->item_units as $index => $unit) {
            $photoPath = null;

            if ($request->hasFile("item_units.$index.photo")) {
                $photoPath = $request->file("item_units.$index.photo")->store('return_photos', 'public');
            }

            ReturnDetail::create([
                'item_unit_id' => $unit['id'],
                'condition' => $unit['condition'],
                'return_request_id' => $returnRequest->id,
                'photo' => $photoPath,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pengembalian berhasil diajukan.',
            'data' => $returnRequest->load('returnDetails.itemUnit.item'),
        ]);
    }
}
