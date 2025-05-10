@extends('layouts.app')

@section('title', 'SISFO Sarpras - Detail Peminjaman')

@section('heading', 'Detail Peminjaman')

@section('content')
<div class="container py-4">
    <!-- Header Section -->
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Detail Peminjaman Sarana Prasarana</h2>
        <p class="text-gray-600">Daftar lengkap peminjaman barang</p>
    </div>

    <!-- Stats Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-50 text-blue-600 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Peminjaman</p>
                    <p class="text-xl font-semibold">{{ count($borrowDetails) }} Barang</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-50 text-green-600 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Disetujui</p>
                    <p class="text-xl font-semibold">{{ $borrowDetails->where('borrowRequest.status', 'approved')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-50 text-yellow-600 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Menunggu</p>
                    <p class="text-xl font-semibold">{{ $borrowDetails->where('borrowRequest.status', 'pending')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Borrow Details Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach ($borrowDetails as $detail)
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-200">
            <!-- Item Image and QR -->
            <div class="relative">
                @if ($detail->itemUnit->item->image_url)
                    <img src="{{ $detail->itemUnit->item->image_url }}" alt="{{ $detail->itemUnit->item->name }}" class="w-full h-40 object-cover">
                @else
                    <div class="w-full h-40 bg-gray-100 flex items-center justify-center text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                @endif

                <!-- Status Badge -->
                <div class="absolute top-2 right-2">
                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                        {{ $detail->borrowRequest->status === 'approved' ? 'bg-green-100 text-green-800' :
                           ($detail->borrowRequest->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                        {{ ucfirst($detail->borrowRequest->status) }}
                    </span>
                </div>

                <!-- QR Code (small) -->
                @if ($detail->itemUnit->qr_image_url)
                <div class="absolute bottom-2 left-2 bg-white p-1 rounded shadow-xs">
                    {!! QrCode::size(60)->generate($detail->itemUnit->sku) !!}
                </div>
                @endif
            </div>

            <!-- Item Details -->
            <div class="p-4">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="font-semibold text-gray-800">{{ $detail->itemUnit->item->name }}</h3>
                    <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">SKU: {{ $detail->itemUnit->sku }}</span>
                </div>

                <div class="flex items-center text-sm text-gray-500 mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    {{ $detail->borrowRequest->user->username }}
                </div>

                <div class="flex items-center text-sm text-gray-500 mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Kembali: {{ \Carbon\Carbon::parse($detail->borrowRequest->return_date_expected)->format('d M Y') }}
                </div>

                <div class="grid grid-cols-2 gap-2 text-sm mb-3">
                    <div>
                        <p class="text-gray-500">Jumlah</p>
                        <p class="font-medium">{{ $detail->quantity }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Kondisi</p>
                        <div class="flex items-center">
                            <span class="font-medium capitalize">{{ $detail->itemUnit->condition }}</span>
                            @if($detail->itemUnit->condition === 'baik')
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-500 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            @elseif($detail->itemUnit->condition === 'rusak')
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-500 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="pt-2 border-t border-gray-100 text-sm">
                    <p class="text-gray-500">Disetujui oleh:</p>
                    <p class="font-medium">{{ $detail->borrowRequest->approver->username ?? '-' }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Footer -->
    <div class="mt-6 text-center text-sm text-gray-500">
        <p>SISFO Sarpras • {{ date('d M Y') }}</p>
    </div>
</div>
@endsection
