@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Tambah Barang untuk Peminjaman</h2>

        <form action="{{ route('borrow-details.store') }}" method="POST">
            @csrf
            <input type="hidden" name="borrow_request_id" value="{{ $borrowRequest->id }}">

            <div class="mb-3">
                <label for="item_unit_id" class="form-label">Pilih Unit Barang</label>
                <select name="item_unit_id" id="item_unit_id" class="form-select" required>
                    <option value="">-- Pilih Unit --</option>
                    @foreach ($itemUnits as $unit)
                        <option value="{{ $unit->id }}">
                            {{ $unit->item->name }} (SKU: {{ $unit->sku }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="quantity" class="form-label">Jumlah</label>
                <input type="number" class="form-control" name="quantity" min="1" value="1" required>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
@endsection
