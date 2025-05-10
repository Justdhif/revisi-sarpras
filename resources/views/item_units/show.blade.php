@extends('layouts.app')

@section('title', 'SISFO Sarpras - Detail Unit')

@section('heading', 'Detail Unit')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <!-- Card Header -->
                <div class="bg-gradient-to-r from-indigo-600 to-purple-700 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h1 class="text-2xl font-bold text-white">Detail Unit Barang</h1>
                        <a href="{{ route('item-units.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-md text-white text-sm font-medium transition duration-150 ease-in-out">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                            Kembali
                        </a>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Product Information -->
                        <div class="space-y-6">
                            <div class="bg-gray-50 p-5 rounded-lg border border-gray-100">
                                <h2 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-2 mb-4">Informasi
                                    Produk</h2>
                                <div class="space-y-4">
                                    <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                                        <span class="text-gray-600 font-medium">SKU</span>
                                        <span class="text-gray-900 font-semibold">{{ $itemUnit->sku }}</span>
                                    </div>
                                    <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                                        <span class="text-gray-600 font-medium">Nama Produk</span>
                                        <span class="text-gray-900 font-semibold">{{ $itemUnit->item->name }}</span>
                                    </div>
                                    <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                                        <span class="text-gray-600 font-medium">Kondisi</span>
                                        <span
                                            class="px-3 py-1 rounded-full text-xs font-medium {{ $itemUnit->condition === 'baru' ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                                            {{ $itemUnit->condition }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 font-medium">Status</span>
                                        <span
                                            class="px-3 py-1 rounded-full text-xs font-medium {{ $itemUnit->status === 'tersedia' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($itemUnit->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Storage Information -->
                            <div class="bg-gray-50 p-5 rounded-lg border border-gray-100">
                                <h2 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-2 mb-4">Lokasi
                                    Penyimpanan</h2>
                                <div class="space-y-4">
                                    <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                                        <span class="text-gray-600 font-medium">Gudang</span>
                                        <span class="text-gray-900 font-semibold">{{ $itemUnit->warehouse->name }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 font-medium">Lokasi</span>
                                        <span class="text-gray-900 font-semibold">{{  $itemUnit->warehouse->location }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- QR Code Section -->
                        <div class="flex flex-col">
                            <div
                                class="bg-gradient-to-br from-gray-50 to-gray-100 p-6 rounded-lg border border-gray-200 h-full flex flex-col items-center justify-center">
                                <h2 class="text-lg font-semibold text-gray-700 mb-4">Kode Identifikasi Produk</h2>
                                <div class="bg-white p-4 rounded-md shadow-sm border border-gray-200 mb-4">
                                    {!! QrCode::format('svg')->size(180)->generate($itemUnit->sku) !!}
                                </div>
                                <p class="text-gray-500 text-sm mb-6">Scan QR code untuk verifikasi produk</p>

                                <div class="flex space-x-3">
                                    <button
                                        class="inline-flex items-center px-4 py-2 border border-blue-500 text-blue-600 rounded-md hover:bg-blue-50 transition duration-150 ease-in-out">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Cetak Label
                                    </button>
                                    <button
                                        class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition duration-150 ease-in-out">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Download QR
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Footer -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500 text-sm">Terakhir diperbarui:
                            {{ $itemUnit->updated_at->format('d M Y H:i') }}</span>
                        <div class="flex space-x-2">
                            <a href="{{ route('item-units.edit', $itemUnit->id) }}"
                                class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-md transition-colors duration-200 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path
                                        d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                                Edit
                            </a>
                            <form action="{{ route('item-units.destroy', $itemUnit) }}" method="POST"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus unit ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="text-rose-600 hover:text-rose-900 bg-rose-50 hover:bg-rose-100 px-3 py-1.5 rounded-md transition-colors duration-200 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
