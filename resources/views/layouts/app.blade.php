<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SISFO Sarpras - Taruna Bhakti')</title>
    <link rel="icon" href="{{ asset('images/logo_sarpras.jpg') }}" type="image/x-icon">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#4F46E5',
                        secondary: '#6366F1',
                        accent: '#8B5CF6',
                        dark: '#0f172a',
                        light: '#F8FAFC',
                        success: '#10B981',
                        warning: '#F59E0B',
                        danger: '#EF4444',
                    },
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        /* Custom styles */
        .nav-item {
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-item.active {
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.9) 0%, rgba(139, 92, 246, 0.9) 100%);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background-color: white;
            border-radius: 0 4px 4px 0;
        }

        .nav-item:not(.active):hover {
            background-color: rgba(79, 70, 229, 0.7);
            transform: translateX(4px);
        }

        .submenu-item {
            transition: all 0.2s ease;
        }

        .submenu-item.active {
            background-color: rgba(79, 70, 229, 0.3);
            font-weight: 500;
        }

        .submenu-item:not(.active):hover {
            background-color: rgba(79, 70, 229, 0.2);
        }

        /* Scrollable navigation area */
        .sidebar-container {
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        .scrollable-nav {
            flex: 1;
            overflow-y: auto;
            padding: 1rem;
        }

        /* Custom scrollbar */
        .scrollable-nav::-webkit-scrollbar {
            width: 6px;
        }

        .scrollable-nav::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        .scrollable-nav::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }

        .scrollable-nav::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        /* Fixed footer at bottom */
        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            background: linear-gradient(to bottom, #003366, #002244);
        }
    </style>
</head>

