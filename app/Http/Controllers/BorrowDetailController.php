<?php

namespace App\Http\Controllers;

use App\Models\ItemUnit;
use App\Models\BorrowDetail;
use Illuminate\Http\Request;
use App\Models\BorrowRequest;

class BorrowDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $borrowDetails = BorrowDetail::with([
            'borrowRequest.user',
            'borrowRequest.approver',
            'itemUnit.item'
        ])->latest()->get();

        return view('borrow_details.index', compact('borrowDetails'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($borrowRequestId)
    {
        $borrowRequest = BorrowRequest::with('user')->findOrFail($borrowRequestId);
        $itemUnits = ItemUnit::with('item')
            ->where('status', 'available')
            ->whereHas('item', function ($query) {
                $query->where(function ($q) {
                    $q->where('type', '!=', 'consumable')
                        ->orWhere(function ($q2) {
                            $q2->where('type', 'consumable')
                                ->where('quantity', '>', 0);
                        });
                });
            })
            ->get();

        return view('borrow_details.create', compact('borrowRequest', 'itemUnits'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'borrow_request_id' => 'required|exists:borrow_requests,id',
            'item_unit_id' => 'required|exists:item_units,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $unit = ItemUnit::findOrFail($request->item_unit_id);
        $item = $unit->item; // relasi ke Item

        if ($item->type === 'consumable') {
            if ($unit->quantity === null || $unit->quantity < $request->quantity) {
                return back()->with('error', 'Stok tidak mencukupi untuk item: ' . $item->name);
            }

            $unit->quantity -= $request->quantity;

            // Jika stok habis, status bisa jadi 'unavailable' (opsional)
            if ($unit->quantity === 0) {
                $unit->status = 'unavailable';
            }

            $unit->save();
        } else {
            if ($unit->status !== 'available') {
                return back()->with('error', 'Item tidak tersedia untuk dipinjam.');
            }

            $unit->status = 'borrowed';
            $unit->save();
        }

        BorrowDetail::create($request->only('borrow_request_id', 'item_unit_id', 'quantity'));

        return redirect()->route('borrow-requests.show', $request->borrow_request_id)->with('success', 'Barang berhasil ditambahkan ke peminjaman.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
}
