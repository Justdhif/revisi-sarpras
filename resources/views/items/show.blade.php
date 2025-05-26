@extends('layouts.app')

@section('title', 'SISFO Sarpras - Detail Barang')

@section('heading')
    <a href="{{ route('items.index') }}">
        <i class="fas fa-box ml-2 mr-1 text-indigo-300"></i>
        Barang
    </a>
@endsection

@section('subheading', ' / Detail Barang ' . ucfirst($item->name))

@section('content')
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
            <div>
                <a href="{{ route('items.index') }}"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 mb-4">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
                <h1 class="text-2xl font-semibold text-gray-800">Barang: {{ $item->name }}</h1>
                <p class="text-sm text-gray-500">Tampilan detail inventori barang</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('items.edit', $item->id) }}"
                    class="inline-flex items-center text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-4 py-2 rounded-lg">
                    <i class="fas fa-edit mr-2"></i> Edit
                </a>
                <form method="POST" action="{{ route('items.destroy', $item->id) }}" class="delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="inline-flex items-center text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-4 py-2 rounded-lg">
                        <i class="fas fa-trash mr-2"></i> Hapus
                    </button>
                </form>
            </div>
        </div>

        <!-- Item Card -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
            <div class="md:flex">
                <!-- Image Section -->
                <div class="md:w-1/3 bg-gray-50 flex items-center justify-center p-8">
                    @if ($item->image_url)
                        <img src="{{ asset($item->image_url) }}" alt="{{ $item->name }}"
                            class="w-full h-64 object-contain rounded-lg shadow-sm border border-gray-200">
                    @else
                        <div
                            class="w-full h-64 bg-gray-100 rounded-lg flex items-center justify-center border border-gray-200">
                            <i class="fas fa-box text-gray-400 text-5xl"></i>
                        </div>
                    @endif
                </div>

                <!-- Details Section -->
                <div class="md:w-2/3 p-6">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <span
                                class="inline-block bg-gradient-to-r from-blue-500 to-purple-600 text-white text-xs font-semibold px-3 py-1 rounded-full uppercase tracking-wider">
                                {{ $item->category->name }}
                            </span>
                            <h3 class="text-2xl font-light text-gray-800 mt-3">{{ $item->name }}</h3>
                            <p class="text-gray-500 capitalize">{{ $item->type }}</p>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-8">
                        <h4 class="text-lg font-medium text-gray-700 mb-3 flex items-center">
                            <i class="fas fa-align-left mr-2 text-gray-400"></i>Deskripsi
                        </h4>
                        <p class="text-gray-600 leading-relaxed bg-gray-50 p-4 rounded-lg border border-gray-200">
                            {{ $item->description ?? 'Tidak ada deskripsi tersedia' }}
                        </p>
                    </div>

                    <!-- Stats -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="rounded-lg border border-blue-100 bg-blue-50 p-4 flex items-start">
                            <div class="rounded-full p-3 mr-4 text-lg bg-blue-100 text-blue-600">
                                <i class="fas fa-boxes"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-sm font-medium text-gray-500">Total Unit</h3>
                                <p class="text-xl font-semibold mt-1">{{ $item->itemUnits->count() }}</p>
                            </div>
                        </div>

                        <div class="rounded-lg border border-purple-100 bg-purple-50 p-4 flex items-start">
                            <div class="rounded-full p-3 mr-4 text-lg bg-purple-100 text-purple-600">
                                <i class="fas fa-warehouse"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-sm font-medium text-gray-500">Gudang</h3>
                                <p class="text-xl font-semibold mt-1">
                                    {{ $item->itemUnits->unique('warehouse_id')->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Units Table -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <h4 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-box-open mr-2 text-blue-600"></i>
                        Unit Barang
                        <span class="ml-3 bg-blue-100 text-blue-800 text-sm font-semibold px-3 py-1 rounded-full">
                            {{ $item->itemUnits->count() }} unit
                        </span>
                    </h4>
                    <a href="{{ route('item-units.create') }}?item={{ $item->id }}"
                        class="inline-flex items-center bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg">
                        <i class="fas fa-plus mr-2"></i>Tambah Unit
                    </a>
                </div>
            </div>

            @if ($item->itemUnits->count())
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    SKU</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kondisi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Lokasi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Qty</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kode QR</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($item->itemUnits as $unit)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap font-mono text-sm text-gray-900">
                                        {{ $unit->sku }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            {{ $unit->condition === 'new'
                                                ? 'bg-green-100 text-green-800'
                                                : ($unit->condition === 'used'
                                                    ? 'bg-yellow-100 text-yellow-800'
                                                    : 'bg-red-100 text-red-800') }}">
                                            {{ ucfirst($unit->condition) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            {{ $unit->status === 'available' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($unit->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $unit->warehouse->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $unit->quantity }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if ($unit->qr_image_url)
                                            <div class="bg-white p-1 rounded border inline-block">
                                                {!! QrCode::size(50)->color(40, 40, 40)->generate($unit->sku) !!}
                                            </div>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm space-x-2">
                                        <a href="{{ route('item-units.show', $unit->id) }}"
                                            class="inline-flex items-center px-3 py-1.5 text-sm rounded text-amber-600 hover:text-amber-800 bg-amber-50 hover:bg-amber-100">
                                            <i class="fas fa-eye mr-1"></i> Lihat
                                        </a>
                                        <a href="{{ route('item-units.edit', $unit->id) }}"
                                            class="inline-flex items-center px-3 py-1.5 text-sm rounded text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100">
                                            <i class="fas fa-edit mr-1"></i> Edit
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-12 text-center">
                    <i class="fas fa-box-open text-gray-400 text-5xl mx-auto mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-700">Tidak ada unit ditemukan</h3>
                    <p class="mt-1 text-sm text-gray-500">Barang ini belum memiliki unit dalam inventori.</p>
                    <a href="{{ route('item-units.create') }}?item={{ $item->id }}"
                        class="inline-flex items-center mt-4 bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg">
                        <i class="fas fa-plus mr-2"></i>Tambah Unit
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
