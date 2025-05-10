<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BorrowRequest;
use Illuminate\Support\Facades\Auth;

class BorrowRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $requests = BorrowRequest::with('user', 'approver')->latest()->get();
        return view('borrow_requests.index', compact('requests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('borrow_requests.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'return_date_expected' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        BorrowRequest::create([
            'return_date_expected' => $request->return_date_expected,
            'notes' => $request->notes,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('borrow-requests.index')->with('success', 'Request created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(BorrowRequest $borrowRequest)
    {
        $borrowRequest->with(['user', 'borrowDetail.itemUnit.item']);
        return view('borrow_requests.show', compact('borrowRequest'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BorrowRequest $borrowRequest)
    {
        return view('borrow_requests.edit', compact('borrowRequest'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BorrowRequest $borrowRequest)
    {
        $request->validate([
            'return_date_expected' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $borrowRequest->update($request->only('return_date_expected', 'notes'));

        return redirect()->route('borrow-requests.index')->with('success', 'Request updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BorrowRequest $borrowRequest)
    {
        $borrowRequest->delete();
        return back()->with('success', 'Request deleted.');
    }

    public function approve($id)
    {
        $request = BorrowRequest::with('borrowDetail.itemUnit.item')->findOrFail($id);

        foreach ($request->borrowDetail as $detail) {
            $item = $detail->itemUnit->item;

            if ($item->type === 'consumable') {
                // Kurangi stok dari item unit
                $itemUnit = $detail->itemUnit;
                if ($itemUnit->quantity < $detail->quantity) {
                    return back()->with('error', 'Stok tidak mencukupi untuk item: ' . $item->name);
                }
                $itemUnit->quantity -= $detail->quantity;
                $itemUnit->save();
            } else {
                // Non-consumable: ubah status jadi borrowed
                $itemUnit = $detail->itemUnit;
                $itemUnit->status = 'borrowed';
                $itemUnit->save();
            }
        }

        $request->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
        ]);

        return back()->with('success', 'Request approved.');
    }

    public function reject($id)
    {
        $request = BorrowRequest::findOrFail($id);
        $request->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
        ]);

        return back()->with('success', 'Request rejected.');
    }
}
