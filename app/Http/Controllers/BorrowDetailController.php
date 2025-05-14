<?php

namespace App\Http\Controllers;

use App\Models\ItemUnit;
use App\Models\BorrowDetail;
use App\Models\BorrowRequest;
use Illuminate\Http\Request;

class BorrowDetailController extends Controller
{
    /**
     * Menampilkan daftar detail peminjaman.
     *
     * @return \Illuminate\View\View
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
     * Menampilkan form untuk menambahkan item ke dalam permintaan peminjaman.
     *
     * @param  int  $borrowRequestId
     * @return \Illuminate\View\View
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
     * Menyimpan data peminjaman barang ke dalam database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
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
            // Validasi stok barang habis pakai
            if ($unit->quantity === null || $unit->quantity < $request->quantity) {
                return back()->with('error', 'Stok tidak mencukupi untuk item: ' . $item->name);
            }

            $unit->quantity -= $request->quantity;

            // Tandai barang tidak tersedia jika stok habis
            if ($unit->quantity === 0) {
                $unit->status = 'unavailable';
            }

            $unit->save();
        } else {
            // Validasi barang tidak habis pakai harus tersedia
            if ($unit->status !== 'available') {
                return back()->with('error', 'Item tidak tersedia untuk dipinjam.');
            }

            $unit->status = 'borrowed';
            $unit->save();
        }

        BorrowDetail::create($request->only('borrow_request_id', 'item_unit_id', 'quantity'));

        return redirect()
            ->route('borrow-requests.show', $request->borrow_request_id)
            ->with('success', 'Barang berhasil ditambahkan ke peminjaman.');
    }

    /**
     * Placeholder untuk menampilkan detail peminjaman tertentu.
     *
     * @param  string  $id
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Placeholder untuk menampilkan form edit peminjaman.
     *
     * @param  string  $id
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Placeholder untuk memperbarui data peminjaman.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Placeholder untuk menghapus data peminjaman.
     *
     * @param  string  $id
     */
    public function destroy(string $id)
    {
        //
    }
}
