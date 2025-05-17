@extends('layouts.app')

@section('title', 'SISFO Sarpras - Buat Permohonan Peminjaman')

@section('content')
    <div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Buat Permohonan Peminjaman
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Isi formulir berikut untuk mengajukan permohonan peminjaman barang
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-2xl">
            <div class="bg-white py-8 px-6 shadow-lg rounded-lg sm:px-10 border border-gray-100">
                <form class="space-y-6" action="{{ route('borrow-requests.store') }}" method="POST">
                    @csrf

                    <div>
                        <label for="borrow_date_expected" class="block text-sm font-medium text-gray-700">Tanggal
                            Peminjaman</label>
                        <input type="date" name="borrow_date_expected" id="borrow_date_expected" required
                            class="block w-full border-gray-300 rounded-md shadow-sm py-2 px-3 mt-1"
                            min="{{ date('Y-m-d') }}">
                    </div>

                    <div>
                        <label for="return_date_expected" class="block text-sm font-medium text-gray-700">Tanggal
                            Pengembalian</label>
                        <input type="date" name="return_date_expected" id="return_date_expected" required
                            class="block w-full border-gray-300 rounded-md shadow-sm py-2 px-3 mt-1"
                            min="{{ date('Y-m-d') }}">
                    </div>

                    <div>
                        <label for="reason" class="block text-sm font-medium text-gray-700">Alasan Peminjaman</label>
                        <textarea id="reason" name="reason" rows="3" required
                            class="block w-full border-gray-300 rounded-md shadow-sm py-2 px-3 resize-none mt-1"
                            placeholder="Jelaskan alasan peminjaman"></textarea>
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700">Catatan Tambahan</label>
                        <textarea id="notes" name="notes" rows="3"
                            class="block w-full border-gray-300 rounded-md shadow-sm py-2 px-3 resize-none mt-1"
                            placeholder="Catatan tambahan (opsional)"></textarea>
                    </div>

                    <hr class="my-6">

                    <h3 class="text-lg font-semibold mb-2">Barang yang Dipinjam</h3>

                    <div id="item-units-container">
                        <div class="item-unit-row flex space-x-4 mb-4">
                            <div class="w-3/5">
                                <label class="block text-sm font-medium text-gray-700">Unit Barang</label>
                                <select name="item_unit_ids[]" required
                                    class="block w-full border-gray-300 rounded-md shadow-sm py-2 px-3 mt-1">
                                    <option value="">-- Pilih Unit Barang --</option>
                                    @foreach ($itemUnits as $unit)
                                        <option value="{{ $unit->id }}">
                                            {{ $unit->item->name }} (SKU: {{ $unit->sku }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-1/5">
                                <label class="block text-sm font-medium text-gray-700">Jumlah</label>
                                <input type="number" name="quantities[]" min="1" value="1" required
                                    class="block w-full border-gray-300 rounded-md shadow-sm py-2 px-3 mt-1">
                            </div>
                            <div class="w-1/5 flex items-end">
                                <button type="button" onclick="removeItemRow(this)"
                                    class="bg-red-500 text-white px-3 py-2 rounded hover:bg-red-600 text-sm mt-6">Hapus</button>
                            </div>
                        </div>
                    </div>

                    <button type="button" onclick="addItemRow()"
                        class="mb-4 bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 text-sm">
                        + Tambah Barang
                    </button>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 text-sm">
                            Ajukan Permohonan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function addItemRow() {
            const container = document.getElementById('item-units-container');
            const newRow = container.firstElementChild.cloneNode(true);

            newRow.querySelector('select').selectedIndex = 0;
            newRow.querySelector('input').value = 1;

            container.appendChild(newRow);
        }

        function removeItemRow(button) {
            const container = document.getElementById('item-units-container');
            if (container.children.length > 1) {
                button.closest('.item-unit-row').remove();
            }
        }
    </script>
@endsection
