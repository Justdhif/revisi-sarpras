<?php

namespace App\Http\Controllers;

use App\Models\ItemUnit;
use Barryvdh\DomPDF\PDF;
use App\Models\ReturnDetail;
use Illuminate\Http\Request;
use App\Models\BorrowRequest;
use App\Models\ReturnRequest;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReturnRequestsExport;
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
        $returns = $this->getFilteredReturnRequests($request);

        if ($request->ajax()) {
            return $this->getAjaxResponse($returns);
        }

        return view('return_requests.index', ['returns' => $returns]);
    }

    public function create(BorrowRequest $borrowRequest)
    {
        $borrowRequest->load('borrowDetail.itemUnit.item');
        return view('return_requests.create', compact('borrowRequest'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateReturnRequest($request);
        $returnRequest = $this->createReturnRequest($validated);
        $this->createReturnDetails($returnRequest, $validated['item_units']);

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
            $this->processApproval($returnRequest);
            DB::commit();

            $returnRequest->user->notify(new ReturnApprovedNotification($returnRequest));
            return back()->with('success', 'Pengembalian disetujui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function reject(ReturnRequest $returnRequest)
    {
        $returnRequest->update(['status' => 'rejected']);
        $returnRequest->user->notify(new ReturnRejectedNotification($returnRequest));

        return back()->with('error', 'Pengembalian ditolak.');
    }

    private function getFilteredReturnRequests(Request $request)
    {
        $query = ReturnRequest::with(['borrowRequest.requester', 'borrowRequest.item']);

        if ($request->filled('search')) {
            $query->whereHas('borrowRequest.requester', fn($q) => $q->where('name', 'like', "%{$request->search}%"));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('return_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('return_date', '<=', $request->end_date);
        }

        return $query->orderBy('created_at', 'desc')->paginate(self::PAGINATION_COUNT);
    }

    private function getAjaxResponse($returns)
    {
        return response()->json([
            'data' => $returns->items(),
            'current_page' => $returns->currentPage(),
            'last_page' => $returns->lastPage(),
            'from' => $returns->firstItem(),
            'to' => $returns->lastItem(),
            'total' => $returns->total(),
            'links' => $returns->links()->elements,
        ]);
    }

    private function validateReturnRequest(Request $request)
    {
        return $request->validate([
            'borrow_request_id' => 'required|exists:borrow_requests,id',
            'notes' => 'nullable|string',
            'item_units.*.id' => 'required|exists:item_units,id',
            'item_units.*.condition' => 'required|string',
            'item_units.*.photo' => 'required|image|mimes:jpeg,png,jpg|max:' . self::MAX_PHOTO_SIZE,
            'item_units.*.quantity' => 'required|numeric',
        ]);
    }

    private function createReturnRequest(array $data)
    {
        return ReturnRequest::create([
            'borrow_request_id' => $data['borrow_request_id'],
            'notes' => $data['notes'],
        ]);
    }

    private function createReturnDetails(ReturnRequest $returnRequest, array $itemUnits)
    {
        foreach ($itemUnits as $unit) {
            $photoPath = $unit['photo']->store(self::PHOTO_STORAGE_PATH, 'public');

            ReturnDetail::create([
                'item_unit_id' => $unit['id'],
                'condition' => $unit['condition'],
                'return_request_id' => $returnRequest->id,
                'quantity' => $unit['quantity'],
                'photo' => $photoPath,
            ]);
        }
    }

    private function processApproval(ReturnRequest $returnRequest)
    {
        $returnRequest->update(['status' => 'approved']);

        foreach ($returnRequest->returnDetails as $detail) {
            $this->processReturnDetail($detail);
        }
    }

    private function processReturnDetail(ReturnDetail $detail)
    {
        $itemUnit = $detail->itemUnit;
        $item = $itemUnit->item;

        $this->recordStockMovement($itemUnit, $detail->quantity);

        if ($item->type === 'consumable') {
            $itemUnit->quantity += $detail->quantity;
        }

        $itemUnit->status = 'available';
        $itemUnit->save();
    }

    private function recordStockMovement(ItemUnit $itemUnit, $quantity)
    {
        StockMovement::create([
            'item_unit_id' => $itemUnit->id,
            'type' => 'in',
            'quantity' => $quantity,
            'description' => 'Persetujuan pengembalian',
            'movement_date' => now(),
        ]);
    }
}
