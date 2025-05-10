@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4">QR Code Semua Item Unit</h2>
        <button onclick="window.print()" class="btn btn-primary mb-4">🖨️ Cetak Halaman</button>

        <div class="row">
            @foreach ($itemUnits as $unit)
                <div class="col-md-3 text-center mb-4">
                    <div style="border: 1px solid #ccc; padding: 10px;">
                        <strong>{{ $unit->item->name }}</strong><br>
                        <small>{{ $unit->sku }}</small><br>
                        {!! QrCode::format('svg')->size(100)->generate($unit->sku) !!}
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <style>
        @media print {
            .btn {
                display: none !important;
            }
        }
    </style>
@endsection
