<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\ReturnDetail;
use App\Notifications\UserRequestedReturnNotification;
use Illuminate\Http\Request;
use App\Models\BorrowRequest;
use App\Models\ReturnRequest;
use App\Models\CustomNotification;
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
            'return_details' => 'required|array|min:1',
            'return_details.*.item_unit_id' => 'required|exists:item_units,id',
            'return_details.*.condition' => 'required|string',
            'return_details.*.photo' => 'nullable|image|max:2048',
            'return_details.*.quantity' => 'nullable|integer|min:1',
        ]);

        $return = ReturnRequest::create([
            'borrow_request_id' => $request->borrow_request_id,
            'notes' => $request->notes,
            'user_id' => auth()->id(),
            'status' => 'pending',
        ]);

        foreach ($request->return_details as $detail) {
            $photoPath = null;

            if (isset($detail['photo'])) {
                $photo = $detail['photo'];
                $photoPath = $photo->store('return_photos', 'public');
            }

            ReturnDetail::create([
                'return_request_id' => $return->id,
                'item_unit_id' => $detail['item_unit_id'],
                'condition' => $detail['condition'],
                'quantity' => $detail['quantity'] ?? 1, // ✅ default quantity = 1
                'photo' => $photoPath,
            ]);
        }

        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            $admin->notify(new UserRequestedReturnNotification($return));
        }

        return response()->json([
            'success' => true,
            'message' => 'Pengembalian berhasil disimpan',
            'data' => $return->load('returnDetails'),
        ]);
    }
}
