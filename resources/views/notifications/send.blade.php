@extends('layouts.app')

@section('title', 'Kirim Notifikasi')

@section('content')
    <div class="max-w-xl mx-auto p-6">
        <h2 class="text-2xl font-bold mb-4">Kirim Notifikasi</h2>

        <form action="{{ route('notifications.send') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block font-semibold">Tujuan</label>
                <select name="user_id" class="w-full border rounded px-3 py-2">
                    <option value="all">Semua Pengguna</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->role }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-semibold">Judul</label>
                <input type="text" name="title" class="w-full border rounded px-3 py-2" required>
            </div>

            <div>
                <label class="block font-semibold">Pesan</label>
                <textarea name="message" class="w-full border rounded px-3 py-2" rows="4" required></textarea>
            </div>

            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition">
                Kirim Notifikasi
            </button>
        </form>
    </div>
@endsection
