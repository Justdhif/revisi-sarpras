@extends('layouts.app')

@section('title', 'SISFO Sarpras - Detail Gudang')

@section('heading')
    <a href="{{ route('warehouses.index') }}">
        <i class="fas fa-warehouse ml-2 mr-1 text-indigo-300"></i>
        Gudang
    </a>
@endsection

@section('subheading', ' / Detail Gudang ' . ucfirst($warehouse->name))

@section('content')
    @include('warehouses._edit-modal')

    <div class="space-y-6">
        <!-- Header Section with Back Button -->
        <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
            <div>
                <a href="{{ route('warehouses.index') }}"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 mb-4">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
                <h1 class="text-2xl font-semibold text-gray-800">Gudang: {{ $warehouse->name }}</h1>
                <div class="flex flex-wrap gap-4 mt-2">
                    <div class="flex items-center text-sm text-gray-500">
                        <i class="fas fa-map-marker-alt mr-2 text-gray-400"></i>
                        Lokasi: {{ $warehouse->location ?? 'Belum ditentukan' }}
                    </div>
                    <div class="flex items-center text-sm text-gray-500">
                        <i class="fas fa-boxes mr-2 text-gray-400"></i>
                        Total Item: {{ $warehouse->itemUnits->count() }}
                    </div>
                </div>
            </div>
            <div class="flex gap-2">
                <button
                    onclick="openEditModal({{ $warehouse->id }}, '{{ $warehouse->name }}', '{{ $warehouse->location }}', '{{ $warehouse->capacity }}', '{{ $warehouse->description }}')"
                    class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-md transition-colors duration-200 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path
                            d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                    Edit
                </button>
                <form method="POST" action="{{ route('warehouses.destroy', $warehouse->id) }}" class="delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="inline-flex items-center text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-4 py-2 rounded-lg">
                        <i class="fas fa-trash mr-2"></i> Hapus
                    </button>
                </form>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <!-- Total Capacity Card -->
            <div class="rounded-lg border border-blue-100 bg-blue-50 p-5 flex items-start">
                <div class="rounded-full p-3 mr-4 text-lg bg-blue-100 text-blue-600">
                    <i class="fas fa-warehouse"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-medium text-gray-500">Total Kapasitas</h3>
                    <p class="text-xl font-semibold mt-1">{{ $warehouse->capacity }} unit</p>
                    <div class="mt-3">
                        <div class="flex justify-between text-xs text-gray-500 mb-1">
                            <span>0%</span>
                            <span>100%</span>
                        </div>
                        <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-blue-400 to-blue-600 rounded-full" style="width: 100%">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Used Capacity Card -->
            <div class="rounded-lg border border-indigo-100 bg-indigo-50 p-5 flex items-start">
                <div class="rounded-full p-3 mr-4 text-lg bg-indigo-100 text-indigo-600">
                    <i class="fas fa-box-open"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-medium text-gray-500">Kapasitas Terpakai</h3>
                    <p class="text-xl font-semibold mt-1">
                        {{ $warehouse->itemUnits->sum('quantity') }} unit
                        <span class="text-sm ml-2 text-indigo-600">
                            ({{ round(($warehouse->itemUnits->sum('quantity') / $warehouse->capacity) * 100, 1) }}%)
                        </span>
                    </p>
                    <div class="mt-3">
                        <div class="flex justify-between text-xs text-gray-500 mb-1">
                            <span>0%</span>
                            <span>100%</span>
                        </div>
                        <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-indigo-400 to-indigo-600 rounded-full"
                                style="width: {{ ($warehouse->itemUnits->sum('quantity') / $warehouse->capacity) * 100 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Remaining Capacity Card -->
            <div class="rounded-lg border border-green-100 bg-green-50 p-5 flex items-start">
                <div class="rounded-full p-3 mr-4 text-lg bg-green-100 text-green-600">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-medium text-gray-500">Sisa Kapasitas</h3>
                    <p class="text-xl font-semibold mt-1">
                        {{ $warehouse->capacity - $warehouse->used_capacity }} unit
                        <span class="text-sm ml-2 text-green-600">
                            ({{ round((($warehouse->capacity - $warehouse->used_capacity) / $warehouse->capacity) * 100, 1) }}
                            %)
                        </span>
                    </p>
                    <div class="mt-3">
                        <div class="flex justify-between text-xs text-gray-500 mb-1">
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
        </div>

        <!-- Items Section -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
            <div
                class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-boxes mr-2"></i>Daftar Item di Gudang
                    </h3>
                    <p class="text-sm text-gray-600">Total {{ $warehouse->itemUnits->count() }} item terdaftar</p>
                </div>
                <a href="{{ route('item-units.create') }}?warehouse={{ $warehouse->id }}"
                    class="inline-flex items-center bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg">
                    <i class="fas fa-plus mr-2"></i>Tambah Unit Baru
                </a>
            </div>

            @if ($warehouse->itemUnits->isEmpty())
                <div class="p-12 text-center">
                    <i class="fas fa-box-open text-gray-400 text-5xl mx-auto mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-700">Gudang kosong</h3>
                    <p class="mt-1 text-sm text-gray-500">Belum ada item yang tersimpan di gudang ini.</p>
                    <a href="{{ route('items.create') }}"
                        class="inline-flex items-center mt-4 bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg">
                        <i class="fas fa-plus mr-2"></i>Tambah Item Baru
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 p-6">
                    @foreach ($warehouse->itemUnits as $unit)
                        <div
                            class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow duration-200">
                            <!-- Item Image -->
                            <div class="h-48 bg-gray-100 relative overflow-hidden">
                                @if ($unit->item->image_url)
                                    <img class="w-full h-full object-cover" src="{{ asset($unit->item->image_url) }}"
                                        alt="{{ $unit->item->name }}">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                        <i class="fas fa-box-open text-gray-400 text-4xl"></i>
                                    </div>
                                @endif
                                <div class="absolute top-3 right-3">
                                    <span
                                        class="px-2 py-1 text-xs font-medium rounded-full
                                        {{ $unit->condition === 'baik'
                                            ? 'bg-green-100 text-green-800'
                                            : ($unit->condition === 'rusak'
                                                ? 'bg-red-100 text-red-800'
                                                : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ ucfirst($unit->condition) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Item Content -->
                            <div class="p-4">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="font-medium text-gray-900">{{ $unit->item->name }}</h3>
                                        <p class="text-sm text-gray-500 mt-1">SKU: {{ $unit->sku }}</p>
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
                                            <i class="fas fa-qrcode text-gray-400 text-xl"></i>
                                        @endif
                                    </div>
                                </div>

                                <div class="mt-4 flex space-x-2">
                                    <a href="{{ route('item-units.show', $unit->id) }}"
                                        class="flex-1 text-center text-sm bg-amber-50 hover:bg-amber-100 text-amber-700 py-1.5 px-3 rounded">
                                        <i class="fas fa-eye mr-1"></i> Lihat
                                    </a>
                                    <a href="{{ route('item-units.edit', $unit->id) }}"
                                        class="flex-1 text-center text-sm bg-indigo-50 hover:bg-indigo-100 text-indigo-700 py-1.5 px-3 rounded">
                                        <i class="fas fa-edit mr-1"></i> Edit
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <script>
        function openEditModal(id, name, location, capacity, description) {
            const form = document.getElementById('edit-warehouse-form');
            form.action = `{{ route('warehouses.update', ':id') }}`.replace(':id', id);
            document.getElementById('edit-id').value = id;
            document.getElementById('edit-name').value = name;
            document.getElementById('edit-location').value = location;
            document.getElementById('edit-capacity').value = capacity;
            document.getElementById('edit-description').value = description;

            // Show modal
            document.getElementById('edit-modal').classList.remove('hidden');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }
    </script>
@endsection
