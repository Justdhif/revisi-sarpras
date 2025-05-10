@extends('layouts.app')

@section('content')
    <h2>Ajukan Pengembalian</h2>

    <form action="{{ route('return_requests.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="borrow_request_id" value="{{ $borrowRequest->id }}">

        <div class="mb-3">
            <label>Catatan</label>
            <textarea name="notes" class="form-control"></textarea>
        </div>

        <h4>Item yang Dipinjam:</h4>
        @if ($borrowRequest->borrowDetail->isEmpty())
            <div class="alert alert-warning">
                Tidak ada data item yang dipinjam.
            </div>
        @else
            @foreach ($borrowRequest->borrowDetail as $detail)
                <input type="hidden" name="item_units[{{ $loop->index }}][id]" value="{{ $detail->itemUnit->id }}">
                <div class="mb-2">
                    <label>{{ $detail->itemUnit->item->name }} (SKU: {{ $detail->itemUnit->sku }})</label>
                    <input type="text" name="item_units[{{ $loop->index }}][condition]" class="form-control mb-1"
                        placeholder="Kondisi saat dikembalikan" required>
                    <input type="file" name="item_units[{{ $loop->index }}][photo]" class="form-control" required>
                </div>
            @endforeach
        @endif

        <button type="submit" class="btn btn-primary">Ajukan Pengembalian</button>
    </form>
@endsection
