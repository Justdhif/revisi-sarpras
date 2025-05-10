@extends('layouts.app')

@section('title', 'SISFO Sarpras - Detail Gudang')

@section('heading', 'Detail Gudang')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Warehouse Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $warehouse->name }}</h1>
                <div class="flex items-center mt-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="ml-2 text-gray-600">{{ $warehouse->location ?? 'Lokasi belum ditentukan' }}</span>
                </div>
            </div>
            <a href="{{ route('warehouses.index') }}"
                class="mt-4 md:mt-0 inline-flex items-center px-4 py-2.5 border border-gray-200 rounded-full text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                        clip-rule="evenodd" />
                </svg>
                Kembali ke Daftar
            </a>
        </div>

        <!-- Warehouse Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Capacity -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-medium text-gray-500">Total Kapasitas</h3>
                    <div class="p-2 rounded-lg bg-blue-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                </div>
                <p class="mt-3 text-3xl font-semibold text-gray-900">{{ $warehouse->capacity }}</p>
                <div class="mt-6">
                    <div class="flex justify-between text-sm text-gray-500 mb-1">
                        <span>0%</span>
                        <span>100%</span>
                    </div>
                    <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-blue-400 to-blue-600 rounded-full" style="width: 100%">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Used Capacity -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-medium text-gray-500">Kapasitas Terpakai</h3>
                    <div class="p-2 rounded-lg bg-indigo-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                </div>
                <p class="mt-3 text-3xl font-semibold text-gray-900">
                    {{ $warehouse->used_capacity }}
                    <span class="text-lg ml-2 text-indigo-600">
                        ({{ round(($warehouse->used_capacity / $warehouse->capacity) * 100, 1) }}%)
                    </span>
                </p>
                <div class="mt-6">
                    <div class="flex justify-between text-sm text-gray-500 mb-1">
                        <span>0%</span>
                        <span>100%</span>
                    </div>
                    <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-indigo-400 to-indigo-600 rounded-full"
                            style="width: {{ ($warehouse->used_capacity / $warehouse->capacity) * 100 }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Remaining Capacity -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-medium text-gray-500">Sisa Kapasitas</h3>
                    <div class="p-2 rounded-lg bg-green-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                </div>
                <p class="mt-3 text-3xl font-semibold text-gray-900">
                    {{ $warehouse->capacity - $warehouse->used_capacity }}
                    <span class="text-lg ml-2 text-green-600">
                        ({{ round((($warehouse->capacity - $warehouse->used_capacity) / $warehouse->capacity) * 100, 1) }}%)
                    </span>
                </p>
                <div class="mt-6">
                    <div class="flex justify-between text-sm text-gray-500 mb-1">
                        <span>0%</span>
                        <span>100%</span>
                    </div>
                    <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-green-400 to-green-600 rounded-full"
                            style="width: {{ (($warehouse->capacity - $warehouse->used_capacity) / $warehouse->capacity) * 100 }}%">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Items Section -->
        <div class="mb-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-900">Item di Gudang</h2>
                <span class="px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                    {{ $warehouse->itemUnits->count() }} items
                </span>
            </div>

            @if ($warehouse->itemUnits->count())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach ($warehouse->itemUnits as $unit)
                        <div
                            class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
                            <!-- Item Image -->
                            <div class="h-48 bg-gray-100 relative overflow-hidden">
                                <img class="w-full h-full object-cover" src="{{ asset($unit->item->image_url) }}"
                                    alt="{{ $unit->item->name }}">
                                <div class="absolute top-3 right-3">
                                    <span
                                        class="px-2 py-1 text-xs font-medium rounded-full
                                        {{ $unit->condition === 'baik'
                                            ? 'bg-green-100 text-green-800'
                                            : ($unit->condition === 'rusak'
                                                ? 'bg-red-100 text-red-800'
                                                : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ $unit->condition }}
                                    </span>
                                </div>
                            </div>

                            <!-- Item Content -->
                            <div class="p-5">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="font-bold text-gray-900">{{ $unit->item->name }}</h3>
                                        <p class="text-sm text-gray-500 mt-1">{{ $unit->sku }}</p>
                                    </div>
                                    <span
                                        class="px-2 py-1 text-xs font-medium rounded-full
                                        {{ $unit->status === 'tersedia' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                        {{ ucfirst($unit->status) }}
                                    </span>
                                </div>

                                <div class="mt-4 flex items-center justify-between">
                                    <span class="text-lg font-semibold text-gray-900">{{ $unit->quantity }} unit</span>
                                    <div class="p-2 bg-gray-50 rounded-lg">
                                        @if ($unit->qr_image_url)
                                            {!! QrCode::size(40)->generate($unit->sku) !!}
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                            </svg>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Gudang kosong</h3>
                    <p class="mt-2 text-gray-500">Tidak ada item yang tersimpan di gudang ini saat ini.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
