@extends('layouts.app')

@section('content')
    <h2>Detail Pengembalian</h2>

    <p><strong>Status:</strong> {{ $returnRequest->status }}</p>
    <p><strong>Peminjam:</strong> {{ $returnRequest->borrowRequest->user->username }}</p>
    <p><strong>Catatan:</strong> {{ $returnRequest->notes }}</p>

    <h4>Item yang Dikembalikan:</h4>
    <ul>
        @foreach ($returnRequest->returnDetails as $detail)
            <li>
                {{ $detail->itemUnit->item->name }} - SKU: {{ $detail->itemUnit->sku }} <br>
                <strong>Kondisi:</strong> {{ $detail->condition }}
            </li>
            @if ($detail->photo)
                <img src="{{ asset('storage/' . $detail->photo) }}" alt="Foto Pengembalian" width="200">
            @endif
        @endforeach
    </ul>

    @if ($returnRequest->status == 'pending')
        <form method="POST" action="{{ route('return_requests.approve', $returnRequest) }}">
            @csrf
            <button class="btn btn-success">Setujui</button>
        </form>
        <form method="POST" action="{{ route('return_requests.reject', $returnRequest) }}">
            @csrf
            <button class="btn btn-danger mt-2">Tolak</button>
        </form>
    @endif
@endsection
