@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-3">Detail Item: {{ $item->name }}</h2>

        <div class="mb-4">
            <img src="{{ asset($item->image_url) }}" alt="">
            <p><strong>Kategori:</strong> {{ $item->category->name }}</p>
            <p><strong>Tipe:</strong> {{ ucfirst($item->type) }}</p>
            <p><strong>Deskripsi:</strong> {{ $item->description ?? '-' }}</p>
        </div>

        <h4>Item Units</h4>
        @if ($item->itemUnits->count())
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>SKU</th>
                        <th>Kondisi</th>
                        <th>Status</th>
                        <th>Lokasi</th>
                        <th>Qty</th>
                        <th>QR Code</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($item->itemUnits as $unit)
                        <tr>
                            <td>{{ $unit->sku }}</td>
                            <td>{{ $unit->condition }}</td>
                            <td>{{ ucfirst($unit->status) }}</td>
                            <td>{{ $unit->warehouse->name }}</td>
                            <td>{{ $unit->quantity }}</td>
                            <td>
                                @if ($unit->qr_image_url)
                                    {!! QrCode::size(60)->generate($unit->sku) !!}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Tidak ada unit untuk item ini.</p>
        @endif

        <a href="{{ route('items.index') }}" class="btn btn-secondary mt-3">Kembali ke Daftar Item</a>
    </div>
@endsection
