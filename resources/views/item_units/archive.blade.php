@extends('layouts.app')

@section('content')
    <div class="container">
        <h3 class="text-xl font-semibold mb-4">🗂 Arsip Unit Barang</h3>

        <div class="overflow-auto">
            <table>
                <thead>
                    <tr>
                        <th>
                            SKU
                        </th>
                        <th>
                            Barang
                        </th>
                        <th>
                            Kondisi</th>
                        <th>
                            Gudang</th>
                        <th>
                            Status</th>
                        <th>
                            Kode QR
                        </th>
                        <th>
                            Kuantitas</th>
                        <th>
                            Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($itemUnits as $unit)
                        <tr>
                            <td>
                                <div>{{ $unit->sku }}</div>
                            </td>
                            <td>
                                <div>
                                    <div>{{ $unit->item->name }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                @php
                                    $conditionColors = [
                                        'new' => 'bg-green-100 text-green-800',
                                        'used' => 'bg-blue-100 text-blue-800',
                                        'refurbished' => 'bg-purple-100 text-purple-800',
                                        'damaged' => 'bg-red-100 text-red-800',
                                    ];
                                    $colorClass =
                                        $conditionColors[strtolower($unit->condition)] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span
                                    class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $colorClass }}">
                                    {{ ucfirst($unit->condition) }}
                                </span>
                            </td>
                            <td>
                                <div class="text-sm text-gray-600">{{ $unit->warehouse->name }}</div>
                            </td>
                            <td>
                                @php
                                    $statusColors = [
                                        'available' => 'bg-green-100 text-green-800',
                                        'reserved' => 'bg-yellow-100 text-yellow-800',
                                        'out_of_stock' => 'bg-red-100 text-red-800',
                                    ];
                                    $statusClass =
                                        $statusColors[strtolower($unit->status)] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span
                                    class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                    {{ ucfirst(str_replace('_', ' ', $unit->status)) }}
                                </span>
                            </td>
                            <td>
                                <div>
                                    {!! QrCode::size(60)->generate($unit->sku) !!}
                                </div>
                            </td>
                            <td>
                                <div>{{ $unit->quantity }}</div>
                            </td>
                            <td>
                                <form action="{{ route('item-units.restore', $unit->id) }}" method="POST"
                                    class="inline-block">
                                    @csrf
                                    <button class="btn btn-sm btn-success">Pulihkan</button>
                                </form>

                                <form action="{{ route('item-units.forceDelete', $unit->id) }}" method="POST"
                                    class="inline-block" onsubmit="return confirm('Hapus permanen unit ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Hapus Permanen</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="13" class="text-center">Tidak ada unit barang yang diarsipkan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
