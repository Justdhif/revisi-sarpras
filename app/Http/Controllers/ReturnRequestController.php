<?php
namespace App\Http\Controllers;

use App\Models\ReturnDetail;
use Illuminate\Http\Request;
use App\Models\BorrowRequest;
use App\Models\ReturnRequest;

class ReturnRequestController extends Controller
{
    /**
     * Menampilkan daftar return requests.
     */
    public function index()
    {
        // Mengambil semua return request dengan relasi borrow request dan user
        $returns = ReturnRequest::with('borrowRequest.user')->latest()->get();

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
        // Mengubah status return request menjadi approved
        $returnRequest->update(['status' => 'approved']);

        // Mengubah status setiap item unit yang terkait menjadi available
        foreach ($returnRequest->returnDetails as $detail) {
            $detail->itemUnit->update(['status' => 'available']);
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
