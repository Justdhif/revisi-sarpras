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

    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Stock Movements</h1>
            <div class="flex space-x-3">
                <button onclick="openModal('create-modal')"
                    class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-medium py-2 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                            clip-rule="evenodd" />
                    </svg>
                    Tambah Movement
                </button>
                {{-- <div class="flex space-x-2">
                    <a href="{{ route('stock_movements.exportExcel') }}"
                        class="bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-medium py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Ekspor Excel
                    </a>
                    <a href="{{ route('stock_movements.exportPdf') }}"
                        class="bg-gradient-to-r from-rose-600 to-pink-600 hover:from-rose-700 hover:to-pink-700 text-white font-medium py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Ekspor PDF
                    </a>
                </div> --}}
            </div>
        </div>

        <!-- Filter Controls -->
        <div class="mb-6 bg-white p-4 rounded-lg shadow border border-gray-100">
            <form action="{{ route('stock_movements.index') }}" method="GET">
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
                        <input name="search" value="{{ request('search') }}" type="text"
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Search items...">
                    </div>

                    <!-- Movement Type Filter -->
                    <div class="w-2/3">
                        <select name="movement_type"
                            class="block w-full pl-3 pr-10 py-2 border border-gray-300 rounded-lg bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Semua tipe</option>
                            <option value="in" {{ request('movement_type') === 'in' ? 'selected' : '' }}>Stock In
                            </option>
                            <option value="out" {{ request('movement_type') === 'out' ? 'selected' : '' }}>Stock Out
                            </option>
                            <option value="damaged" {{ request('movement_type') === 'damaged' ? 'selected' : '' }}>Damaged
                            </option>
                        </select>
                    </div>

                    <!-- Date Filters -->
                    <div class="w-2/3">
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                            class="block w-full pl-3 pr-10 py-2 border border-gray-300 rounded-lg bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Start Date">
                    </div>

                    <div class="w-2/3">
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                            class="block w-full pl-3 pr-10 py-2 border border-gray-300 rounded-lg bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="End Date">
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z"
                                clip-rule="evenodd" />
                        </svg>
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Movements Container -->
        <div x-data="movementTable">
            <!-- Loading State (shown only during initial load or search) -->
            <div x-show="isLoading" class="mb-6">
                @include('stock_movements.partials._loading_table')
            </div>

            <!-- Content (shown when not loading) -->
            <div x-show="!isLoading" x-transition>
                @if ($movements->isEmpty())
                    <div class="flex flex-col items-center justify-center min-h-[70vh] py-12 text-center">
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
                            <a href="{{ route('stock_movements.index') }}"
                                class="inline-flex items-center px-4 py-3 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Clear Filters
                            </a>
                        </div>
                    </div>
                @else
                    @include('stock_movements.partials._movements_table', [
                        'movements' => $movements,
                        'sortField' => $sortField,
                        'sortDirection' => $sortDirection,
                    ])
                @endif
            </div>
        </div>
    </div>

    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
            document.getElementById('modal-movement-date').valueAsDate = new Date();
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        document.addEventListener('alpine:init', () => {
            Alpine.data('movementTable', () => ({
                isLoading: true,

                init() {
                    setTimeout(() => {
                        this.isLoading = false;
                    }, 300);

                    this.setupSearch();
                },

                setupSearch() {
                    const searchInput = document.querySelector('input[name="search"]');
                    let timer;

                    searchInput.addEventListener('input', () => {
                        clearTimeout(timer);
                        this.isLoading = true;

                        timer = setTimeout(() => {
                            this.submitSearchForm(searchInput.value);
                        }, 500);
                    });
                },

                submitSearchForm(searchTerm) {
                    const form = document.createElement('form');
                    form.method = 'GET';
                    form.action = '{{ route('stock_movements.index') }}';

                    const urlParams = new URLSearchParams(window.location.search);
                    urlParams.set('search', searchTerm);

                    // Add movement_type filter
                    const movementTypeSelect = document.querySelector('select[name="movement_type"]');
                    if (movementTypeSelect.value) {
                        urlParams.set('movement_type', movementTypeSelect.value);
                    } else {
                        urlParams.delete('movement_type');
                    }

                    // Add date filters
                    const startDateInput = document.querySelector('input[name="start_date"]');
                    if (startDateInput.value) {
                        urlParams.set('start_date', startDateInput.value);
                    } else {
                        urlParams.delete('start_date');
                    }

                    const endDateInput = document.querySelector('input[name="end_date"]');
                    if (endDateInput.value) {
                        urlParams.set('end_date', endDateInput.value);
                    } else {
                        urlParams.delete('end_date');
                    }

                    // Add sort parameters if they exist
                    if (urlParams.has('sort')) {
                        urlParams.set('sort', urlParams.get('sort'));
                    }
                    if (urlParams.has('direction')) {
                        urlParams.set('direction', urlParams.get('direction'));
                    }

                    form.innerHTML = '';
                    for (const [key, value] of urlParams.entries()) {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = key;
                        input.value = value;
                        form.appendChild(input);
                    }

                    document.body.appendChild(form);
                    form.submit();
                }
            }));
        });
    </script>
@endsection
