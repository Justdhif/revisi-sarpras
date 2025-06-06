@extends('layouts.app')

@section('title', 'SISFO Sarpras - Stock Movements')

@section('heading')
    <a href="{{ route('stock_movements.index') }}">
        <i class="fas fa-exchange-alt ml-2 mr-1 text-indigo-300"></i>
        Stock Movements
    </a>
@endsection

@section('content')
    @include('stock_movements.partials._create-modal')
    {{-- @include('stock_movements._edit-modal') --}}

    <div class="container mx-auto px-4 py-8" x-data="movementFilter()" x-init="init()">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Stock Movements</h1>

            <button onclick="openModal('create-modal')"
                class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-medium py-2 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                        clip-rule="evenodd" />
                </svg>
                Tambah Movement
            </button>
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
                    <input x-model="searchQuery" @input.debounce.500ms="filterMovements()" type="text"
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Search items...">
                </div>

                <!-- Type Filter -->
                <div class="w-2/3">
                    <select x-model="selectedType" @change="filterMovements()"
                        class="block w-full pl-3 pr-10 py-2 border border-gray-300 rounded-lg bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All Types</option>
                        <option value="in">Stock In</option>
                        <option value="out">Stock Out</option>
                        <option value="damaged">Damaged</option>
                    </select>
                </div>

                <!-- Date Filters -->
                <div class="w-2/3">
                    <input type="date" x-model="startDate" @change="filterMovements()"
                        class="block w-full pl-3 pr-10 py-2 border border-gray-300 rounded-lg bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Start Date">
                </div>

                <div class="w-2/3">
                    <input type="date" x-model="endDate" @change="filterMovements()"
                        class="block w-full pl-3 pr-10 py-2 border border-gray-300 rounded-lg bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="End Date">
                </div>

                <!-- Sort Options -->
                <div class="w-2/3">
                    <select x-model="sortOption" @change="filterMovements()"
                        class="block w-full pl-3 pr-10 py-2 border border-gray-300 rounded-lg bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="item_asc">Nama Item A-Z</option>
                        <option value="item_desc">Nama Item Z-A</option>
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
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Quantity</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Description</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template x-for="i in 6" :key="i">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="h-4 bg-gray-200 rounded w-3/4 animate-pulse"></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="h-4 bg-gray-200 rounded w-3/4 animate-pulse"></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="h-4 bg-gray-200 rounded w-1/2 animate-pulse"></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="h-4 bg-gray-200 rounded w-1/3 animate-pulse"></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="h-4 bg-gray-200 rounded w-1/4 animate-pulse"></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="h-4 bg-gray-200 rounded w-1/4 animate-pulse"></div>
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

        <!-- Movements Container -->
        <div x-show="!isLoading">
            <div x-show="movements.length === 0"
                class="flex flex-col items-center justify-center min-h-[70vh] py-12 text-center">
                <div class="max-w-md mx-auto px-4">
                    <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-indigo-50 mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-indigo-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">No movements found</h2>
                    <p class="text-gray-500 mb-8">No stock movement records available for the selected filters.</p>
                </div>
            </div>

            <!-- Include the movements table partial -->
            @include('stock_movements.partials._movements_table')
        </div>
    </div>

    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
            // Set today's date as default
            document.getElementById('modal-movement-date').valueAsDate = new Date();
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        document.addEventListener('alpine:init', () => {
            Alpine.data('movementFilter', () => ({
                searchQuery: '',
                selectedType: '',
                startDate: '',
                endDate: '',
                isLoading: false,
                movements: [],
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
                    this.filterMovements();
                },

                filterMovements() {
                    this.isLoading = true;

                    const params = {
                        search: this.searchQuery,
                        type: this.selectedType,
                        start_date: this.startDate,
                        end_date: this.endDate,
                        page: this.pagination.current_page
                    };

                    // Remove empty params
                    Object.keys(params).forEach(key => {
                        if (!params[key]) delete params[key];
                    });

                    const queryString = new URLSearchParams(params).toString();

                    fetch(`{{ route('stock_movements.index') }}?${queryString}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            this.movements = data.data;
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
                        this.filterMovements();
                    }
                },

                nextPage() {
                    if (this.pagination.current_page < this.pagination.last_page) {
                        this.pagination.current_page++;
                        this.filterMovements();
                    }
                },

                goToPage(url) {
                    if (!url) return;

                    const page = new URL(url).searchParams.get('page');
                    if (page) {
                        this.pagination.current_page = parseInt(page);
                        this.filterMovements();
                    }
                }
            }));
        });
    </script>
@endsection
