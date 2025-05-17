@extends('layouts.app')

@section('title', 'SISFO Sarpras - Detail Permintaan Peminjaman')

@section('content')
    <div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto">
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Permintaan Peminjaman</h1>
                    <p class="mt-2 text-gray-600">Detail lengkap permintaan peminjaman barang</p>
                </div>
                <span
                    class="mt-4 md:mt-0 px-4 py-2 rounded-full text-sm font-medium
                {{ $borrowRequest->status === 'approved'
                    ? 'bg-emerald-100 text-emerald-800'
                    : ($borrowRequest->status === 'pending'
                        ? 'bg-amber-100 text-amber-800'
                        : 'bg-gray-100 text-gray-800') }}">
                    {{ ucfirst($borrowRequest->status) }}
                </span>
            </div>

            <!-- Main Card -->
            <div class="bg-white shadow-lg rounded-xl overflow-hidden mb-8">
                <!-- Card Header -->
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-xl font-semibold text-gray-800">Informasi Peminjaman</h2>
                </div>

                <!-- Card Content -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left Column -->
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-xs font-medium text-gray-500 uppercase tracking-wider">Pemohon</h3>
                                <div class="mt-1 flex items-center">
                                    <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <p class="text-lg font-medium text-gray-900">{{ $borrowRequest->user->username }}</p>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Kembali</h3>
                                <div class="mt-1 flex items-center">
                                    <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-lg font-medium text-gray-900">
                                        {{ \Carbon\Carbon::parse($borrowRequest->return_date_expected)->isoFormat('D MMMM YYYY') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</h3>
                                <div class="mt-1 flex">
                                    <svg class="h-5 w-5 text-gray-400 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="text-lg font-medium text-gray-900">
                                        {{ $borrowRequest->notes ?? 'Tidak ada catatan' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($borrowRequest->status === 'pending')
                        <div class="mt-8 pt-6 border-t border-gray-100">
                            <form action="{{ route('borrow-requests.approve', $borrowRequest->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit"
                                    class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out transform hover:-translate-y-0.5">
                                    <svg class="-ml-1 mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    Setujui Permintaan
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white shadow-lg rounded-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-800">Barang Dipinjam</h2>
                    <a href="{{ route('borrow-details.create', $borrowRequest->id) }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Tambah Barang
                    </a>
                </div>

                <div class="overflow-x-auto">
                    @if ($borrowRequest->borrowDetail->count() > 0)
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama Barang</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        SKU</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jumlah</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($borrowRequest->borrowDetail as $detail)
                                    <tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div
                                                    class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                                                    <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                    </svg>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $detail->itemUnit->item->name }}</div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ $detail->itemUnit->item->category->name ?? '-' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                {{ $detail->itemUnit->sku }}
                                            </span>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-gray-900">
                                            {{ $detail->quantity }} unit
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada barang yang dipinjam</h3>
                            <p class="mt-1 text-sm text-gray-500">Tambahkan barang untuk melengkapi permintaan
                                peminjaman</p>
                            <div class="mt-6">
                                <a href="{{ route('borrow-details.create', $borrowRequest->id) }}"
                                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Tambah Barang
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
