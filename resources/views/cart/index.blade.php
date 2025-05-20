@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto px-6 py-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-light text-gray-800 tracking-wide">Keranjang Peminjaman</h1>
            <div class="text-sm text-gray-500">
                <span class="font-medium">{{ $carts->count() }}</span> item(s)
            </div>
        </div>

        @if ($carts->isEmpty())
            <div class="bg-white rounded-xl shadow-sm p-8 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-700">Keranjang kosong</h3>
                <p class="mt-1 text-gray-500">Tambahkan barang untuk memulai peminjaman</p>
                <div class="mt-6">
                    <a href="{{ route('items.index') }}"
                        class="px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded-lg shadow-md hover:shadow-lg transition duration-300">
                        Jelajahi Barang
                    </a>
                </div>
            </div>
        @else
            <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-8">
                <div class="grid grid-cols-12 bg-gray-50 p-4 border-b border-gray-100">
                    <div class="col-span-5 font-medium text-gray-600 uppercase text-xs tracking-wider">Barang</div>
                    <div class="col-span-2 font-medium text-gray-600 uppercase text-xs tracking-wider">Unit</div>
                    <div class="col-span-3 font-medium text-gray-600 uppercase text-xs tracking-wider">Jumlah</div>
                    <div class="col-span-2 font-medium text-gray-600 uppercase text-xs tracking-wider">Aksi</div>
                </div>

                @foreach ($carts as $cart)
                    <div
                        class="grid grid-cols-12 p-4 items-center border-b border-gray-100 hover:bg-gray-50 transition duration-150">
                        <div class="col-span-5 flex items-center">
                            <div
                                class="flex-shrink-0 h-10 w-10 bg-blue-50 rounded-lg flex items-center justify-center mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-gray-800 font-medium">{{ $cart->item->name }}</h4>
                                <p class="text-xs text-gray-500">SKU: {{ $cart->item->sku }}</p>
                            </div>
                        </div>
                        <div class="col-span-2 text-gray-600">{{ $cart->itemUnit->sku }}</div>
                        <div class="col-span-3">
                            <form action="{{ route('cart.updateQuantity', $cart->id) }}" method="POST"
                                class="flex items-center space-x-2">
                                @csrf
                                @method('PATCH')
                                <input type="number" name="quantity" value="{{ $cart->quantity }}" min="1"
                                    class="w-20 px-3 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-center">
                                <button type="submit"
                                    class="px-3 py-1 bg-blue-50 text-blue-600 rounded-md hover:bg-blue-100 transition duration-200 text-sm">
                                    Update
                                </button>
                            </form>
                        </div>
                        <div class="col-span-2">
                            <form action="{{ route('cart.destroy', $cart->id) }}" method="POST" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="text-red-500 hover:text-red-700 transition duration-200 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-xl font-light text-gray-800">Formulir Peminjaman</h2>
                </div>

                <form action="{{ route('cart.submit') }}" method="POST" class="p-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pinjam</label>
                            <div class="relative">
                                <input type="date" name="borrow_date_expected"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                    required>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kembali</label>
                            <div class="relative">
                                <input type="date" name="return_date_expected"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                    required>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alasan Peminjaman</label>
                            <textarea name="reason" rows="3"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                required></textarea>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Tambahan (Opsional)</label>
                            <textarea name="notes" rows="2"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"></textarea>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded-lg shadow-md hover:shadow-lg transition duration-300 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Ajukan Peminjaman
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>
@endsection
