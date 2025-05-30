@extends('layouts.app')

@section('title', 'SISFO Sarpras - Manajemen Pengembalian')

@section('heading')
    <a href="{{ route('return-requests.index') }}">
        <i class="fas fa-redo-alt ml-2 mr-1 text-indigo-300"></i>
        Pengembalian
    </a>
@endsection

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Manajemen Pengembalian</h1>
            <div class="flex space-x-3">
                <a href="{{ route('return-requests.exportExcel') }}"
                    class="bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-medium py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Ekspor Excel
                </a>
                <a href="{{ route('return-requests.exportPdf') }}"
                    class="bg-gradient-to-r from-rose-600 to-pink-600 hover:from-rose-700 hover:to-pink-700 text-white font-medium py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Ekspor PDF
                </a>
            </div>
        </div>

        <!-- Filter Controls -->
        <div class="mb-6 bg-white p-4 rounded-lg shadow border border-gray-100">
            <div class="flex items-center justify-center gap-4">
                <!-- Search Input -->
                <div class="relative w-full">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input id="searchQuery" type="text"
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Cari pemohon...">
                </div>

                <!-- Status Filter -->
                <div class="w-2/3">
                    <select id="selectedStatus"
                        class="block w-full pl-3 pr-10 py-2 border border-gray-300 rounded-lg bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Status</option>
                        <option value="pending">Menunggu</option>
                        <option value="approved">Disetujui</option>
                        <option value="rejected">Ditolak</option>
                    </select>
                </div>

                <!-- Date Range Filter -->
                <div class="w-2/3">
                    <input type="date" id="startDate"
                        class="block w-full pl-3 pr-10 py-2 border border-gray-300 rounded-lg bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Dari Tanggal">
                </div>
                <div class="w-2/3">
                    <input type="date" id="endDate"
                        class="block w-full pl-3 pr-10 py-2 border border-gray-300 rounded-lg bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Sampai Tanggal">
                </div>
            </div>
        </div>

        <!-- Loading Indicator -->
        <div id="loadingIndicator" class="hidden text-center py-8">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-indigo-600"></div>
            <p class="mt-2 text-gray-600">Memuat data...</p>
        </div>

        <!-- Returns Container (akan diisi oleh AJAX) -->
        <div id="returnsContainer">
            @include('return_requests.partials._returns_table', ['returns' => $returns])
        </div>
    </div>

    <!-- JavaScript untuk AJAX -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elemen filter
            const searchQuery = document.getElementById('searchQuery');
            const selectedStatus = document.getElementById('selectedStatus');
            const startDate = document.getElementById('startDate');
            const endDate = document.getElementById('endDate');

            // Container untuk hasil
            const returnsContainer = document.getElementById('returnsContainer');
            const loadingIndicator = document.getElementById('loadingIndicator');

            // Debounce untuk pencarian
            let debounceTimer;

            // Fungsi untuk memuat data
            function loadReturns() {
                // Tampilkan loading indicator
                loadingIndicator.classList.remove('hidden');
                returnsContainer.classList.add('opacity-50');

                // Siapkan parameter
                const params = {
                    search: searchQuery.value,
                    status: selectedStatus.value,
                    start_date: startDate.value,
                    end_date: endDate.value
                };

                // Buat URL dengan parameter
                const url = new URL('{{ route('return-requests.index') }}');
                Object.keys(params).forEach(key => {
                    if (params[key]) {
                        url.searchParams.append(key, params[key]);
                    }
                });

                // Buat request AJAX
                fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'text/html'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        returnsContainer.innerHTML = html;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        returnsContainer.innerHTML =
                            '<div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100 p-12 text-center text-red-500">Terjadi kesalahan saat memuat data.</div>';
                    })
                    .finally(() => {
                        loadingIndicator.classList.add('hidden');
                        returnsContainer.classList.remove('opacity-50');
                    });
            }

            // Event listeners untuk semua filter
            [selectedStatus, startDate, endDate].forEach(element => {
                element.addEventListener('change', loadReturns);
            });

            // Event listener khusus untuk input pencarian dengan debounce
            searchQuery.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(loadReturns, 500);
            });

            // Event delegation untuk tombol approve/reject
            returnsContainer.addEventListener('submit', function(e) {
                if (e.target.closest('form')) {
                    e.preventDefault();

                    const form = e.target.closest('form');
                    const url = form.getAttribute('action');
                    const method = form.querySelector('input[name="_method"]') ?
                        form.querySelector('input[name="_method"]').value : 'POST';

                    if (form.classList.contains('approve-form') && !confirm(
                            'Apakah Anda yakin ingin menyetujui pengembalian ini?')) {
                        return;
                    }

                    if (form.classList.contains('reject-form') && !confirm(
                            'Apakah Anda yakin ingin menolak pengembalian ini?')) {
                        return;
                    }

                    fetch(url, {
                            method: method,
                            headers: {
                                'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            },
                            body: new FormData(form)
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                loadReturns(); // Muat ulang data setelah aksi
                            } else {
                                alert('Gagal memproses pengembalian: ' + (data.message ||
                                    'Terjadi kesalahan'));
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan saat memproses pengembalian');
                        });
                }
            });
        });
    </script>
@endsection
