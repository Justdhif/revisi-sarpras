@extends('layouts.app')

@section('title', 'SISFO Sarpras - Detail Permintaan Peminjaman')

@section('heading')
    <a href="{{ route('borrow-requests.index') }}">
        <i class="fas fa-undo-alt ml-2 mr-1 text-indigo-300"></i>
        Peminjaman
    </a>
@endsection

@section('subheading', ' / Daftar Permohonan ' . ucfirst($borrowRequest->user->username))

@section('content')
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
            <div>
                <a href="{{ route('borrow-requests.index') }}"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 mb-4">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
                <h1 class="text-2xl font-semibold text-gray-800">Permintaan Peminjaman</h1>
                <p class="text-sm text-gray-500">Detail lengkap permintaan peminjaman barang</p>
            </div>
            <span
                class="px-3 py-1 rounded-full text-sm font-medium
                {{ $borrowRequest->status === 'approved'
                    ? 'bg-green-100 text-green-800'
                    : ($borrowRequest->status === 'pending'
                        ? 'bg-amber-100 text-amber-800'
                        : 'bg-gray-100 text-gray-800') }}">
                {{ ucfirst($borrowRequest->status) }}
            </span>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                    Informasi Peminjaman
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 flex items-center">
                                <i class="fas fa-user mr-2 text-gray-400"></i>Pemohon
                            </h3>
                            <p class="mt-1 text-gray-900">{{ $borrowRequest->user->username }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 flex items-center">
                                <i class="fas fa-calendar-day mr-2 text-gray-400"></i>Tanggal Kembali
                            </h3>
                            <p class="mt-1 text-gray-900">
                                {{ \Carbon\Carbon::parse($borrowRequest->return_date_expected)->isoFormat('D MMMM YYYY') }}
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
                                {{ $borrowRequest->notes ?? 'Tidak ada catatan' }}
                            </p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 flex items-center">
                                <i class="fas fa-comment-alt mr-2 text-gray-400"></i>Alasan
                            </h3>
                            <p class="mt-1 text-gray-900">
                                {{ $borrowRequest->reason ?? 'Tidak ada alasan' }}
                            </p>
                        </div>
                    </div>
                </div>

                @if ($borrowRequest->status === 'pending')
                    <div class="mt-8 pt-6 border-t border-gray-200 flex space-x-3">
                        <form method="POST" action="{{ route('borrow-requests.approve', $borrowRequest) }}">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg">
                                <i class="fas fa-check mr-2"></i> Setujui
                            </button>
                        </form>
                        <form method="POST" action="{{ route('borrow-requests.reject', $borrowRequest) }}">
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
            <div
                class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-boxes mr-2 text-indigo-600"></i>
                    Barang Dipinjam
                </h2>
                <a href="{{ route('borrow-details.create', $borrowRequest->id) }}"
                    class="inline-flex items-center bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg">
                    <i class="fas fa-plus mr-2"></i> Tambah Barang
                </a>
            </div>

            @if ($borrowRequest->borrowDetail->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama Barang</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    SKU</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Jumlah</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($borrowRequest->borrowDetail as $detail)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if ($detail->itemUnit->item->image_url)
                                                <img src="{{ asset($detail->itemUnit->item->image_url) }}"
                                                    class="h-10 w-10 rounded-lg object-cover mr-3 border border-gray-200"
                                                    alt="{{ $detail->itemUnit->item->name }}">
                                            @else
                                                <div
                                                    class="h-10 w-10 rounded-lg bg-gray-100 flex items-center justify-center mr-3 border border-gray-200">
                                                    <i class="fas fa-box text-gray-400"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="font-medium text-gray-900">{{ $detail->itemUnit->item->name }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $detail->itemUnit->item->category->name ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                                            {{ $detail->itemUnit->sku }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-gray-900">
                                        {{ $detail->quantity }} unit
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm space-x-2">
                                        <form action="{{ route('borrow-details.destroy', $detail->id) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-12 text-center">
                    <i class="fas fa-box-open text-gray-400 text-5xl mx-auto mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-700">Belum ada barang yang dipinjam</h3>
                    <p class="mt-1 text-sm text-gray-500">Tambahkan barang untuk melengkapi permintaan peminjaman</p>
                    <div class="mt-6">
                        <a href="{{ route('borrow-details.create', $borrowRequest->id) }}"
                            class="inline-flex items-center bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg">
                            <i class="fas fa-plus mr-2"></i> Tambah Barang
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- Last Updated -->
        <div class="text-sm text-gray-500 mt-4">
            <i class="fas fa-clock mr-1"></i> Terakhir diperbarui: {{ $borrowRequest->updated_at->format('d M Y H:i') }}
        </div>
    </div>
@endsection
