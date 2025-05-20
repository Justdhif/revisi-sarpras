<div class="sidebar-container w-64 sidebar text-white fixed left-0 top-0 z-50 h-full">
    <!-- Logo Section -->
    <div class="p-6 flex items-center justify-center border-b border-slate-700 glass">
        <img src="{{ asset('images/logo_sarpras.jpg') }}" alt="SarprasPro Logo" class="h-12 w-auto rounded-lg shadow-md">
        <h1 class="ml-3 text-xl font-bold tracking-wider text-white">
            <span
                class="bg-clip-text text-transparent bg-gradient-to-r from-indigo-200 to-indigo-400">Sarpras</span><span
                class="text-indigo-300">TB</span>
        </h1>
    </div>

    <!-- Navigation Menu -->
    <div class="scrollable-nav">
        <!-- Dashboard Link -->
        <a href="{{ route('dashboard') }}"
            class="nav-item flex items-center px-4 py-3 rounded-lg font-medium {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-home mr-3 text-indigo-300"></i> Dashboard
        </a>

        <!-- Home Link -->
        <a href="{{ route('home') }}"
            class="nav-item flex items-center px-4 py-3 rounded-lg font-medium {{ request()->routeIs('home') ? 'active' : '' }}">
            <i class="fas fa-home mr-3 text-indigo-300"></i> Beranda
        </a>

        <!-- Master Data Section -->
        <div class="pt-2">
            <p class="px-4 py-2 text-xs font-semibold text-indigo-300 uppercase tracking-wider">Master Data</p>
            <div class="space-y-1">
                <a href="{{ route('categories.index') }}"
                    class="nav-item flex items-center px-4 py-3 rounded-lg font-medium {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                    <i class="fas fa-tags mr-3 text-indigo-300"></i> Kategori
                </a>
                <a href="{{ route('warehouses.index') }}"
                    class="nav-item flex items-center px-4 py-3 rounded-lg font-medium {{ request()->routeIs('warehouses.*') ? 'active' : '' }}">
                    <i class="fas fa-warehouse mr-3 text-indigo-300"></i> Gudang
                </a>
            </div>
        </div>

        <!-- Inventory Management Section -->
        <div class="pt-2">
            <p class="px-4 py-2 text-xs font-semibold text-indigo-300 uppercase tracking-wider">Manajemen Inventori</p>
            <div x-data="{ itemsOpen: {{ request()->routeIs('items.*', 'item-units.*') ? 'true' : 'false' }} }">
                <button @click="itemsOpen = !itemsOpen"
                    class="nav-item flex items-center justify-between w-full px-4 py-3 rounded-lg font-medium {{ request()->routeIs('items.*') ? 'active' : '' }}">
                    <span class="flex items-center">
                        <i class="fas fa-box-open mr-3 text-indigo-300"></i> Barang
                    </span>
                    <i
                        :class="itemsOpen ? 'fas fa-chevron-up text-indigo-300' : 'fas fa-chevron-down text-indigo-300'"></i>
                </button>
                <div x-show="itemsOpen" class="ml-8 mt-1 space-y-1" x-cloak>
                    <a href="{{ route('items.index') }}"
                        class="submenu-item block px-3 py-2 rounded text-sm {{ request()->routeIs('items.*') ? 'active' : '' }}">
                        <i class="fas fa-box mr-2 text-indigo-300"></i> Daftar Barang
                    </a>
                    <a href="{{ route('item-units.index') }}"
                        class="submenu-item block px-3 py-2 rounded text-sm {{ request()->routeIs('item-units.*') ? 'active' : '' }}">
                        <i class="fas fa-boxes mr-2 text-indigo-300"></i> Daftar Unit
                    </a>
                </div>
            </div>
        </div>

        <!-- Transaction Section -->
        <div class="pt-2">
            <p class="px-4 py-2 text-xs font-semibold text-indigo-300 uppercase tracking-wider">Transaksi</p>
            <div class="space-y-1">
                <a href="{{ route('borrow-requests.index') }}"
                    class="nav-item flex items-center px-4 py-3 rounded-lg font-medium {{ request()->routeIs('borrow-requests.*') ? 'active' : '' }}">
                    <i class="fas fa-redo-alt mr-3 text-indigo-300"></i> Peminjaman
                </a>
                <a href="{{ route('return-requests.index') }}"
                    class="nav-item flex items-center px-4 py-3 rounded-lg font-medium {{ request()->routeIs('return-requests.index') ? 'active' : '' }}">
                    <i class="fas fa-undo-alt mr-3 text-indigo-300"></i> Pengembalian
                </a>
            </div>
        </div>

        <!-- Users Section -->
        <div class="pt-2">
            <p class="px-4 py-2 text-xs font-semibold text-indigo-300 uppercase tracking-wider">Pengguna</p>
            <div class="space-y-1">
                <a href="{{ route('users.index') }}"
                    class="nav-item flex items-center px-4 py-3 rounded-lg font-medium {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <i class="fas fa-users mr-3 text-indigo-300"></i> Pengguna
                </a>
            </div>
        </div>

        <!-- System Section -->
        <div class="pt-2">
            <p class="px-4 py-2 text-xs font-semibold text-indigo-300 uppercase tracking-wider">Sistem</p>
            <div class="space-y-1">
                <a href="{{ route('activity-logs.index') }}"
                    class="nav-item flex items-center px-4 py-3 rounded-lg font-medium {{ request()->routeIs('activity-logs.index') ? 'active' : '' }}">
                    <i class="fas fa-history mr-3 text-indigo-300"></i> Log Aktivitas
                </a>
                <div x-data="{ archiveOpen: {{ request()->routeIs('archive.*') ? 'true' : 'false' }} }">
                    <button @click="archiveOpen = !archiveOpen"
                        class="nav-item flex items-center justify-between w-full px-4 py-3 rounded-lg font-medium {{ request()->routeIs('archive.*') ? 'active' : '' }}">
                        <span class="flex items-center">
                            <i class="fas fa-archive mr-3 text-indigo-300"></i> Archive
                        </span>
                        <i
                            :class="archiveOpen ? 'fas fa-chevron-up text-indigo-300' : 'fas fa-chevron-down text-indigo-300'"></i>
                    </button>
                    <div x-show="archiveOpen" class="ml-8 mt-1 space-y-1" x-cloak>
                        <a href="{{ route('archive.users') }}"
                            class="submenu-item block px-3 py-2 rounded text-sm {{ request()->routeIs('archive.users') ? 'active' : '' }}">
                            <i class="fas fa-user-friends mr-3 text-indigo-300"></i> Arsip Pengguna
                        </a>
                        <a href="{{ route('archive.items') }}"
                            class="submenu-item block px-3 py-2 rounded text-sm {{ request()->routeIs('archive.items') ? 'active' : '' }}">
                            <i class="fas fa-file-import mr-3 text-indigo-300"></i> Arsip Barang
                        </a>
                        <a href="{{ route('archive.item-units') }}"
                            class="submenu-item block px-3 py-2 rounded text-sm {{ request()->routeIs('archive.item-units') ? 'active' : '' }}">
                            <i class="fas fa-boxes mr-3 text-indigo-300"></i> Arsip Unit
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Profile & Logout Section -->
    <div class="sidebar-footer glass">
        <div class="flex items-center mb-4">
            <div
                class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center text-white shadow-md">
                <i class="fas fa-user-tie"></i>
            </div>
            <div class="ml-3">
                <p class="font-medium text-white">{{ Auth::user()->username }}</p>
                <p class="text-xs text-indigo-200">Administrator</p>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                class="w-full bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white py-2 px-4 rounded-lg transition-all duration-300 shadow-md hover:shadow-lg flex items-center justify-center">
                <i class="fas fa-sign-out-alt mr-2"></i> Keluar
            </button>
        </form>
    </div>
</div>
