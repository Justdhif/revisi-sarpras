<!-- Sidebar -->
<div class="w-64 h-screen fixed left-0 top-0 z-40 border-r bg-background">
    <!-- Logo Section -->
    <div class="flex h-16 items-center px-6 border-b">
        <img src="{{ asset('images/logo_sarpras.jpg') }}" alt="SarprasPro Logo" class="h-8 w-auto rounded-lg">
        <h1 class="ml-3 text-lg font-semibold">
            <span class="text-primary">Sarpras</span><span class="text-muted-foreground">TB</span>
        </h1>
    </div>

    <div class="sidebar-scrollable h-[calc(100vh-64px)] overflow-y-auto py-4 px-3">
        <!-- Dashboard Link -->
        <div class="space-y-1">
            <a href="{{ route('dashboard') }}"
                class="flex items-center px-3 py-2 text-sm rounded-md font-medium transition-colors
                    {{ request()->routeIs('dashboard') ? 'bg-accent text-accent-foreground' : 'hover:bg-accent/50 hover:text-accent-foreground' }}">
                <i
                    class="fas fa-home w-5 mr-3 {{ request()->routeIs('dashboard') ? 'text-blue-500' : 'text-muted-foreground' }}"></i>
                Dashboard
            </a>
        </div>

        <!-- Master Data Section -->
        <div class="mt-6">
            <h3 class="px-3 mb-2 text-xs font-semibold text-muted-foreground uppercase tracking-wider">Master Data</h3>
            <div class="space-y-1">
                <a href="{{ route('categories.index') }}"
                    class="flex items-center px-3 py-2 text-sm rounded-md font-medium transition-colors
                        {{ request()->routeIs('categories.*') ? 'bg-accent text-accent-foreground' : 'hover:bg-accent/50 hover:text-accent-foreground' }}">
                    <i
                        class="fas fa-tags w-5 mr-3 {{ request()->routeIs('categories.*') ? 'text-purple-500' : 'text-muted-foreground' }}"></i>
                    Kategori
                </a>
                <a href="{{ route('warehouses.index') }}"
                    class="flex items-center px-3 py-2 text-sm rounded-md font-medium transition-colors
                        {{ request()->routeIs('warehouses.*') ? 'bg-accent text-accent-foreground' : 'hover:bg-accent/50 hover:text-accent-foreground' }}">
                    <i
                        class="fas fa-warehouse w-5 mr-3 {{ request()->routeIs('warehouses.*') ? 'text-amber-500' : 'text-muted-foreground' }}"></i>
                    Gudang
                </a>
            </div>
        </div>

        <!-- Inventory Management Section -->
        <div class="mt-6" x-data="{ itemsOpen: {{ request()->routeIs('items.*', 'item-units.*', 'stock_movements.*', 'damaged-items.*') ? 'true' : 'false' }} }">
            <h3 class="px-3 mb-2 text-xs font-semibold text-muted-foreground uppercase tracking-wider">Manajemen
                Inventori</h3>
            <div class="space-y-1">
                <button @click="itemsOpen = !itemsOpen"
                    class="w-full flex items-center justify-between px-3 py-2 text-sm rounded-md font-medium transition-colors
                        {{ request()->routeIs('items.*', 'item-units.*', 'stock_movements.*', 'damaged-items.*') ? 'bg-accent text-accent-foreground' : 'hover:bg-accent/50 hover:text-accent-foreground' }}">
                    <span class="flex items-center">
                        <i
                            class="fas fa-box-open w-5 mr-3 {{ request()->routeIs('items.*', 'item-units.*', 'stock_movements.*', 'damaged-items.*') ? 'text-emerald-500' : 'text-muted-foreground' }}"></i>
                        Barang
                    </span>
                    <i
                        :class="itemsOpen ? 'fas fa-chevron-up text-muted-foreground' :
                            'fas fa-chevron-down text-muted-foreground'"></i>
                </button>

                <div x-show="itemsOpen" x-transition class="ml-4 mt-1 space-y-1">
                    <a href="{{ route('items.index') }}"
                        class="block px-3 py-1.5 text-sm rounded-md font-medium transition-colors
                            {{ request()->routeIs('items.*') ? 'bg-accent text-accent-foreground' : 'hover:bg-accent/50 hover:text-accent-foreground' }}">
                        <i
                            class="fas fa-box w-5 mr-2 {{ request()->routeIs('items.*') ? 'text-emerald-600' : 'text-muted-foreground' }}"></i>
                        Daftar Barang
                    </a>
                    <a href="{{ route('item-units.index') }}"
                        class="block px-3 py-1.5 text-sm rounded-md font-medium transition-colors
                            {{ request()->routeIs('item-units.*') ? 'bg-accent text-accent-foreground' : 'hover:bg-accent/50 hover:text-accent-foreground' }}">
                        <i
                            class="fas fa-boxes w-5 mr-2 {{ request()->routeIs('item-units.*') ? 'text-teal-500' : 'text-muted-foreground' }}"></i>
                        Daftar Unit
                    </a>
                    <a href="{{ route('stock_movements.index') }}"
                        class="block px-3 py-1.5 text-sm rounded-md font-medium transition-colors
                            {{ request()->routeIs('stock_movements.*') ? 'bg-accent text-accent-foreground' : 'hover:bg-accent/50 hover:text-accent-foreground' }}">
                        <i
                            class="fas fa-history w-5 mr-2 {{ request()->routeIs('stock_movements.*') ? 'text-cyan-500' : 'text-muted-foreground' }}"></i>
                        Riwayat Stok
                    </a>
                    <a href="{{ route('damaged-items.index') }}"
                        class="block px-3 py-1.5 text-sm rounded-md font-medium transition-colors
                            {{ request()->routeIs('damaged-items.*') ? 'bg-accent text-accent-foreground' : 'hover:bg-accent/50 hover:text-accent-foreground' }}">
                        <i
                            class="fas fa-bolt w-5 mr-2 {{ request()->routeIs('damaged-items.*') ? 'text-red-500' : 'text-muted-foreground' }}"></i>
                        Barang Rusak
                    </a>
                </div>
            </div>
        </div>

        <!-- Transaction Section -->
        <div class="mt-6">
            <h3 class="px-3 mb-2 text-xs font-semibold text-muted-foreground uppercase tracking-wider">Transaksi</h3>
            <div class="space-y-1">
                <a href="{{ route('borrow-requests.index') }}"
                    class="flex items-center px-3 py-2 text-sm rounded-md font-medium transition-colors
                        {{ request()->routeIs('borrow-requests.*') ? 'bg-accent text-accent-foreground' : 'hover:bg-accent/50 hover:text-accent-foreground' }}">
                    <i
                        class="fas fa-redo-alt w-5 mr-3 {{ request()->routeIs('borrow-requests.*') ? 'text-indigo-500' : 'text-muted-foreground' }}"></i>
                    Peminjaman
                </a>
                <a href="{{ route('return_requests.index') }}"
                    class="flex items-center px-3 py-2 text-sm rounded-md font-medium transition-colors
                        {{ request()->routeIs('return_requests.*') ? 'bg-accent text-accent-foreground' : 'hover:bg-accent/50 hover:text-accent-foreground' }}">
                    <i
                        class="fas fa-undo-alt w-5 mr-3 {{ request()->routeIs('return_requests.*') ? 'text-violet-500' : 'text-muted-foreground' }}"></i>
                    Pengembalian
                </a>
            </div>
        </div>

        <!-- Users Section -->
        <div class="mt-6">
            <h3 class="px-3 mb-2 text-xs font-semibold text-muted-foreground uppercase tracking-wider">Pengguna</h3>
            <div class="space-y-1">
                <a href="{{ route('users.index') }}"
                    class="flex items-center px-3 py-2 text-sm rounded-md font-medium transition-colors
                        {{ request()->routeIs('users.*') ? 'bg-accent text-accent-foreground' : 'hover:bg-accent/50 hover:text-accent-foreground' }}">
                    <i
                        class="fas fa-users w-5 mr-3 {{ request()->routeIs('users.*') ? 'text-pink-500' : 'text-muted-foreground' }}"></i>
                    Pengguna
                </a>
                <a href="{{ route('origins.index') }}"
                    class="flex items-center px-3 py-2 text-sm rounded-md font-medium transition-colors
                        {{ request()->routeIs('origins.*') ? 'bg-accent text-accent-foreground' : 'hover:bg-accent/50 hover:text-accent-foreground' }}">
                    <i
                        class="fas fa-map-marker-alt w-5 mr-3 {{ request()->routeIs('origins.*') ? 'text-rose-500' : 'text-muted-foreground' }}"></i>
                    Asal
                </a>
            </div>
        </div>

        @php
            $unreadCount = auth()->user()->unreadNotifications()->count();
        @endphp

        <!-- System Section -->
        <div class="mt-6">
            <h3 class="px-3 mb-2 text-xs font-semibold text-muted-foreground uppercase tracking-wider">Sistem</h3>
            <div class="space-y-1">
                <a href="{{ route('notifications.index') }}"
                    class="relative flex items-center px-3 py-2 text-sm rounded-md font-medium transition-colors
                {{ request()->routeIs('notifications.*') ? 'bg-accent text-accent-foreground' : 'hover:bg-accent/50 hover:text-accent-foreground' }}">
                    <i
                        class="fas fa-bell w-5 mr-3 {{ request()->routeIs('notifications.*') ? 'text-amber-500' : 'text-muted-foreground' }}"></i>
                    Notifikasi

                    @if ($unreadCount > 0)
                        <span class="absolute left-5 top-1.5">
                            <span class="relative flex h-2 w-2">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-500 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                            </span>
                        </span>
                    @endif
                </a>
                <a href="{{ route('activity-logs.index') }}"
                    class="flex items-center px-3 py-2 text-sm rounded-md font-medium transition-colors
                {{ request()->routeIs('activity-logs.index') ? 'bg-accent text-accent-foreground' : 'hover:bg-accent/50 hover:text-accent-foreground' }}">
                    <i
                        class="fas fa-history w-5 mr-3 {{ request()->routeIs('activity-logs.index') ? 'text-orange-500' : 'text-muted-foreground' }}"></i>
                    Log Aktivitas
                </a>
            </div>
        </div>
    </div>
</div>
