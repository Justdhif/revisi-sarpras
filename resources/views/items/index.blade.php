@extends('layouts.app')

@section('title', 'SISFO Sarpras - Inventori Barang')

@section('heading')
    <a href="{{ route('items.index') }}">
        <i class="fas fa-box ml-2 mr-1 text-indigo-300"></i>
        Barang
    </a>
@endsection

@section('content')
    <div class="container mx-auto px-4 py-8" x-data="itemFilter()" x-init="init()">
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
                    <input x-model="searchQuery" @input.debounce.500ms="filterItems()" type="text"
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Cari barang...">
                </div>

                <!-- Type Filter -->
                <div class="w-2/3">
                    <select x-model="selectedType" @change="filterItems()"
                        class="block w-full pl-3 pr-10 py-2 border border-gray-300 rounded-lg bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Tipe</option>
                        <option value="consumable">Consumable</option>
                        <option value="non-consumable">Non-Consumable</option>
                    </select>
                </div>

                <!-- Category Filter -->
                <div class="w-2/3">
                    <select x-model="selectedCategory" @change="filterItems()"
                        class="block w-full pl-3 pr-10 py-2 border border-gray-300 rounded-lg bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Sort Options -->
                <div class="w-2/3">
                    <select x-model="sortOption" @change="filterItems()"
                        class="block w-full pl-3 pr-10 py-2 border border-gray-300 rounded-lg bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="name_asc">Nama A-Z</option>
                        <option value="name_desc">Nama Z-A</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div x-show="isLoading" class="mb-6">
            <div class="bg-white rounded-lg shadow overflow-hidden border border-gray-200">
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
                                Gambar</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Deskripsi</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <!-- Skeleton rows -->
                        <template x-for="i in 6" :key="i">
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
                        </template>
                    </tbody>
                </table>
                <div class="flex justify-between items-center px-4 py-3 bg-white border-t border-gray-200 rounded-b-lg">
                    <div class="h-4 bg-gray-200 rounded w-1/4 animate-pulse"></div>
                    <div class="flex space-x-2">
                        <div class="h-8 bg-gray-200 rounded w-8 animate-pulse"></div>
                        <div class="h-8 bg-gray-200 rounded w-8 animate-pulse"></div>
                        <div class="h-8 bg-gray-200 rounded w-8 animate-pulse"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Items Container -->
        <div x-show="!isLoading">
            <div x-show="items.length === 0"
                class="flex flex-col items-center justify-center min-h-[70vh] py-12 text-center">
                <div class="max-w-md mx-auto px-4">
                    <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-indigo-50 mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-indigo-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Tidak ada barang</h2>
                    <p class="text-gray-500 mb-8">Mulai dengan menambahkan barang pertama Anda untuk mengelola inventori.
                    </p>
                    <a href="{{ route('items.create') }}"
                        class="inline-flex items-center px-4 py-3 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                clip-rule="evenodd" />
                        </svg>
                        Tambah Barang
                    </a>
                </div>
            </div>

            <!-- Include the items table partial -->
            @include('items.partials._items_table')
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('itemFilter', () => ({
                searchQuery: '',
                selectedType: '',
                selectedCategory: '',
                sortOption: 'name_asc',
                isLoading: false,
                items: [],
                pagination: {
                    current_page: 1,
                    last_page: 1,
                    from: 0,
                    to: 0,
                    total: 0,
                    links: []
                },

                init() {
                    // Load initial data
                    this.filterItems();
                },

                filterItems() {
                    this.isLoading = true;

                    const params = {
                        search: this.searchQuery,
                        type: this.selectedType,
                        category: this.selectedCategory,
                        sort: this.sortOption,
                        page: this.pagination.current_page
                    };

                    // Remove empty params
                    Object.keys(params).forEach(key => {
                        if (!params[key]) delete params[key];
                    });

                    const queryString = new URLSearchParams(params).toString();

                    fetch(`{{ route('items.index') }}?${queryString}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            this.items = data.data;
                            this.pagination = {
                                current_page: data.current_page,
                                last_page: data.last_page,
                                from: data.from,
                                to: data.to,
                                total: data.total,
                                links: data.links
                            };
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        })
                        .finally(() => {
                            this.isLoading = false;
                        });
                },

                previousPage() {
                    if (this.pagination.current_page > 1) {
                        this.pagination.current_page--;
                        this.filterItems();
                    }
                },

                nextPage() {
                    if (this.pagination.current_page < this.pagination.last_page) {
                        this.pagination.current_page++;
                        this.filterItems();
                    }
                },

                goToPage(url) {
                    if (!url) return;

                    const page = new URL(url).searchParams.get('page');
                    if (page) {
                        this.pagination.current_page = parseInt(page);
                        this.filterItems();
                    }
                }
            }));
        });
    </script>
@endsection
