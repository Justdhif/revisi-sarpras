<?php

namespace App\Http\Controllers;

use App\Models\ItemUnit;
use Barryvdh\DomPDF\PDF;
use App\Models\BorrowDetail;
use Illuminate\Http\Request;
use App\Models\BorrowRequest;
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
        $borrowRequests = BorrowRequest::with(['user', 'approver', 'borrowDetails.itemUnit.item'])->get();
        $filename = self::EXPORT_FILENAME . '-' . now()->format('Y-m-d') . '.pdf';

        $pdf = PDF::loadView('borrow_requests.pdf', compact('borrowRequests'))
            ->setPaper('a4', 'landscape');

        return $pdf->download($filename);
    }

    public function index(Request $request)
    {
        $query = $this->buildFilterQuery($request);
        $requests = $query->orderBy('created_at', 'desc')->paginate(self::PAGINATION_COUNT);

        if ($request->ajax()) {
            return $this->ajaxResponse($requests);
        }

        return view('borrow_requests.index', ['requests' => $requests]);
    }

    public function show(BorrowRequest $borrowRequest)
    {
        $borrowRequest->load(['user', 'borrowDetails.itemUnit.item']);
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
        $request = BorrowRequest::with('borrowDetails.itemUnit.item')->findOrFail($id);

        DB::beginTransaction();
        try {
            $this->processApproval($request);
            DB::commit();

            $request->user->notify(new BorrowApprovedNotification($request));
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

        $this->returnNonConsumableItems($request);
        $request->user->notify(new BorrowRejectedNotification($request));

        return back()->with('success', 'Permintaan berhasil ditolak.');
    }

    private function buildFilterQuery(Request $request)
    {
        $query = BorrowRequest::with(['requester', 'approver']);

        if ($request->filled('search')) {
            $query->whereHas('requester', fn($q) => $q->where('name', 'like', "%{$request->search}%"))
                ->orWhereHas('approver', fn($q) => $q->where('name', 'like', "%{$request->search}%"));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        return $query;
    }

    private function ajaxResponse($requests)
    {
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

    private function processApproval(BorrowRequest $request)
    {
        foreach ($request->borrowDetails as $detail) {
            $this->processBorrowDetail($detail);
        }

        $request->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
        ]);
    }

    private function processBorrowDetail(BorrowDetail $detail)
    {
        $itemUnit = $detail->itemUnit;
        $item = $itemUnit->item;

        if ($item->type === 'consumable') {
            $this->handleConsumableItem($detail, $itemUnit);
        } else {
            $this->handleNonConsumableItem($itemUnit);
        }

        $this->recordStockMovement($itemUnit, $detail->quantity);
    }

    private function handleConsumableItem(BorrowDetail $detail, ItemUnit $itemUnit)
    {
        if ($itemUnit->quantity < $detail->quantity) {
            throw new \Exception('Stok tidak mencukupi untuk item: ' . $itemUnit->item->name);
        }

        $itemUnit->quantity -= $detail->quantity;
        $itemUnit->status = $itemUnit->quantity === 0 ? 'out_of_stock' : 'available';
        $itemUnit->save();
    }

    private function handleNonConsumableItem(ItemUnit $itemUnit)
    {
        $itemUnit->status = 'borrowed';
        $itemUnit->save();
    }

    private function recordStockMovement(ItemUnit $itemUnit, $quantity)
    {
        StockMovement::create([
            'item_unit_id' => $itemUnit->id,
            'type' => 'out',
            'quantity' => $quantity,
            'description' => 'Persetujuan peminjaman',
            'movement_date' => now(),
        ]);
    }

    private function returnNonConsumableItems(BorrowRequest $request)
    {
        foreach ($request->borrowDetails as $detail) {
            if ($detail->itemUnit->item->type !== 'consumable') {
                $detail->itemUnit->status = 'available';
                $detail->itemUnit->save();
            }
        }
    }
}
