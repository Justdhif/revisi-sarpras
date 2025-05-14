@extends('layouts.app')

@section('title', 'SISFO Sarpras - Detail Pengguna')

@section('heading', 'Detail Pengguna')

@section('content')
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 gap-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Detail Pengguna</h1>
                    <p class="text-gray-500 mt-2 font-light">Informasi lengkap dan riwayat peminjaman pengguna sistem</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                    <a href="{{ route('users.edit', $user->id) }}"
                        class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 ease-in-out shadow-lg hover:shadow-xl font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path
                                d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                        </svg>
                        Edit Profil
                    </a>
                    <a href="{{ route('users.index') }}"
                        class="inline-flex items-center px-5 py-2.5 border border-gray-200 bg-white text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-300 ease-in-out shadow-sm hover:shadow-md font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                                clip-rule="evenodd" />
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>

            <!-- User Profile Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
                <!-- User Card -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                        <div class="p-8">
                            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-8 mb-8">
                                <div class="relative">
                                    <div
                                        class="w-24 h-24 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <span class="absolute -bottom-2 -right-2 bg-white rounded-full shadow-md p-1.5">
                                        <span
                                            class="block w-6 h-6 rounded-full bg-gradient-to-r from-green-400 to-blue-500 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    </span>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
                                    <p class="text-gray-600 font-light">{{ $user->email }}</p>
                                    <div class="mt-3">
                                        <span
                                            class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-semibold tracking-wide uppercase bg-indigo-100 text-indigo-700 border border-indigo-200">
                                            {{ $user->role }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                                <div
                                    class="bg-gradient-to-br from-gray-50 to-white p-5 rounded-xl border border-gray-100 shadow-sm">
                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Terakhir Login
                                    </p>
                                    <p class="mt-2 font-medium text-gray-800">
                                        {{ $user->last_login_at ? $user->last_login_at->format('d M Y H:i') : 'Belum pernah login' }}
                                    </p>
                                </div>
                                <div
                                    class="bg-gradient-to-br from-gray-50 to-white p-5 rounded-xl border border-gray-100 shadow-sm">
                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Status Akun</p>
                                    <p class="mt-2 font-medium text-green-600 flex items-center">
                                        <span class="w-2.5 h-2.5 bg-green-500 rounded-full mr-2"></span>
                                        Aktif
                                    </p>
                                </div>
                                <div
                                    class="bg-gradient-to-br from-gray-50 to-white p-5 rounded-xl border border-gray-100 shadow-sm">
                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Bergabung Pada
                                    </p>
                                    <p class="mt-2 font-medium text-gray-800">
                                        {{ $user->created_at->format('d M Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Card -->
                <div>
                    <div
                        class="bg-gradient-to-br from-indigo-600 to-purple-600 rounded-2xl shadow-xl overflow-hidden h-full">
                        <div class="p-8 text-white">
                            <h3 class="text-lg font-semibold mb-6 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                Statistik Pengguna
                            </h3>
                            <div class="space-y-5">
                                <div>
                                    <p class="text-sm font-light text-indigo-100">Total Peminjaman</p>
                                    <p class="text-2xl font-bold mt-1">{{ $totalBorrowCount }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-light text-indigo-100">Total Barang Dipinjam</p>
                                    <p class="text-2xl font-bold mt-1">{{ $totalItemBorrowed }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-light text-indigo-100">Total Barang Dikembalikan</p>
                                    <p class="text-2xl font-bold mt-1">{{ $totalItemReturned }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Borrowing History -->
            <div class="space-y-8">
                <!-- Active Borrows -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-5">
                        <h3 class="text-lg font-semibold text-white flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Sedang Dipinjam
                        </h3>
                    </div>
                    <div class="p-8">
                        @forelse ($activeBorrows as $borrow)
                            <div class="mb-8 last:mb-0 pb-8 border-b border-gray-100 last:border-0">
                                <div
                                    class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                                    <div>
                                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">ID
                                            Peminjaman</p>
                                        <p class="text-lg font-bold text-gray-800">#{{ $borrow->id }}</p>
                                    </div>
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold tracking-wide uppercase bg-blue-100 text-blue-800 border border-blue-200">
                                        <span class="w-2 h-2 bg-blue-600 rounded-full mr-2"></span>
                                        Aktif
                                    </span>
                                </div>
                                <ul class="space-y-4">
                                    @foreach ($borrow->borrowDetail as $detail)
                                        <li
                                            class="flex flex-col sm:flex-row items-start sm:items-center gap-5 p-4 bg-gray-50 rounded-xl border border-gray-100 hover:bg-gray-100 transition-all duration-200">
                                            <div class="bg-blue-100 p-3 rounded-lg flex-shrink-0">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="font-semibold text-gray-800 truncate">
                                                    {{ $detail->itemUnit->item->name }}</p>
                                                <p class="text-sm text-gray-500 font-light">SKU:
                                                    {{ $detail->itemUnit->sku ?? 'N/A' }}
                                                </p>
                                            </div>
                                            <div class="text-right sm:text-left">
                                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                                    Dipinjam pada</p>
                                                <p class="text-gray-700 font-medium">
                                                    {{ $borrow->created_at->format('d M Y') }}</p>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <div
                                    class="mx-auto w-20 h-20 bg-gray-100 rounded-2xl flex items-center justify-center mb-6 shadow-inner">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-800 mb-3">Tidak ada peminjaman aktif</h3>
                                <p class="text-gray-500 font-light max-w-md mx-auto">Pengguna ini tidak memiliki item yang
                                    sedang dipinjam saat ini.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Returned Borrows -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                    <div class="bg-gradient-to-r from-green-600 to-teal-500 px-8 py-5">
                        <h3 class="text-lg font-semibold text-white flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Riwayat Pengembalian
                        </h3>
                    </div>
                    <div class="p-8">
                        @forelse ($returnedBorrows as $borrow)
                            <div class="mb-8 last:mb-0 pb-8 border-b border-gray-100 last:border-0">
                                <div
                                    class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                                    <div>
                                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">ID
                                            Peminjaman</p>
                                        <p class="text-lg font-bold text-gray-800">#{{ $borrow->id }}</p>
                                    </div>
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold tracking-wide uppercase bg-green-100 text-green-800 border border-green-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Dikembalikan
                                    </span>
                                </div>
                                <ul class="space-y-4">
                                    @foreach ($borrow->details as $detail)
                                        <li
                                            class="flex flex-col sm:flex-row items-start sm:items-center gap-5 p-4 bg-gray-50 rounded-xl border border-gray-100 hover:bg-gray-100 transition-all duration-200">
                                            <div class="bg-green-100 p-3 rounded-lg flex-shrink-0">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="font-semibold text-gray-800 truncate">
                                                    {{ $detail->itemUnit->item->name }}</p>
                                                <p class="text-sm text-gray-500 font-light">Serial:
                                                    {{ $detail->itemUnit->serial_number ?? 'N/A' }}</p>
                                            </div>
                                            <div class="text-right sm:text-left">
                                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                                    Dikembalikan pada</p>
                                                <p class="text-gray-700 font-medium">
                                                    {{ $borrow->updated_at->format('d M Y') }}</p>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <div
                                    class="mx-auto w-20 h-20 bg-gray-100 rounded-2xl flex items-center justify-center mb-6 shadow-inner">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-800 mb-3">Belum ada pengembalian</h3>
                                <p class="text-gray-500 font-light max-w-md mx-auto">Pengguna ini belum mengembalikan item
                                    apapun.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
