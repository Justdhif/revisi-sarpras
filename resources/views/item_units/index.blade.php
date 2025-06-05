@extends('layouts.app')

@section('title', 'SISFO Sarpras - Daftar Unit Barang')

@section('heading')
    <a href="{{ route('item-units.index') }}">
        <i class="fas fa-box ml-2 mr-1 text-indigo-300"></i>
        Unit Barang
    </a>
@endsection

@section('content')
    <div class="container mx-auto px-4 py-8" x-data="unitFilter()" x-init="init()">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Daftar Unit Barang</h1>
            <div class="flex space-x-3">
                <a href="{{ route('item-units.create') }}"
                    class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-medium py-2 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                            clip-rule="evenodd" />
                    </svg>
                    Tambah Unit
                </a>
                <div class="flex space-x-2">
                    <a href="{{ route('item-units.exportExcel') }}"
                        class="bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-medium py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Ekspor Excel
                    </a>
                    <a href="{{ route('item-units.exportPdf') }}"
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
                    <input x-model="searchQuery" @input.debounce.500ms="filterUnits()" type="text"
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Cari unit...">
                </div>

                <!-- Condition Filter -->
                <div class="w-2/3">
                    <select x-model="selectedCondition" @change="filterUnits()"
                        class="block w-full pl-3 pr-10 py-2 border border-gray-300 rounded-lg bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Kondisi</option>
                        <option value="new">Baru</option>
                        <option value="used">Bekas</option>
                        <option value="refurbished">Rekondisi</option>
                        <option value="damaged">Rusak</option>
                    </select>
                </div>

                <!-- Status Filter -->
                <div class="w-2/3">
                    <select x-model="selectedStatus" @change="filterUnits()"
                        class="block w-full pl-3 pr-10 py-2 border border-gray-300 rounded-lg bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Status</option>
                        <option value="available">Tersedia</option>
                        <option value="reserved">Dipesan</option>
                        <option value="out_of_stock">Habis</option>
                    </select>
                </div>

                <!-- Warehouse Filter -->
                <div class="w-2/3">
                    <select x-model="selectedWarehouse" @change="filterUnits()"
                        class="block w-full pl-3 pr-10 py-2 border border-gray-300 rounded-lg bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Gudang</option>
                        @foreach ($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                        @endforeach
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
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Barang</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kondisi</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode
                                QR</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kuantitas</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template x-for="i in 6" :key="i">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="h-4 bg-gray-200 rounded w-3/4 animate-pulse"></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="h-4 bg-gray-200 rounded w-1/2 animate-pulse"></div>
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

        <!-- Units Container -->
        <div x-show="!isLoading">
            <div x-show="units.length === 0"
                class="flex flex-col items-center justify-center min-h-[70vh] py-12 text-center">
                <div class="max-w-md mx-auto px-4">
                    <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-indigo-50 mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-indigo-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Tidak ada unit barang</h2>
                    <p class="text-gray-500 mb-8">Mulai dengan menambahkan unit barang pertama Anda untuk mengelola
                        inventori.</p>
                    <a href="{{ route('item-units.create') }}"
                        class="inline-flex items-center px-4 py-3 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                clip-rule="evenodd" />
                        </svg>
                        Tambah Unit
                    </a>
                </div>
            </div>

            <!-- Include the units table partial -->
            @include('item_units.partials._units_table')
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('unitFilter', () => ({
                searchQuery: '',
                selectedCondition: '',
                selectedStatus: '',
                selectedWarehouse: '',
                isLoading: false,
                units: [],
                pagination: {
                    current_page: 1,
                    last_page: 1,
                    from: 0,
                    to: 0,
                    total: 0,
                    links: []
                },

                init() {
                    this.filterUnits();
                },

                filterUnits() {
                    this.isLoading = true;

                    const params = {
                        search: this.searchQuery,
                        condition: this.selectedCondition,
                        status: this.selectedStatus,
                        warehouse: this.selectedWarehouse,
                        page: this.pagination.current_page
                    };

                    // Remove empty params
                    Object.keys(params).forEach(key => {
                        if (!params[key]) delete params[key];
                    });

                    const queryString = new URLSearchParams(params).toString();

                    fetch(`{{ route('item-units.index') }}?${queryString}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            this.units = data.data;
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
                        this.filterUnits();
                    }
                },

                nextPage() {
                    if (this.pagination.current_page < this.pagination.last_page) {
                        this.pagination.current_page++;
                        this.filterUnits();
                    }
                },

                goToPage(url) {
                    if (!url) return;

                    const page = new URL(url).searchParams.get('page');
                    if (page) {
                        this.pagination.current_page = parseInt(page);
                        this.filterUnits();
                    }
                },

                async deleteUnit(unitId) {
                    if (!confirm('Apakah Anda yakin ingin menghapus unit barang ini?')) {
                        return;
                    }

                    try {
                        const response = await fetch(`{{ route('item-units.index') }}/${unitId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        });

                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }

                        const data = await response.json();
                        if (data.success) {
                            this.filterUnits(); // Refresh the list
                        } else {
                            alert('Gagal menghapus unit barang: ' + (data.message ||
                                'Terjadi kesalahan'));
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat menghapus unit barang');
                    }
                }
            }));
        });
    </script>
@endsection