<body class="bg-light font-sans antialiased min-h-screen flex">
    <!-- Sidebar Container -->
    <div
        class="sidebar-container w-64 bg-gradient-to-t from-[#004b8d] to-[#003366] text-white shadow-xl fixed left-0 top-0 z-50">
        <!-- Logo -->
        <div class="p-6 flex items-center justify-center border-b border-slate-700">
            <img src="{{ asset('images/logo_sarpras.jpg') }}" alt="SarprasPro Logo" class="h-10 w-auto">
            <h1 class="ml-3 text-xl font-semibold tracking-widest text-white">
                Sarpras<span class="text-indigo-300">TB</span>
            </h1>
        </div>

        <!-- Scrollable Navigation -->
        <div class="scrollable-nav">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}"
                class="nav-item flex items-center px-4 py-3 rounded-lg font-medium {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-home mr-3"></i> Dashboard
            </a>

            <!-- Master Data Section -->
            <div class="pt-2">
                <p class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Master Data</p>
                <div class="space-y-1">
                    <a href="{{ route('categories.index') }}"
                        class="nav-item flex items-center px-4 py-3 rounded-lg font-medium {{ request()->routeIs('categories.index', 'categories.create', 'categories.edit', 'categories.show') ? 'active' : '' }}">
                        <i class="fas fa-tags mr-3"></i> Kategori
                    </a>
                    <a href="{{ route('warehouses.index') }}"
                        class="nav-item flex items-center px-4 py-3 rounded-lg font-medium {{ request()->routeIs('warehouses.index', 'warehouses.create', 'warehouses.edit', 'warehouses.show') ? 'active' : '' }}">
                        <i class="fas fa-warehouse mr-3"></i> Warehouse
                    </a>
                </div>
            </div>

            <!-- Inventory Management Section -->
            <div class="pt-2">
                <p class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Inventory</p>
                <div x-data="{ itemsOpen: {{ request()->routeIs('items.index') || request()->routeIs('items.create', 'items.edit', 'items.show') ? 'true' : 'false' }} }">
                    <button @click="itemsOpen = !itemsOpen"
                        class="nav-item flex items-center justify-between w-full px-4 py-3 rounded-lg font-medium {{ request()->routeIs('items.index', 'items.edit', 'items.show') || request()->routeIs('items.create') ? 'active' : '' }}">
                        <span class="flex items-center">
                            <i class="fas fa-box-open mr-3"></i> Items
                        </span>
                        <i :class="itemsOpen ? 'fas fa-chevron-up' : 'fas fa-chevron-down'"></i>
                    </button>
                    <div x-show="itemsOpen" class="ml-8 mt-1 space-y-1" x-cloak>
                        <a href="{{ route('items.index') }}"
                            class="submenu-item block px-3 py-2 rounded text-sm {{ request()->routeIs('items.index') ? 'active' : '' }}">
                            <i class="fas fa-list mr-2"></i> List Items
                        </a>
                        <a href="{{ route('items.create') }}"
                            class="submenu-item block px-3 py-2 rounded text-sm {{ request()->routeIs('items.create') ? 'active' : '' }}">
                            <i class="fas fa-plus mr-2"></i> Add Item
                        </a>
                    </div>
                </div>
                <div x-data="{ unitOpen: {{ request()->routeIs('item-units.index', 'item-units.create', 'item-units.edit', 'item-units.show') ? 'true' : 'false' }} }">
                    <button @click="unitOpen = !unitOpen"
                        class="nav-item flex items-center justify-between w-full px-4 py-3 rounded-lg font-medium {{ request()->routeIs('item-units.index', 'item-units.edit', 'item-units.show') || request()->routeIs('item-units.create') ? 'active' : '' }}">
                        <span class="flex items-center">
                            <i class="fas fa-ruler-combined mr-3"></i> Unit
                        </span>
                        <i :class="unitOpen ? 'fas fa-chevron-up' : 'fas fa-chevron-down'"></i>
                    </button>
                    <div x-show="unitOpen" class="ml-8 mt-1 space-y-1" x-cloak>
                        <a href="{{ route('item-units.index') }}"
                            class="submenu-item block px-3 py-2 rounded text-sm {{ request()->routeIs('item-units.index', 'item-units.show') ? 'active' : '' }}">
                            <i class="fas fa-list mr-2"></i> Daftar Unit
                        </a>
                        <a href="{{ route('item-units.create') }}"
                            class="submenu-item block px-3 py-2 rounded text-sm {{ request()->routeIs('item-units.create') ? 'active' : '' }}">
                            <i class="fas fa-plus mr-2"></i> Tambah Unit
                        </a>
                    </div>
                </div>
            </div>

            <!-- Transaction Section -->
            <div class="pt-2">
                <p class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Transactions</p>
                <div class="space-y-1">
                    <div x-data="{ borrowOpen: {{ request()->routeIs('borrow-requests.index') || request()->routeIs('borrow-details.index') ? 'true' : 'false' }} }">
                        <button @click="borrowOpen = !borrowOpen"
                            class="nav-item flex items-center justify-between w-full px-4 py-3 rounded-lg font-medium {{ request()->routeIs('borrow-requests.index') || request()->routeIs('borrow-details.index') ? 'active' : '' }}">
                            <span class="flex items-center">
                                <i class="fas fa-hand-holding mr-3"></i> Borrow
                            </span>
                            <i :class="borrowOpen ? 'fas fa-chevron-up' : 'fas fa-chevron-down'"></i>
                        </button>
                        <div x-show="borrowOpen" class="ml-8 mt-1 space-y-1" x-cloak>
                            <a href="{{ route('borrow-requests.index') }}"
                                class="submenu-item block px-3 py-2 rounded text-sm {{ request()->routeIs('borrow-requests.index') ? 'active' : '' }}">
                                <i class="fas fa-inbox mr-2"></i> Borrow Requests
                            </a>
                            <a href="{{ route('borrow-details.index') }}"
                                class="submenu-item block px-3 py-2 rounded text-sm {{ request()->routeIs('borrow-details.index') ? 'active' : '' }}">
                                <i class="fas fa-info-circle mr-2"></i> Borrow Detail
                            </a>
                        </div>
                    </div>
                    <a href="{{ route('return-requests.index') }}"
                        class="nav-item flex items-center px-4 py-3 rounded-lg font-medium {{ request()->routeIs('return-requests.index') ? 'active' : '' }}">
                        <i class="fas fa-undo-alt mr-3"></i> Return
                    </a>
                </div>
            </div>

            <!-- System Section -->
            <div class="pt-2">
                <p class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">System</p>
                <div class="space-y-1">
                    <div x-data="{ usersOpen: {{ request()->routeIs('users.index') || request()->routeIs('users.create') ? 'true' : 'false' }} }">
                        <button @click="usersOpen = !usersOpen"
                            class="nav-item flex items-center justify-between w-full px-4 py-3 rounded-lg font-medium {{ request()->routeIs('users.index') || request()->routeIs('users.create') ? 'active' : '' }}">
                            <span class="flex items-center">
                                <i class="fas fa-users mr-3"></i> Users
                            </span>
                            <i :class="usersOpen ? 'fas fa-chevron-up' : 'fas fa-chevron-down'"></i>
                        </button>
                        <div x-show="usersOpen" class="ml-8 mt-1 space-y-1" x-cloak>
                            <a href="{{ route('users.index') }}"
                                class="submenu-item block px-3 py-2 rounded text-sm {{ request()->routeIs('users.index') ? 'active' : '' }}">
                                <i class="fas fa-list mr-2"></i> Daftar User
                            </a>
                            <a href="{{ route('users.create') }}"
                                class="submenu-item block px-3 py-2 rounded text-sm {{ request()->routeIs('users.create') ? 'active' : '' }}">
                                <i class="fas fa-user-plus mr-2"></i> Tambah User
                            </a>
                        </div>
                    </div>
                    <a href="{{ route('activity-logs.index') }}"
                        class="nav-item flex items-center px-4 py-3 rounded-lg font-medium {{ request()->routeIs('activity-logs.index') ? 'active' : '' }}">
                        <i class="fas fa-history mr-3"></i> Activity Logs
                    </a>
                </div>
            </div>
        </div>

        <!-- Fixed User & Logout at bottom -->
        <div class="sidebar-footer">
            <div class="flex items-center mb-4">
                <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-white">
                    <i class="fas fa-user"></i>
                </div>
                <div class="ml-3">
                    <p class="font-medium">{{ Auth::user()->username }}</p>
                    <p class="text-sm text-gray-300">Administrator</p>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="w-full bg-red-600 hover:bg-red-500 text-white py-2 px-4 rounded-lg transition-colors duration-300">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden ml-64">
        <!-- Alert -->
        <main class="flex-1 overflow-y-auto p-6">
            @if (session('success'))
                <div
                    class="bg-emerald-100 border-l-4 border-emerald-500 text-emerald-700 p-4 mb-6 rounded-lg shadow-sm">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>

</html>
