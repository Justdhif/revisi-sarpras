<?php

namespace App\Http\Controllers;

use App\Models\ItemUnit;
use Barryvdh\DomPDF\PDF;
use App\Models\Notification;
use App\Models\ReturnDetail;
use Illuminate\Http\Request;
use App\Models\BorrowRequest;
use App\Models\ReturnRequest;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReturnRequestsExport;
use Illuminate\Support\Facades\Storage;
use App\Notifications\ReturnApprovedNotification;
use App\Notifications\ReturnRejectedNotification;

class ReturnRequestController extends Controller
{
    const PAGINATION_COUNT = 10;
    const EXPORT_FILENAME = 'detail_pengembalian';
    const PHOTO_STORAGE_PATH = 'return_photos';
    const MAX_PHOTO_SIZE = 2048;

    public function exportExcel()
    {
        $filename = self::EXPORT_FILENAME . '-' . now()->format('Y-m-d') . '.xlsx';
        return Excel::download(new ReturnRequestsExport, $filename);
    }

    public function exportPdf()
    {
        $returnDetails = ReturnDetail::with(['itemUnit.item', 'returnRequest.borrowRequest.user'])->get();
        $filename = self::EXPORT_FILENAME . '-' . now()->format('Y-m-d') . '.pdf';

        $pdf = PDF::loadView('exports.return_details_pdf', compact('returnDetails'))
            ->setPaper('a4', 'landscape');

        return $pdf->download($filename);
    }

    public function index(Request $request)
    {
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');

        $query = ReturnRequest::with(['borrowRequest.user', 'returnDetails.itemUnit'])
            ->when($request->search, function ($query) use ($request) {
                return $query->whereHas('borrowRequest.user', function ($q) use ($request) {
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

        $returnRequests = $query->paginate(10);

        return view('return_requests.index', [
            'returnRequests' => $returnRequests,
            'sortField' => $sortField,
            'sortDirection' => $sortDirection,
        ]);
    }

    public function create(BorrowRequest $borrowRequest)
    {
        $borrowRequest->load('borrowDetail.itemUnit.item');
        return view('return_requests.create', compact('borrowRequest'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'borrow_request_id' => 'required|exists:borrow_requests,id',
            'notes' => 'nullable|string',
            'item_units.*.id' => 'required|exists:item_units,id',
            'item_units.*.condition' => 'required|string',
            'item_units.*.photo' => 'required|image|mimes:jpeg,png,jpg|max:' . self::MAX_PHOTO_SIZE,
            'item_units.*.quantity' => 'required|numeric',
        ]);

        $returnRequest = ReturnRequest::create([
            'borrow_request_id' => $validated['borrow_request_id'],
            'notes' => $validated['notes'],
        ]);

        foreach ($validated['item_units'] as $unit) {
            $photoPath = $unit['photo']->store(self::PHOTO_STORAGE_PATH, 'public');

            ReturnDetail::create([
                'item_unit_id' => $unit['id'],
                'condition' => $unit['condition'],
                'return_request_id' => $returnRequest->id,
                'quantity' => $unit['quantity'],
                'photo' => $photoPath,
            ]);
        }

        return redirect()->route('return-requests.index')
            ->with('success', 'Pengembalian berhasil diajukan.');
    }

    public function show(ReturnRequest $returnRequest)
    {
        $returnRequest->load('returnDetails.itemUnit', 'borrowRequest.user');
        return view('return_requests.show', compact('returnRequest'));
    }

    public function edit(string $id)
    {
        // Implementation pending
    }

    public function update(Request $request, string $id)
    {
        // Implementation pending
    }

    public function destroy(string $id)
    {
        // Implementation pending
    }

    public function approve(ReturnRequest $returnRequest)
    {
        DB::beginTransaction();

        try {
            $returnRequest->update([
                'status' => 'approved',
                'handled_by' => Auth::id(),
            ]);

            foreach ($returnRequest->returnDetails as $detail) {
                $itemUnit = $detail->itemUnit;
                $item = $itemUnit->item;

                StockMovement::create([
                    'item_unit_id' => $itemUnit->id,
                    'movement_type' => 'in',
                    'quantity' => $detail->quantity,
                    'description' => 'Persetujuan pengembalian',
                    'movement_date' => now(),
                ]);

                if ($item->type === 'consumable') {
                    $itemUnit->quantity += $detail->quantity;
                }

                $itemUnit->status = 'available';
                $itemUnit->save();
            }

            Notification::create([
                'sender_id' => Auth::id(),
                'receiver_id' => $returnRequest->user_id,
                'notification_type' => 'approved_return_request',
                'message' => 'Permintaan pengembalian telah disetujui',
                'return_request_id' => $returnRequest->id
            ]);

            DB::commit();

            return back()->with('success', 'Pengembalian disetujui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function reject(ReturnRequest $returnRequest)
    {
        $returnRequest->update(['status' => 'rejected']);

        return back()->with('error', 'Pengembalian ditolak.');
    }
}
