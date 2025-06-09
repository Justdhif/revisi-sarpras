@extends('layouts.app')

@section('title', 'SISFO Sarpras - Barang Rusak')

@section('heading')
    <a href="{{ route('damaged-items.index') }}">
        <i class="fas fa-exclamation-triangle ml-2 mr-1 text-red-300"></i>
        Barang Rusak
    </a>
@endsection

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-4">Daftar Barang Rusak</h1>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto bg-white rounded shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">#</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Nama Barang</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">SKU / Kode Unit</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Jumlah Rusak</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Tanggal Rusak</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($damagedItems as $index => $item)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $loop->iteration + ($damagedItems->currentPage() - 1) * $damagedItems->perPage() }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $item->itemUnit->item->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $item->itemUnit->sku ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-red-600 font-semibold">{{ $item->quantity }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($item->damaged_at)->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $item->description ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada data barang
                                rusak.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $damagedItems->links() }}
        </div>
    </div>
@endsection
