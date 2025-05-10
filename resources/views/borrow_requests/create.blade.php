@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Buat Borrow Request</h3>

        <form method="POST" action="{{ route('borrow-requests.store') }}">
            @csrf
            <div class="mb-3">
                <label for="return_date_expected">Tanggal Pengembalian</label>
                <input type="date" name="return_date_expected" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="notes">Catatan</label>
                <textarea name="notes" class="form-control"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Kirim Request</button>
        </form>
    </div>
@endsection
