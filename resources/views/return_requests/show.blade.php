@extends('layouts.app')

@section('title', 'SISFO Sarpras - Detail Pengembalian')

@section('heading')
    <a href="{{ route('return-requests.index') }}">
        <i class="fas fa-redo-alt ml-2 mr-1 text-indigo-300"></i>
        Pengembalian
    </a>
@endsection

@section('subheading', ' / Detail Pengembalian ' . ucfirst($returnRequest->borrowRequest->user->username))

@section('content')
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
            <div>
                <a href="{{ route('return-requests.index') }}"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 mb-4">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
                <h1 class="text-2xl font-semibold text-gray-800">Detail Pengembalian</h1>
                <p class="text-sm text-gray-500">Informasi lengkap pengembalian barang</p>
            </div>
            <span
                class="px-3 py-1 rounded-full text-sm font-medium
                {{ $returnRequest->status === 'approved'
                    ? 'bg-green-100 text-green-800'
                    : ($returnRequest->status === 'rejected'
                        ? 'bg-red-100 text-red-800'
                        : 'bg-amber-100 text-amber-800') }}">
                {{ ucfirst($returnRequest->status) }}
            </span>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                    Informasi Pengembalian
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 flex items-center">
                                <i class="fas fa-user mr-2 text-gray-400"></i>Peminjam
                            </h3>
                            <p class="mt-1 text-gray-900">{{ $returnRequest->borrowRequest->user->username }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 flex items-center">
                                <i class="fas fa-calendar-day mr-2 text-gray-400"></i>Tanggal Pengembalian
                            </h3>
                            <p class="mt-1 text-gray-900">
                                {{ $returnRequest->created_at->format('d M Y H:i') }}
                            </p>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 flex items-center">
                                <i class="fas fa-sticky-note mr-2 text-gray-400"></i>Catatan
                            </h3>
                            <p class="mt-1 text-gray-900">
                                {{ $returnRequest->notes ?? 'Tidak ada catatan' }}
                            </p>
                        </div>
                    </div>
                </div>

                @if ($returnRequest->status === 'pending')
                    <div class="mt-8 pt-6 border-t border-gray-200 flex space-x-3">
                        <form method="POST" action="{{ route('return-requests.approve', $returnRequest) }}">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg">
                                <i class="fas fa-check mr-2"></i> Setujui
                            </button>
                        </form>
                        <form method="POST" action="{{ route('return-requests.reject', $returnRequest) }}">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg">
                                <i class="fas fa-times mr-2"></i> Tolak
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        <!-- Items Card -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-boxes mr-2 text-indigo-600"></i>
                    Barang Dikembalikan
                </h2>
            </div>
            <div class="p-6">
                @if ($returnRequest->returnDetails->count() > 0)
                    <div class="space-y-4">
                        @foreach ($returnRequest->returnDetails as $detail)
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex flex-col md:flex-row gap-4">
                                    <!-- Item Info -->
                                    <div class="flex-1">
                                        <div class="flex items-start">
                                            @if ($detail->itemUnit->item->image_url)
                                                <img src="{{ asset($detail->itemUnit->item->image_url) }}"
                                                    class="h-16 w-16 rounded-lg object-cover mr-3 border border-gray-200"
                                                    alt="{{ $detail->itemUnit->item->name }}">
                                            @else
                                                <div
                                                    class="h-16 w-16 rounded-lg bg-gray-100 flex items-center justify-center mr-3 border border-gray-200">
                                                    <i class="fas fa-box text-gray-400 text-xl"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h3 class="font-medium text-gray-800">{{ $detail->itemUnit->item->name }}
                                                </h3>
                                                <p class="text-sm text-gray-500">SKU: {{ $detail->itemUnit->sku }}</p>
                                                <p class="mt-2">
                                                    <span class="text-sm font-medium text-gray-500">Kondisi:</span>
                                                    <span
                                                        class="px-2 py-1 text-xs font-medium rounded-full
                                                        {{ $detail->condition === 'baik'
                                                            ? 'bg-green-100 text-green-800'
                                                            : ($detail->condition === 'rusak'
                                                                ? 'bg-red-100 text-red-800'
                                                                : 'bg-yellow-100 text-yellow-800') }}">
                                                        {{ ucfirst($detail->condition) }}
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Photo Evidence -->
                                    <div class="md:w-1/3">
                                        @if ($detail->photo)
                                            <h4 class="text-sm font-medium text-gray-500 mb-2">Bukti Foto:</h4>
                                            <img src="{{ asset('storage/' . $detail->photo) }}" alt="Foto Pengembalian"
                                                class="rounded-lg border border-gray-200 max-w-full h-auto cursor-pointer"
                                                onclick="window.open('{{ asset('storage/' . $detail->photo) }}', '_blank')">
                                        @else
                                            <p class="text-sm text-gray-500">Tidak ada foto bukti</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-box-open text-gray-400 text-4xl mb-3"></i>
                        <h3 class="text-lg font-medium text-gray-700">Tidak ada barang dikembalikan</h3>
                        <p class="text-sm text-gray-500">Tidak ditemukan data barang yang dikembalikan</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
