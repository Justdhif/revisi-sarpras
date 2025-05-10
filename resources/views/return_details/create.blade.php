@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tambah Detail Pengembalian</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Oops!</strong> Ada kesalahan saat input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('return-details.store') }}" method="POST">
        @csrf

        <input type="hidden" name="return_request_id" value="{{ $returnRequest->id }}">

        <div class="mb-3">
            <label for="item_unit_id" class="form-label">Pilih Unit Barang</label>
            <select name="item_unit_id" class="form-control" required>
                <option value="">-- Pilih Unit --</option>
                @foreach ($itemUnits as $unit)
                    <option value="{{ $unit->id }}">
                        {{ $unit->item->name ?? 'Barang' }} (Kode: {{ $unit->serial_number ?? $unit->id }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="condition" class="form-label">Kondisi Barang Saat Dikembalikan</label>
            <input type="text" name="condition" class="form-control" value="{{ old('condition') }}" required>
        </div>

        <button type="submit" class="btn btn-success">Simpan Detail</button>
    </form>
</div>
@endsection
