<?php
namespace App\Http\Controllers;

use App\Models\ItemUnit;
use App\Models\ReturnDetail;
use Illuminate\Http\Request;
use App\Models\ReturnRequest;

class ReturnDetailController extends Controller
{
    /**
     * Menampilkan daftar return detail.
     */
    public function index()
    {
        //
    }

    /**
     * Menampilkan form untuk membuat return detail baru.
     *
     * @param  int  $returnRequestId  ID dari return request yang terkait.
     */
    public function create($returnRequestId)
    {
        // Mendapatkan data return request berdasarkan ID
        $returnRequest = ReturnRequest::findOrFail($returnRequestId);

        // Mengambil semua item unit yang terkait dengan barang yang dapat dikembalikan
        $itemUnits = ItemUnit::with('item')->get();

        return view('return_details.create', compact('returnRequest', 'itemUnits'));
    }

    /**
     * Menyimpan return detail yang baru dibuat.
     *
     * @param  \Illuminate\Http\Request  $request  Data request dari form.
     */
    public function store(Request $request)
    {
        // Melakukan validasi terhadap input
        $validated = $request->validate([
            'condition' => 'required|string',
            'item_unit_id' => 'required|exists:item_units,id',
            'return_request_id' => 'required|exists:return_requests,id',
        ]);

        // Membuat return detail baru berdasarkan data yang telah divalidasi
        $detail = ReturnDetail::create($validated);

        // Mengembalikan response JSON sebagai konfirmasi
        return response()->json([
            'message' => 'Return detail added successfully.',
            'data' => $detail,
        ]);
    }

    /**
     * Menampilkan detail dari return request berdasarkan ID.
     * Fungsi ini belum diimplementasikan.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Menampilkan form untuk mengedit return detail berdasarkan ID.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Memperbarui return detail yang ada berdasarkan ID.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Menghapus return detail berdasarkan ID.
     */
    public function destroy(string $id)
    {
        //
    }
}
