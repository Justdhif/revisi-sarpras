@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Arsip Pengguna Terhapus</h3>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Dihapus pada</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ ucfirst($user->role) }}</td>
                        <td>{{ $user->deleted_at->format('d-m-Y H:i') }}</td>
                        <td>
                            <form action="{{ route('archive.users.restore', $user->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-success btn-sm">Pulihkan</button>
                            </form>

                            <form action="{{ route('archive.users.forceDelete', $user->id) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Yakin hapus permanen?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">Hapus Permanen</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">Tidak ada pengguna terhapus.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
