<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\ItemUnit;
use Barryvdh\DomPDF\PDF;
use App\Models\BorrowDetail;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\BorrowRequest;
use App\Models\ReturnRequest;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BorrowRequestsExport;
use App\Notifications\BorrowApprovedNotification;
use App\Notifications\BorrowRejectedNotification;

class BorrowRequestController extends Controller
{
    const PAGINATION_COUNT = 10;
    const EXPORT_FILENAME = 'data_peminjaman';

    public function exportExcel()
    {
        $filename = self::EXPORT_FILENAME . '-' . now()->format('Y-m-d') . '.xlsx';
        return Excel::download(new BorrowRequestsExport, $filename);
    }

    public function exportPdf()
    {
        $borrowRequests = BorrowRequest::with(['user', 'handler', 'borrowDetails.itemUnit.item'])->get();
        $filename = self::EXPORT_FILENAME . '-' . now()->format('Y-m-d') . '.pdf';

        $pdf = PDF::loadView('borrow_requests.pdf', compact('borrowRequests'))
            ->setPaper('a4', 'landscape');

        return $pdf->download($filename);
    }

    public function index(Request $request)
    {
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');

        $query = BorrowRequest::with(['user', 'handler', 'returnRequest'])
            ->when($request->search, function ($query) use ($request) {
                return $query->whereHas('user', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%');
                })->orWhereHas('handler', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%');
                });
            })
            ->when($request->status, function ($query) use ($request) {
                return $query->where('status', $request->status);
            })
            ->when($request->start_date, function ($query) use ($request) {
                return $query->whereDate('created_at', '>=', $request->start_date);
            })
            ->when($request->end_date, function ($query) use ($request) {
                return $query->whereDate('created_at', '<=', $request->end_date);
            })
            ->orderBy($sortField, $sortDirection);

        $borrowRequests = $query->paginate(10);

        return view('borrow_requests.index', [
            'borrowRequests' => $borrowRequests,
            'sortField' => $sortField,
            'sortDirection' => $sortDirection,
        ]);
    }

    public function show(BorrowRequest $borrowRequest)
    {
        $borrowRequest->load(['user', 'borrowDetail.itemUnit.item']);
        return view('borrow_requests.show', compact('borrowRequest'));
    }

    public function edit(BorrowRequest $borrowRequest)
    {
        return view('borrow_requests.edit', compact('borrowRequest'));
    }

    public function update(Request $request, BorrowRequest $borrowRequest)
    {
        $validated = $request->validate([
            'return_date_expected' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $borrowRequest->update($validated);

        return redirect()->route('borrow-requests.index')
            ->with('success', 'Permintaan berhasil diperbarui.');
    }

    public function destroy(BorrowRequest $borrowRequest)
    {
        $borrowRequest->delete();
        return back()->with('success', 'Permintaan berhasil dihapus.');
    }

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
                        throw new \Exception('Stok tidak mencukupi untuk item: ' . $itemUnit->item->name);
                    }

                    $itemUnit->quantity -= $detail->quantity;
                    $itemUnit->status = $itemUnit->quantity === 0 ? 'out_of_stock' : 'available';
                    $itemUnit->save();
                } else {
                    $itemUnit->status = 'borrowed';
                    $itemUnit->save();
                }
            }

            $request->update([
                'status' => 'approved',
                'handled_by' => Auth::id(),
            ]);

            Notification::create([
                'sender_id' => Auth::id(),
                'receiver_id' => $request->user_id,
                'notification_type' => 'approved_borrow_request',
                'message' => 'Permintaan peminjaman telah disetujui',
                'borrow_request_id' => $request->id
            ]);

            DB::commit();

            return back()->with('success', 'Permintaan berhasil disetujui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function reject($id)
    {
        $request = BorrowRequest::with('borrowDetails.itemUnit')->findOrFail($id);

        $request->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
        ]);

        foreach ($request->borrowDetails as $detail) {
            if ($detail->itemUnit->item->type !== 'consumable') {
                $detail->itemUnit->status = 'available';
                $detail->itemUnit->save();
            }
        }

        Notification::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->user_id,
            'notification_type' => 'rejected_borrow_request',
            'message' => 'Permintaan peminjaman telah ditolak',
            'borrow_request_id' => $request->id
        ]);

        return back()->with('success', 'Permintaan berhasil ditolak.');
    }

    public function sendReminder($id)
    {
        $borrow = BorrowRequest::findOrFail($id);
        $hasReturned = ReturnRequest::where('borrow_request_id', $borrow->id)
            ->where('status', 'pending')
            ->exists();

        if ($borrow->status === 'approved' && !$hasReturned && Carbon::parse($borrow->return_date_expected)->isPast()) {
            Notification::create([
                'sender_id' => auth()->id(),
                'receiver_id' => $borrow->user_id,
                'notification_type' => 'reminder_peminjaman',
                'message' => 'Peringatan: Anda belum mengembalikan barang yang seharusnya dikembalikan pada ' . Carbon::parse($borrow->return_date_expected)->translatedFormat('d F Y H:i') . '.',
                'borrow_request_id' => $borrow->id,
            ]);

            return back()->with('success', 'Peringatan berhasil dikirim.');
        }

        return back()->with('error', 'Peringatan tidak dapat dikirim. Mungkin barang sudah dikembalikan atau belum melewati batas waktu.');
    }
}
