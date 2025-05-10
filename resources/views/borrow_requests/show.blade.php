@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Detail Permintaan Peminjaman</h2>

        <div class="card mb-3">
            <div class="card-body">
                <p><strong>Pemohon:</strong> {{ $borrowRequest->user->username }}</p>
                <p><strong>Tanggal Kembali (ekspektasi):</strong> {{ $borrowRequest->return_date_expected }}</p>
                <p><strong>Status:</strong> {{ ucfirst($borrowRequest->status) }}</p>
                <p><strong>Catatan:</strong> {{ $borrowRequest->notes ?? '-' }}</p>

                @if ($borrowRequest->status === 'pending')
                    <form action="{{ route('borrow-requests.approve', $borrowRequest->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button class="btn btn-success">Setujui Permintaan & Tambah Barang</button>
                    </form>
                @endif
            </div>
        </div>

        @if ($borrowRequest->status === 'approved')
            <h4>Barang Dipinjam</h4>
            <div class="mb-3">
                <a href="{{ route('borrow-details.create', $borrowRequest->id) }}" class="btn btn-primary">+ Tambah
                    Barang</a>
            </div>
            <ul class="list-group">
                @foreach ($borrowRequest->borrowDetail as $detail)
                    <li class="list-group-item">
                        {{ $detail->itemUnit->item->name }} (SKU: {{ $detail->itemUnit->sku }}) - {{ $detail->quantity }}
                        unit
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection
