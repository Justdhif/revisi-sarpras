<?php

namespace App\Http\Controllers;

use App\Models\ItemUnit;
use Barryvdh\DomPDF\PDF;
use App\Models\BorrowDetail;
use Illuminate\Http\Request;
use App\Models\BorrowRequest;
use App\Models\CustomNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BorrowRequestsExport;
use App\Notifications\BorrowApprovedNotification;
use App\Notifications\BorrowRejectedNotification;

class BorrowRequestController extends Controller
{
    public function exportExcel()
    {
        return Excel::download(new BorrowRequestsExport, 'data_peminjaman.xlsx');
    }

    public function exportPdf()
    {
        $borrowRequests = BorrowRequest::with(['user', 'approver', 'borrowDetails.itemUnit.item'])->get();

        $pdf = PDF::loadView('borrow_requests.pdf', compact('borrowRequests'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('data_peminjaman.pdf');
    }

    /**
     * Menampilkan semua permintaan peminjaman.
     */
    public function index(Request $request)
    {
        $query = BorrowRequest::with(['requester', 'approver']);

        // Filter pencarian
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('requester', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })
                    ->orWhereHas('approver', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Filter status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Filter tanggal
        if ($request->has('start_date') && !empty($request->start_date)) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->has('end_date') && !empty($request->end_date)) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $requests = $query->orderBy('created_at', 'desc')->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'data' => $requests->items(),
                'current_page' => $requests->currentPage(),
                'last_page' => $requests->lastPage(),
                'from' => $requests->firstItem(),
                'to' => $requests->lastItem(),
                'total' => $requests->total(),
                'links' => $requests->links()->elements,
            ]);
        }

        return view('borrow_requests.index', [
            'requests' => $requests
        ]);
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

        DB::beginTransaction();
        try {
            foreach ($request->borrowDetail as $detail) {
                $itemUnit = $detail->itemUnit;
                $item = $itemUnit->item;

                if ($item->type === 'consumable') {
                    if ($itemUnit->quantity < $detail->quantity) {
                        DB::rollBack();
                        return back()->with('error', 'Stok tidak mencukupi untuk item: ' . $item->name);
                    }
                    $itemUnit->quantity -= $detail->quantity;

                    // Simpan stock movement keluar
                    StockMovement::create([
                        'item_unit_id' => $itemUnit->id,
                        'type' => 'out',
                        'quantity' => $detail->quantity,
                        'description' => 'Persetujuan peminjaman',
                        'movement_date' => now(),
                    ]);

                    if ($itemUnit->quantity === 0) {
                        $itemUnit->status = 'out_of_stock';
                    }
                    $itemUnit->save();

                } else {
                    // Untuk barang non-consumable langsung ubah status jadi borrowed
                    $itemUnit->status = 'borrowed';
                    $itemUnit->save();

                    // Catat stock movement sebagai 'out' untuk peminjaman barang non-consumable juga
                    StockMovement::create([
                        'item_unit_id' => $itemUnit->id,
                        'type' => 'out',
                        'quantity' => $detail->quantity,
                        'description' => 'Persetujuan peminjaman',
                        'movement_date' => now(),
                    ]);
                }
            }

            // Update status borrow request dan approved_by
            $request->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
            ]);

            // Kirim notifikasi ke user
            $request->user->notify(new BorrowApprovedNotification($request));

            DB::commit();
            return back()->with('success', 'Permintaan berhasil disetujui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
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

        foreach ($request->borrowDetail as $detail) {
            $itemUnit = $detail->itemUnit;
            if ($itemUnit->item->type !== 'consumable') {
                $itemUnit->status = 'available';
                $itemUnit->save();
            }
        }

        $request->user->notify(new BorrowRejectedNotification($request));

        return back()->with('success', 'Permintaan berhasil ditolak.');
    }
}
