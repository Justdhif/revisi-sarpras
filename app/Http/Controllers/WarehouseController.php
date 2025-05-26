<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    /**
     * Menampilkan daftar semua warehouse yang tersedia, dengan pagination.
     */
    public function index()
    {
        // Mengambil data warehouse terbaru dan menampilkan 10 per halaman
        $warehouses = Warehouse::latest()->paginate(10);

        // Menampilkan halaman daftar warehouse dengan data yang diproses
        return view('warehouses.index', compact('warehouses'));
    }

    /**
     * Menyimpan data warehouse baru ke dalam database.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        // Validasi inputan dari form
        $request->validate([
            'name' => 'required|string|max:255', // Nama warehouse tidak boleh kosong dan memiliki panjang maksimal 255 karakter
            'location' => 'required|string', // Lokasi warehouse tidak boleh kosong
            'capacity' => 'required|integer|min:1', // Kapasitas harus berupa angka positif
            'description' => 'nullable|string', // Deskripsi opsional
        ]);

        // Membuat warehouse baru dengan data yang sudah tervalidasi
        Warehouse::create($request->all());

        // Mengarahkan kembali ke halaman daftar warehouse dengan pesan sukses
        return redirect()->route('warehouses.index')->with('success', 'Warehouse created successfully.');
    }

    /**
     * Menampilkan detail warehouse tertentu beserta item-unit yang ada di dalamnya.
     *
     * @param  Warehouse  $warehouse
     */
    public function show(Warehouse $warehouse)
    {
        // Memuat relasi itemUnits dan item untuk menampilkan data yang lengkap
        $warehouse->with(['itemUnits.item']);

        // Menampilkan halaman detail warehouse
        return view('warehouses.show', compact('warehouse'));
    }

    /**
     * Memperbarui data warehouse yang ada dalam database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Warehouse  $warehouse
     */
    public function update(Request $request, Warehouse $warehouse)
    {
        // Validasi inputan dari form
        $request->validate([
            'name' => 'required|string|max:255', // Nama warehouse tidak boleh kosong dan memiliki panjang maksimal 255 karakter
            'location' => 'required|string', // Lokasi warehouse tidak boleh kosong
            'capacity' => 'required|integer|min:1', // Kapasitas harus berupa angka positif
            'description' => 'nullable|string', // Deskripsi opsional
        ]);

        // Memperbarui data warehouse dengan data yang sudah tervalidasi
        $warehouse->update($request->all());

        // Mengarahkan kembali ke halaman daftar warehouse dengan pesan sukses
        return redirect()->route('warehouses.index')->with('success', 'Warehouse updated successfully.');
    }

    /**
     * Menghapus warehouse dari database.
     *
     * @param  Warehouse  $warehouse
     */
    public function destroy(Warehouse $warehouse)
    {
        // Menghapus warehouse dari database
        $warehouse->delete();

        // Mengarahkan kembali ke halaman daftar warehouse dengan pesan sukses
        return redirect()->route('warehouses.index')->with('success', 'Warehouse deleted successfully.');
    }
}
