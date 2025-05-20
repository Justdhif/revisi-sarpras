<?php

namespace App\Http\Controllers;

use App\Models\ItemUnit;
use App\Models\BorrowDetail;
use Illuminate\Http\Request;
use App\Models\BorrowRequest;
use Illuminate\Support\Facades\Auth;

class BorrowRequestController extends Controller
{
    /**
     * Menampilkan semua permintaan peminjaman.
     */
    public function index()
    {
        $requests = BorrowRequest::with('user', 'approver')->latest()->get();
        return view('borrow_requests.index', compact('requests'));
    }

    /**
     * Menampilkan detail dari permintaan peminjaman.
     */
    public function show(BorrowRequest $borrowRequest)
    {
        // Pastikan eager loading diproses
        $borrowRequest->load(['user', 'borrowDetail.itemUnit.item']);

        return view('borrow_requests.show', compact('borrowRequest'));
    }

    /**
     * Menampilkan form edit permintaan peminjaman.
     */
    public function edit(BorrowRequest $borrowRequest)
    {
        return view('borrow_requests.edit', compact('borrowRequest'));
    }

    /**
     * Memperbarui data permintaan peminjaman.
     */
    public function update(Request $request, BorrowRequest $borrowRequest)
    {
        $request->validate([
            'return_date_expected' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $borrowRequest->update($request->only('return_date_expected', 'notes'));

        return redirect()->route('borrow-requests.index')->with('success', 'Permintaan berhasil diperbarui.');
    }

    /**
     * Menghapus permintaan peminjaman dari database.
     */
    public function destroy(BorrowRequest $borrowRequest)
    {
        $borrowRequest->delete();
        return back()->with('success', 'Permintaan berhasil dihapus.');
    }

    /**
     * Menyetujui permintaan peminjaman.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve($id)
    {
        $request = BorrowRequest::with('borrowDetail.itemUnit.item')->findOrFail($id);

        foreach ($request->borrowDetail as $detail) {
            $itemUnit = $detail->itemUnit;
            $item = $itemUnit->item;

            if ($item->type === 'consumable') {
                // ✅ Validasi stok
                if ($itemUnit->quantity < $detail->quantity) {
                    return back()->with('error', 'Stok tidak mencukupi untuk item: ' . $item->name);
                }

                // ✅ Kurangi stok
                $itemUnit->quantity -= $detail->quantity;

                // ✅ Update status jika stok habis
                if ($itemUnit->quantity === 0) {
                    $itemUnit->status = 'out_of_stock';
                }

                $itemUnit->save();

            } else {
                // ✅ Untuk non-consumable (e.g., laptop, kamera), status jadi borrowed
                $itemUnit->status = 'borrowed';
                $itemUnit->save();
            }
        }

        $request->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
        ]);

        return back()->with('success', 'Permintaan berhasil disetujui.');
    }

    /**
     * Menolak permintaan peminjaman.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject($id)
    {
        $request = BorrowRequest::findOrFail($id);

        $request->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
        ]);

        return back()->with('success', 'Permintaan berhasil ditolak.');
    }
}
