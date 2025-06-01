@extends('layouts.app')

@section('title', 'SISFO Sarpras - Inventori Barang')

@section('heading')
    <a href="{{ route('items.index') }}">
        <i class="fas fa-box ml-2 mr-1 text-indigo-300"></i>
        Barang
    </a>
@endsection

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Daftar Barang</h1>
            <div class="flex space-x-3">
                <a href="{{ route('items.create') }}"
                    class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-medium py-2 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                            clip-rule="evenodd" />
                    </svg>
                    Tambah Barang
                </a>
                <a href="{{ route('items.export.excel') }}"
                    class="bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-medium py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Ekspor Excel
                </a>
                <a href="{{ route('items.export.pdf') }}"
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
                        placeholder="Cari barang...">
                </div>

                <!-- Type Filter -->
                <div class="w-2/3">
                    <select id="selectedType"
                        class="block w-full pl-3 pr-10 py-2 border border-gray-300 rounded-lg bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Tipe</option>
                        <option value="consumable">Consumable</option>
                        <option value="non-consumable">Non-Consumable</option>
                    </select>
                </div>

                <!-- Category Filter -->
                <div class="w-2/3">
                    <select id="selectedCategory"
                        class="block w-full pl-3 pr-10 py-2 border border-gray-300 rounded-lg bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Sort Options -->
                <div class="w-2/3">
                    <select id="sortOption"
                        class="block w-full pl-3 pr-10 py-2 border border-gray-300 rounded-lg bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="name_asc">Nama A-Z</option>
                        <option value="name_desc">Nama Z-A</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Skeleton Loader (hidden by default) -->
        <div id="skeletonLoader" class="hidden">
            <div class="bg-white rounded-lg shadow overflow-hidden border border-gray-200 mb-4">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama
                                Barang</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jenis</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kategori</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Gambar
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Deskripsi
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <!-- Skeleton rows -->
                        @for ($i = 0; $i < 6; $i++)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="h-4 bg-gray-200 rounded w-3/4 animate-pulse"></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="h-4 bg-gray-200 rounded w-1/2 animate-pulse"></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="h-4 bg-gray-200 rounded w-1/3 animate-pulse"></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="h-4 bg-gray-200 rounded w-1/4 animate-pulse"></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="h-4 bg-gray-200 rounded w-1/4 animate-pulse"></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex space-x-2">
                                        <div class="h-7 bg-gray-200 rounded w-10 animate-pulse"></div>
                                        <div class="h-7 bg-gray-200 rounded w-10 animate-pulse"></div>
                                        <div class="h-7 bg-gray-200 rounded w-10 animate-pulse"></div>
                                    </div>
                                </td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
            <div class="flex justify-between items-center px-4 py-3 bg-white border-t border-gray-200 rounded-b-lg">
                <div class="h-4 bg-gray-200 rounded w-1/4 animate-pulse"></div>
                <div class="flex space-x-2">
                    <div class="h-8 bg-gray-200 rounded w-8 animate-pulse"></div>
                    <div class="h-8 bg-gray-200 rounded w-8 animate-pulse"></div>
                    <div class="h-8 bg-gray-200 rounded w-8 animate-pulse"></div>
                </div>
            </div>
        </div>

        <!-- Items Container (akan diisi oleh AJAX) -->
        <div id="itemsContainer">
            @include('items.partials._items_table', ['items' => $items])
        </div>
    </div>

    <!-- JavaScript untuk AJAX -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elemen filter
            const searchQuery = document.getElementById('searchQuery');
            const selectedType = document.getElementById('selectedType');
            const selectedCategory = document.getElementById('selectedCategory');
            const sortOption = document.getElementById('sortOption');

            // Container untuk hasil
            const itemsContainer = document.getElementById('itemsContainer');
            const skeletonLoader = document.getElementById('skeletonLoader');

            // Debounce untuk pencarian
            let debounceTimer;

            // Fungsi untuk memuat data
            function loadItems() {
                // Tampilkan skeleton loader
                skeletonLoader.classList.remove('hidden');
                itemsContainer.classList.add('opacity-50');

                // Siapkan parameter
                const params = {
                    search: searchQuery.value,
                    type: selectedType.value,
                    category: selectedCategory.value,
                    sort: sortOption.value
                };

                // Buat URL dengan parameter
                const url = new URL('{{ route('items.index') }}');
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
                        itemsContainer.innerHTML = html;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        itemsContainer.innerHTML =
                            '<div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100 p-12 text-center text-red-500">Terjadi kesalahan saat memuat data.</div>';
                    })
                    .finally(() => {
                        skeletonLoader.classList.add('hidden');
                        itemsContainer.classList.remove('opacity-50');
                    });
            }

            // Event listeners untuk semua filter
            [searchQuery, selectedType, selectedCategory, sortOption].forEach(element => {
                element.addEventListener('change', loadItems);
            });

            // Event listener khusus untuk input pencarian dengan debounce
            searchQuery.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(loadItems, 500);
            });
        });
    </script>
@endsection
