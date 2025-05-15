@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Arsip Barang Terhapus</h3>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Lokasi</th>
                    <th>Dihapus pada</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->category->name ?? '-' }}</td>
                        <td>{{ $item->location->name ?? '-' }}</td>
                        <td>{{ $item->deleted_at->format('d-m-Y H:i') }}</td>
                        <td>
                            <form action="{{ route('archive.items.restore', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-success btn-sm">Pulihkan</button>
                            </form>

                            <form action="{{ route('archive.items.forceDelete', $item->id) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Yakin hapus permanen?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">Hapus Permanen</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">Tidak ada data terhapus.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
