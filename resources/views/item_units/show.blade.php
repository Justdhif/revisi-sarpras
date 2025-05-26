@extends('layouts.app')

@section('title', 'SISFO Sarpras - Detail Unit')

@section('heading')
    <a href="{{ route('item-units.index') }}">
        <i class="fas fa-box ml-2 mr-1 text-indigo-300"></i>
        Unit Barang
    </a>
@endsection

@section('subheading', ' / Detail Unit ' . ucfirst($itemUnit->name))

@section('content')
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
            <div>
                <a href="{{ route('item-units.index') }}"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 mb-4">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
                <h1 class="text-2xl font-semibold text-gray-800">Unit Barang: {{ $itemUnit->item->name }}</h1>
                <p class="text-sm text-gray-500">SKU: {{ $itemUnit->sku }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('item-units.edit', $itemUnit->id) }}"
                    class="inline-flex items-center text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-4 py-2 rounded-lg">
                    <i class="fas fa-edit mr-2"></i> Edit
                </a>
                <form method="POST" action="{{ route('item-units.destroy', $itemUnit->id) }}" class="delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="inline-flex items-center text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-4 py-2 rounded-lg">
                        <i class="fas fa-trash mr-2"></i> Hapus
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Product Information Card -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                            Informasi Produk
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500">SKU</h3>
                                    <p class="mt-1 text-sm text-gray-900 font-mono">{{ $itemUnit->sku }}</p>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500">Nama Produk</h3>
                                    <p class="mt-1 text-sm text-gray-900">{{ $itemUnit->item->name }}</p>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500">Kondisi</h3>
                                    <p class="mt-1">
                                        <span
                                            class="px-2 py-1 text-xs font-medium rounded-full
                                            {{ $itemUnit->condition === 'baru' ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                                            {{ $itemUnit->condition }}
                                        </span>
                                    </p>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500">Status</h3>
                                    <p class="mt-1">
                                        <span
                                            class="px-2 py-1 text-xs font-medium rounded-full
                                            {{ $itemUnit->status === 'tersedia' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($itemUnit->status) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Warehouse Information Card -->
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm mt-6">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-warehouse mr-2 text-indigo-600"></i>
                            Lokasi Penyimpanan
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Gudang</h3>
                                <p class="mt-1 text-sm text-gray-900">{{ $itemUnit->warehouse->name }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Lokasi</h3>
                                <p class="mt-1 text-sm text-gray-900">{{ $itemUnit->warehouse->location }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- QR Code Card -->
            <div>
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm h-full">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-qrcode mr-2 text-purple-600"></i>
                            Kode Identifikasi
                        </h2>
                    </div>
                    <div class="p-6 flex flex-col items-center">
                        <div class="bg-white p-4 rounded-lg border border-gray-200 mb-4">
                            {!! QrCode::size(180)->generate($itemUnit->sku) !!}
                        </div>
                        <p class="text-gray-500 text-sm mb-6">Scan QR code untuk verifikasi produk</p>
                        <div class="flex flex-col sm:flex-row gap-3 w-full">
                            <button
                                class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-blue-500 text-blue-600 rounded-lg hover:bg-blue-50">
                                <i class="fas fa-print mr-2"></i> Cetak
                            </button>
                            <button
                                class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                                <i class="fas fa-download mr-2"></i> Download
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Last Updated -->
        <div class="text-sm text-gray-500 mt-4">
            <i class="fas fa-clock mr-1"></i> Terakhir diperbarui: {{ $itemUnit->updated_at->format('d M Y H:i') }}
        </div>
    </div>
@endsection
