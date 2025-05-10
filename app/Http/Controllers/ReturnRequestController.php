<?php

namespace App\Http\Controllers;

use App\Models\BorrowDetail;
use App\Models\ReturnDetail;
use Illuminate\Http\Request;
use App\Models\BorrowRequest;
use App\Models\ReturnRequest;

class ReturnRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $returns = ReturnRequest::with('borrowRequest.user')->latest()->get();
        return view('return_requests.index', compact('returns'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(BorrowRequest $borrowRequest)
    {
        $borrowRequest->load('borrowDetail.itemUnit.item');
        return view('return_requests.create', compact('borrowRequest'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'borrow_request_id' => 'required|exists:borrow_requests,id',
            'notes' => 'nullable|string',
            'item_units.*.id' => 'required|exists:item_units,id',
            'item_units.*.condition' => 'required|string',
            'item_units.*.photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $returnRequest = ReturnRequest::create([
            'borrow_request_id' => $request->borrow_request_id,
            'notes' => $request->notes,
        ]);

        foreach ($request->item_units as $unit) {
            $photoPath = null;

            if (isset($unit['photo'])) {
                $photoPath = $unit['photo']->store('return_photos', 'public');
            }

            ReturnDetail::create([
                'item_unit_id' => $unit['id'],
                'condition' => $unit['condition'],
                'return_request_id' => $returnRequest->id,
                'photo' => $photoPath,
            ]);
        }

        return redirect()->route('return-requests.index')->with('success', 'Pengembalian berhasil diajukan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ReturnRequest $returnRequest)
    {
        $returnRequest->load('returnDetails.itemUnit', 'borrowRequest.user');
        return view('return_requests.show', compact('returnRequest'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function approve(ReturnRequest $returnRequest)
    {
        $returnRequest->update(['status' => 'approved']);
        foreach ($returnRequest->returnDetails as $detail) {
            $detail->itemUnit->update(['status' => 'available']);
        }
        return back()->with('success', 'Pengembalian disetujui.');
    }

    public function reject(ReturnRequest $returnRequest)
    {
        $returnRequest->update(['status' => 'rejected']);
        return back()->with('error', 'Pengembalian ditolak.');
    }
}
