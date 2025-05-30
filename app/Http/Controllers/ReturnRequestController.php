<?php
namespace App\Http\Controllers;

use App\Models\ReturnDetail;
use Illuminate\Http\Request;
use App\Models\BorrowRequest;
use App\Models\ReturnRequest;
use App\Exports\ReturnRequestsExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class ReturnRequestController extends Controller
{
    public function exportExcel()
    {
        return Excel::download(new ReturnRequestsExport, 'detail_pengembalian.xlsx');
    }

    public function exportPdf()
    {
        $returnDetails = ReturnDetail::with(['itemUnit.item', 'returnRequest.borrowRequest.user'])->get();

        $pdf = PDF::loadView('exports.return_details_pdf', compact('returnDetails'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('detail_pengembalian.pdf');
    }

    /**
     * Menampilkan daftar return requests.
     */
    public function index(Request $request)
    {
        $query = ReturnRequest::with(['borrowRequest.user']);

        // Filter pencarian
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->whereHas('borrowRequest.user', function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%");
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

        // Urutkan berdasarkan yang terbaru
        $query->orderBy('created_at', 'desc');

        $returns = $query->paginate(10);

        if ($request->ajax()) {
            return view('return_requests.partials._returns_table', compact('returns'));
        }

        return view('return_requests.index', compact('returns'));
    }

    /**
     * Menampilkan form untuk membuat return request baru berdasarkan borrow request.
     *
     * @param  BorrowRequest  $borrowRequest
     */
    public function create(BorrowRequest $borrowRequest)
    {
        // Memuat semua detail dari borrow request termasuk item unit yang dipinjam
        $borrowRequest->load('borrowDetail.itemUnit.item');

        return view('return_requests.create', compact('borrowRequest'));
    }

    /**
     * Menyimpan return request yang baru dibuat ke dalam database.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        // Melakukan validasi terhadap input request
        $request->validate([
            'borrow_request_id' => 'required|exists:borrow_requests,id',
            'notes' => 'nullable|string',
            'item_units.*.id' => 'required|exists:item_units,id',
            'item_units.*.condition' => 'required|string',
            'item_units.*.photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'item_units.*.quantity' => 'required|numeric',
        ]);

        // Membuat return request baru
        $returnRequest = ReturnRequest::create([
            'borrow_request_id' => $request->borrow_request_id,
            'notes' => $request->notes,
        ]);

        // Menyimpan detail pengembalian untuk setiap item unit yang terkait
        foreach ($request->item_units as $unit) {
            $photoPath = null;

            // Menyimpan foto jika ada
            if (isset($unit['photo'])) {
                $photoPath = $unit['photo']->store('return_photos', 'public');
            }

            // Membuat return detail untuk setiap item unit
            ReturnDetail::create([
                'item_unit_id' => $unit['id'],
                'condition' => $unit['condition'],
                'return_request_id' => $returnRequest->id,
                'quantity' => $unit['quantity'],
                'photo' => $photoPath,
            ]);
        }

        // Mengalihkan ke halaman daftar return requests dengan pesan sukses
        return redirect()->route('return-requests.index')->with('success', 'Pengembalian berhasil diajukan.');
    }

    /**
     * Menampilkan detail return request berdasarkan ID.
     *
     * @param  ReturnRequest  $returnRequest  Data return request yang akan ditampilkan.
     */
    public function show(ReturnRequest $returnRequest)
    {
        // Memuat data return details dan borrow request terkait beserta user yang meminjam
        $returnRequest->load('returnDetails.itemUnit', 'borrowRequest.user');

        return view('return_requests.show', compact('returnRequest'));
    }

    /**
     * Menampilkan form untuk mengedit return request.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Memperbarui return request berdasarkan ID.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Menghapus return request berdasarkan ID.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Menyetujui return request yang diajukan.
     *
     * @param  ReturnRequest  $returnRequest
     */
    public function approve(ReturnRequest $returnRequest)
    {
        // Ubah status return request menjadi 'approved'
        $returnRequest->update(['status' => 'approved']);

        foreach ($returnRequest->returnDetails as $detail) {
            // Ubah status unit menjadi 'available'
            $detail->itemUnit->update(['status' => 'available']);

            // Ambil item terkait
            $item = $detail->itemUnit->item;

            // Ambil warehouse dari relasi item
            $warehouse = $item->warehouse; // pastikan relasi ini ada

            $warehouse->used_capacity += $detail->quantity;
        }

        return back()->with('success', 'Pengembalian disetujui.');
    }

    /**
     * Menolak return request yang diajukan.
     *
     * @param  ReturnRequest  $returnRequest
     */
    public function reject(ReturnRequest $returnRequest)
    {
        // Mengubah status return request menjadi rejected
        $returnRequest->update(['status' => 'rejected']);

        return back()->with('error', 'Pengembalian ditolak.');
    }
}
