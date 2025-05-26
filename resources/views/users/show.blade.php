@extends('layouts.app')

@section('title', 'SISFO Sarpras - Detail Pengguna')

@section('heading')
    <a href="{{ route('users.index') }}">
        <i class="fas fa-users ml-2 mr-1 text-indigo-300"></i>
        Pengguna
    </a>
@endsection

@section('subheading', ' / Detail Pengguna ' . ucfirst($user->username))

@section('content')
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
            <div>
                <a href="{{ route('users.index') }}"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 mb-4">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
                <h1 class="text-2xl font-semibold text-gray-800">Detail Pengguna</h1>
                <p class="text-sm text-gray-500">Informasi lengkap dan riwayat peminjaman pengguna sistem</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('users.edit', $user->id) }}"
                    class="inline-flex items-center text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-4 py-2 rounded-lg">
                    <i class="fas fa-edit mr-2"></i> Edit
                </a>
            </div>
        </div>

        <!-- User Profile Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- User Card -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-user-circle mr-2 text-blue-600"></i>
                            Informasi Pengguna
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6 mb-6">
                            <div class="relative">
                                <div class="w-20 h-20 bg-indigo-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-user text-indigo-600 text-3xl"></i>
                                </div>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                                <p class="text-gray-600">{{ $user->email }}</p>
                                <div class="mt-2">
                                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                                <h3 class="text-sm font-medium text-gray-500">Terakhir Login</h3>
                                <p class="mt-1 text-gray-900">
                                    {{ $user->last_login_at ? $user->last_login_at->format('d M Y H:i') : 'Belum login' }}
                                </p>
                            </div>
                            <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                                <h3 class="text-sm font-medium text-gray-500">Status Akun</h3>
                                <p class="mt-1 text-green-600 flex items-center">
                                    <i class="fas fa-circle text-xs mr-2"></i> Aktif
                                </p>
                            </div>
                            <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                                <h3 class="text-sm font-medium text-gray-500">Bergabung Pada</h3>
                                <p class="mt-1 text-gray-900">
                                    {{ $user->created_at->format('d M Y') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Card -->
            <div>
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm h-full">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-chart-bar mr-2 text-indigo-600"></i>
                            Statistik Pengguna
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Total Peminjaman</h3>
                                <p class="text-xl font-semibold mt-1">{{ $totalBorrowCount }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Total Barang Dipinjam</h3>
                                <p class="text-xl font-semibold mt-1">{{ $totalItemBorrowed }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Total Barang Dikembalikan</h3>
                                <p class="text-xl font-semibold mt-1">{{ $totalItemReturned }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Borrowing History -->
        <div class="space-y-6">
            <!-- Active Borrows -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200 bg-blue-50">
                    <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-clock mr-2 text-blue-600"></i>
                        Sedang Dipinjam
                    </h2>
                </div>
                <div class="p-6">
                    @forelse ($activeBorrows as $borrow)
                        <div class="mb-6 pb-6 border-b border-gray-200 last:border-0 last:mb-0 last:pb-0">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500">ID Peminjaman</h3>
                                    <p class="text-lg font-semibold text-gray-800">#{{ $borrow->id }}</p>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Aktif
                                </span>
                            </div>
                            <div class="space-y-3">
                                @foreach ($borrow->borrowDetail as $detail)
                                    <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                                        <div class="bg-blue-100 p-2 rounded-lg mr-4">
                                            <i class="fas fa-box text-blue-600"></i>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-800">{{ $detail->itemUnit->item->name }}</h4>
                                            <p class="text-sm text-gray-500">SKU: {{ $detail->itemUnit->sku }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-xs text-gray-500">Dipinjam pada</p>
                                            <p class="text-sm text-gray-900">{{ $borrow->created_at->format('d M Y') }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <i class="fas fa-box-open text-gray-400 text-4xl mb-3"></i>
                            <h3 class="text-lg font-medium text-gray-700">Tidak ada peminjaman aktif</h3>
                            <p class="text-sm text-gray-500">Pengguna ini tidak memiliki item yang sedang dipinjam</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Returned Borrows -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200 bg-green-50">
                    <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-check-circle mr-2 text-green-600"></i>
                        Riwayat Pengembalian
                    </h2>
                </div>
                <div class="p-6">
                    @forelse ($returnedBorrows as $borrow)
                        <div class="mb-6 pb-6 border-b border-gray-200 last:border-0 last:mb-0 last:pb-0">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500">ID Peminjaman</h3>
                                    <p class="text-lg font-semibold text-gray-800">#{{ $borrow->id }}</p>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Dikembalikan
                                </span>
                            </div>
                            <div class="space-y-3">
                                @foreach ($borrow->details as $detail)
                                    <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                                        <div class="bg-green-100 p-2 rounded-lg mr-4">
                                            <i class="fas fa-check text-green-600"></i>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-800">{{ $detail->itemUnit->item->name }}</h4>
                                            <p class="text-sm text-gray-500">Serial:
                                                {{ $detail->itemUnit->serial_number ?? '-' }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-xs text-gray-500">Dikembalikan pada</p>
                                            <p class="text-sm text-gray-900">{{ $borrow->updated_at->format('d M Y') }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <i class="fas fa-history text-gray-400 text-4xl mb-3"></i>
                            <h3 class="text-lg font-medium text-gray-700">Belum ada pengembalian</h3>
                            <p class="text-sm text-gray-500">Pengguna ini belum mengembalikan item apapun</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
