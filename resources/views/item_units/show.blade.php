@extends('layouts.app')

@section('content')
    <h1>Detail Unit Barang</h1>

    <p><strong>SKU:</strong> {{ $itemUnit->sku }}</p>
    <p><strong>Kondisi:</strong> {{ $itemUnit->condition }}</p>
    <p><strong>Status:</strong> {{ ucfirst($itemUnit->status) }}</p>
    <p><strong>Item:</strong> {{ $itemUnit->item->name }}</p>
    <p><strong>Gudang:</strong> {{ $itemUnit->warehouse->name }}</p>

    <h4>QR Code:</h4>
    <img src="{{ asset($itemUnit->qr_image_url) }}" alt="QR Code" width="200">

    <br><br>
    <a href="{{ asset($itemUnit->qr_image_url) }}" class="btn btn-success" download>📥 Download QR</a>
    <a href="{{ asset($itemUnit->qr_image_url) }}" class="btn btn-secondary" target="_blank">🖨️ Cetak QR</a>
    <a href="{{ route('item-units.index') }}" class="btn btn-warning">⬅️ Kembali</a>
@endsection
