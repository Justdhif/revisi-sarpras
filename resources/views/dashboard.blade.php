@extends('layouts.app')

@section('content')
    <div class="min-h-screen">
        <!-- Main Content Container -->
        <div class="max-w-7xl mx-auto px-4 py-8">
            <!-- Dashboard Header -->
            <div class="mb-8">
                <h1 class="text-2xl font-semibold text-gray-900">Dashboard Admin</h1>
                <p class="mt-1 text-sm text-gray-500">Overview of system statistics and recent activities</p>
            </div>

            <!-- Modern Square Stat Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <x-dashboard.stat-card title="Total User" :value="$totalUsers" :trend="$totalUsersTrend" trendColor="blue"
                    icon='
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    ' />

                <x-dashboard.stat-card title="Total Barang" :value="$totalItems" :trend="$totalItemsTrend" trendColor="emerald"
                    icon='
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                    ' />

                <x-dashboard.stat-card title="Peminjaman" :value="$totalBorrows" :trend="$totalBorrowsTrend" trendColor="amber"
                    icon='
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    ' />

                <x-dashboard.stat-card title="Pengembalian" :value="$totalReturns" :trend="$totalReturnsTrend" trendColor="rose"
                    icon='
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    ' />
            </div>

            <!-- Main Content Area -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-6">
                    <!-- Recent Activity Logs -->
                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
                        <x-dashboard.section-header title="Log Aktivitas Terbaru" />

                        <div class="divide-y divide-gray-200">
                            @forelse ($recentLogs as $log)
                                <x-dashboard.activity-log-item :user="[
                                    'username' => $log->user->username ?? null,
                                    'email' => $log->user->email ?? null,
                                ]" :description="Str::limit($log->description, 50)" :logName="$log->log_name"
                                    :time="$log->created_at->diffForHumans()" />
                            @empty
                                <x-dashboard.empty-state
                                    icon='
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    '
                                    title="No activity logs found" description="User activities will appear here" />
                            @endforelse
                        </div>
                    </div>

                    <!-- Recent Item Units -->
                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
                        <x-dashboard.section-header title="Unit Barang Terbaru" />

                        <div class="divide-y divide-gray-200">
                            @forelse ($recentItemUnits as $unit)
                                <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center gap-4">
                                        <div class="flex-shrink-0">
                                            <div class="w-12 h-12 bg-gray-100 rounded-md flex items-center justify-center">
                                                {!! QrCode::format('svg')->size(80)->generate($unit->sku) !!}
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between">
                                                <p class="text-sm font-medium text-gray-900 truncate">
                                                    {{ $unit->item->name ?? '-' }}</p>
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    {{ $unit->status == 'available' ? 'bg-green-100 text-green-800' : '' }}
                                                    {{ $unit->status == 'borrowed' ? 'bg-amber-100 text-amber-800' : '' }}
                                                    {{ !in_array($unit->status, ['available', 'borrowed']) ? 'bg-gray-100 text-gray-800' : '' }}">
                                                    {{ $unit->status == 'available' ? 'Tersedia' : ($unit->status == 'borrowed' ? 'Dipinjam' : $unit->status) }}
                                                </span>
                                            </div>
                                            <p class="text-sm text-gray-500 mt-1">
                                                {{ $unit->item->category->name ?? 'No category' }}</p>
                                            <div class="mt-2">
                                                <span
                                                    class="font-mono text-xs bg-gray-100 px-2 py-1 rounded">{{ $unit->sku }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <x-dashboard.empty-state
                                    icon='
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                    '
                                    title="No item units found" description="Add new items to see them here" />
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Recent Borrowings -->
                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
                        <x-dashboard.section-header title="Peminjaman Terbaru" />

                        <div class="divide-y divide-gray-200">
                            @forelse ($recentBorrows as $borrow)
                                <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-start gap-4">
                                        <div class="flex-shrink-0">
                                            <div
                                                class="h-10 w-10 bg-purple-50 rounded-full flex items-center justify-center">
                                                <span
                                                    class="text-purple-600 font-medium">{{ substr($borrow->user->username ?? 'U', 0, 1) }}</span>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between">
                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ $borrow->user->username ?? '-' }}</p>
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    {{ $borrow->status == 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                                    {{ $borrow->status == 'pending' ? 'bg-amber-100 text-amber-800' : '' }}
                                                    {{ $borrow->status == 'rejected' ? 'bg-red-100 text-red-800' : '' }}
                                                    {{ !in_array($borrow->status, ['approved', 'pending', 'rejected']) ? 'bg-gray-100 text-gray-800' : '' }}">
                                                    {{ $borrow->status == 'approved' ? 'Disetujui' : ($borrow->status == 'pending' ? 'Menunggu' : ($borrow->status == 'rejected' ? 'Ditolak' : $borrow->status)) }}
                                                </span>
                                            </div>
                                            <p class="text-sm text-gray-500 mt-1">{{ $borrow->user->email ?? '-' }}</p>
                                            <div class="mt-2 flex items-center justify-between">
                                                <span class="text-xs text-gray-500">{{ $borrow->borrowDetail->count() }}
                                                    items</span>
                                                <time class="text-xs text-gray-500 flex items-center">
                                                    <svg class="w-3 h-3 mr-1 text-gray-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    {{ $borrow->created_at->diffForHumans() }}
                                                </time>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <x-dashboard.empty-state
                                    icon='
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path>
                                        </svg>
                                    '
                                    title="No borrow requests found" description="Borrow requests will appear here" />
                            @endforelse
                        </div>
                    </div>

                    <!-- Recent Returns -->
                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
                        <x-dashboard.section-header title="Pengembalian Terbaru" />

                        <div class="divide-y divide-gray-200">
                            @forelse ($recentReturns as $return)
                                <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-start gap-4">
                                        <div class="flex-shrink-0">
                                            <div
                                                class="h-10 w-10 bg-indigo-50 rounded-full flex items-center justify-center">
                                                <span
                                                    class="text-indigo-600 font-medium">{{ substr($return->borrowRequest->user->username ?? 'U', 0, 1) }}</span>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between">
                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ $return->borrowRequest->user->username ?? '-' }}</p>
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    {{ $return->status == 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                                    {{ $return->status == 'pending' ? 'bg-amber-100 text-amber-800' : '' }}
                                                    {{ !in_array($return->status, ['completed', 'pending']) ? 'bg-gray-100 text-gray-800' : '' }}">
                                                    {{ $return->status == 'completed' ? 'Selesai' : ($return->status == 'pending' ? 'Menunggu' : $return->status) }}
                                                </span>
                                            </div>
                                            <p class="text-sm text-gray-500 mt-1">
                                                {{ $return->borrowRequest->user->email ?? '-' }}</p>
                                            <div class="mt-2 flex items-center justify-between">
                                                <span
                                                    class="text-xs text-gray-500">{{ $return->borrowRequest->borrowDetail->count() }}
                                                    items</span>
                                                <time class="text-xs text-gray-500 flex items-center">
                                                    <svg class="w-3 h-3 mr-1 text-gray-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    {{ $return->created_at->diffForHumans() }}
                                                </time>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <x-dashboard.empty-state
                                    icon='
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                        </svg>
                                    '
                                    title="No returns found" description="Item returns will appear here" />
                            @endforelse
                        </div>
                    </div>

                    <!-- Recent Items -->
                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
                        <x-dashboard.section-header title="Daftar Barang Terbaru" />

                        <div class="divide-y divide-gray-200">
                            @forelse ($recentItems as $item)
                                <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center gap-4">
                                        <div class="flex-shrink-0">
                                            <div class="w-12 h-12 bg-gray-100 rounded-md overflow-hidden">
                                                <img src="{{ $item->image_url }}" alt="{{ $item->name }}"
                                                    class="w-full h-full object-cover">
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between">
                                                <p class="text-sm font-medium text-gray-900 truncate">{{ $item->name }}
                                                </p>
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-600">
                                                    {{ $item->category->name ?? '-' }}
                                                </span>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-1">{{ $item->sku }}</p>
                                            <div class="mt-2">
                                                <time class="text-xs text-gray-500 flex items-center">
                                                    <svg class="w-3 h-3 mr-1 text-gray-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    {{ $item->created_at->diffForHumans() }}
                                                </time>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <x-dashboard.empty-state
                                    icon='
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                    '
                                    title="No items found" description="Add new items to see them here" />
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
