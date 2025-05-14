<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SISFO Sarpras - Taruna Bhakti')</title>
    <link rel="icon" href="{{ asset('images/logo_sarpras.jpg') }}" type="image/x-icon">

    <!-- CSS & JS Libraries -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Tailwind Configuration -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        },
                        secondary: '#6366F1',
                        accent: '#8B5CF6',
                        dark: '#0f172a',
                        light: '#F8FAFC',
                        success: '#10B981',
                        warning: '#F59E0B',
                        danger: '#EF4444',
                        premium: {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            200: '#e2e8f0',
                            300: '#cbd5e1',
                            400: '#94a3b8',
                            500: '#64748b',
                            600: '#475569',
                            700: '#334155',
                            800: '#1e293b',
                            900: '#0f172a',
                        }
                    },
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    },
                    boxShadow: {
                        'glass': '0 4px 30px rgba(0, 0, 0, 0.1)',
                        'neumorphism': '8px 8px 16px #d1d9e6, -8px -8px 16px #ffffff',
                        'floating': '0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)'
                    },
                    animation: {
                        'slide-in': 'slideIn 0.3s ease-out forwards',
                        'fade-in': 'fadeIn 0.5s ease-in forwards',
                        'bounce-in': 'bounceIn 0.6s ease-out forwards'
                    },
                    keyframes: {
                        slideIn: {
                            '0%': {
                                transform: 'translateY(-20px)',
                                opacity: '0'
                            },
                            '100%': {
                                transform: 'translateY(0)',
                                opacity: '1'
                            }
                        },
                        fadeIn: {
                            '0%': {
                                opacity: '0'
                            },
                            '100%': {
                                opacity: '1'
                            }
                        },
                        bounceIn: {
                            '0%': {
                                transform: 'scale(0.95)',
                                opacity: '0'
                            },
                            '50%': {
                                transform: 'scale(1.05)',
                                opacity: '1'
                            },
                            '100%': {
                                transform: 'scale(1)',
                                opacity: '1'
                            }
                        }
                    }
                }
            }
        }
    </script>

    <!-- Custom Styles -->
    <style>
        /* Base Styles */
        body {
            background-color: #f5f7fa;
            background-image: radial-gradient(circle at 10% 20%, rgba(235, 248, 255, 0.8) 0%, rgba(255, 255, 255, 0.9) 90%);
        }

        /* Sidebar Styles */
        .sidebar {
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
            box-shadow: 5px 0 15px rgba(0, 0, 0, 0.1);
        }

        .nav-item {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            border-radius: 0.5rem;
            margin: 0.25rem 0;
        }

        .nav-item.active {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.9) 0%, rgba(79, 70, 229, 0.9) 100%);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: linear-gradient(to bottom, #a5b4fc, #818cf8);
            border-radius: 0 4px 4px 0;
        }

        .nav-item:not(.active):hover {
            background-color: rgba(99, 102, 241, 0.2);
            transform: translateX(4px);
        }

        /* Submenu Styles */
        .submenu-item {
            transition: all 0.2s ease;
            border-radius: 0.375rem;
        }

        .submenu-item.active {
            background-color: rgba(99, 102, 241, 0.3);
            font-weight: 500;
            color: white;
        }

        .submenu-item:not(.active):hover {
            background-color: rgba(99, 102, 241, 0.15);
        }

        /* Scrollable Navigation */
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

        /* Custom Scrollbar */
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

        /* Sidebar Footer */
        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            background: linear-gradient(to right, #1e3a8a, #1e40af);
        }

        /* Glass Effect */
        .glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        /* Notification Styles - Modern Design */
        .notification {
            position: relative;
            overflow: hidden;
            border-radius: 12px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transform: translateX(0);
            opacity: 1;
            transition: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        .notification.success {
            background: rgba(16, 185, 129, 0.85);
        }

        .notification.error {
            background: rgba(239, 68, 68, 0.85);
        }

        .notification.warning {
            background: rgba(245, 158, 11, 0.85);
        }

        .notification::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0) 100%);
            z-index: -1;
        }

        .notification-close {
            transition: all 0.3s ease;
        }

        .notification-close:hover {
            transform: rotate(90deg);
        }

        /* Card Styles */
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 12px;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px -5px rgba(0, 0, 0, 0.1);
        }

        /* Animation Keyframes */
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes fadeOut {
            to {
                opacity: 0;
            }
        }
    </style>
</head>

<body class="font-sans antialiased min-h-screen flex">
    <!-- Sidebar Container -->
    <div class="sidebar-container w-64 sidebar text-white fixed left-0 top-0 z-50 h-full">
        <!-- Logo Section -->
        <div class="p-6 flex items-center justify-center border-b border-slate-700 glass">
            <img src="{{ asset('images/logo_sarpras.jpg') }}" alt="SarprasPro Logo"
                class="h-12 w-auto rounded-lg shadow-md">
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
                <p class="px-4 py-2 text-xs font-semibold text-indigo-300 uppercase tracking-wider">Manajemen Inventori
                </p>
                <div x-data="{ itemsOpen: {{ request()->routeIs('items.*') ? 'true' : 'false' }} }">
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
                            class="submenu-item block px-3 py-2 rounded text-sm {{ request()->routeIs('items.index') ? 'active' : '' }}">
                            <i class="fas fa-list mr-2 text-indigo-300"></i> Daftar Barang
                        </a>
                        <a href="{{ route('items.create') }}"
                            class="submenu-item block px-3 py-2 rounded text-sm {{ request()->routeIs('items.create') ? 'active' : '' }}">
                            <i class="fas fa-plus mr-2 text-indigo-300"></i> Tambah Barang
                        </a>
                    </div>
                </div>
                <div x-data="{ unitOpen: {{ request()->routeIs('item-units.*') ? 'true' : 'false' }} }">
                    <button @click="unitOpen = !unitOpen"
                        class="nav-item flex items-center justify-between w-full px-4 py-3 rounded-lg font-medium {{ request()->routeIs('item-units.*') ? 'active' : '' }}">
                        <span class="flex items-center">
                            <i class="fas fa-ruler-combined mr-3 text-indigo-300"></i> Satuan
                        </span>
                        <i
                            :class="unitOpen ? 'fas fa-chevron-up text-indigo-300' : 'fas fa-chevron-down text-indigo-300'"></i>
                    </button>
                    <div x-show="unitOpen" class="ml-8 mt-1 space-y-1" x-cloak>
                        <a href="{{ route('item-units.index') }}"
                            class="submenu-item block px-3 py-2 rounded text-sm {{ request()->routeIs('item-units.index') ? 'active' : '' }}">
                            <i class="fas fa-list mr-2 text-indigo-300"></i> Daftar Satuan
                        </a>
                        <a href="{{ route('item-units.create') }}"
                            class="submenu-item block px-3 py-2 rounded text-sm {{ request()->routeIs('item-units.create') ? 'active' : '' }}">
                            <i class="fas fa-plus mr-2 text-indigo-300"></i> Tambah Satuan
                        </a>
                    </div>
                </div>
            </div>

            <!-- Transaction Section -->
            <div class="pt-2">
                <p class="px-4 py-2 text-xs font-semibold text-indigo-300 uppercase tracking-wider">Transaksi</p>
                <div class="space-y-1">
                    <div x-data="{ borrowOpen: {{ request()->routeIs('borrow-requests.*', 'borrow-details.*') ? 'true' : 'false' }} }">
                        <button @click="borrowOpen = !borrowOpen"
                            class="nav-item flex items-center justify-between w-full px-4 py-3 rounded-lg font-medium {{ request()->routeIs('borrow-requests.*', 'borrow-details.*') ? 'active' : '' }}">
                            <span class="flex items-center">
                                <i class="fas fa-hand-holding mr-3 text-indigo-300"></i> Peminjaman
                            </span>
                            <i
                                :class="borrowOpen ? 'fas fa-chevron-up text-indigo-300' : 'fas fa-chevron-down text-indigo-300'"></i>
                        </button>
                        <div x-show="borrowOpen" class="ml-8 mt-1 space-y-1" x-cloak>
                            <a href="{{ route('borrow-requests.index') }}"
                                class="submenu-item block px-3 py-2 rounded text-sm {{ request()->routeIs('borrow-requests.*') ? 'active' : '' }}">
                                <i class="fas fa-inbox mr-2 text-indigo-300"></i> Permintaan Pinjam
                            </a>
                            <a href="{{ route('borrow-details.index') }}"
                                class="submenu-item block px-3 py-2 rounded text-sm {{ request()->routeIs('borrow-details.*') ? 'active' : '' }}">
                                <i class="fas fa-info-circle mr-2 text-indigo-300"></i> Detail Pinjam
                            </a>
                        </div>
                    </div>
                    <a href="{{ route('return-requests.index') }}"
                        class="nav-item flex items-center px-4 py-3 rounded-lg font-medium {{ request()->routeIs('return-requests.index') ? 'active' : '' }}">
                        <i class="fas fa-undo-alt mr-3 text-indigo-300"></i> Pengembalian
                    </a>
                </div>
            </div>

            <!-- System Section -->
            <div class="pt-2">
                <p class="px-4 py-2 text-xs font-semibold text-indigo-300 uppercase tracking-wider">Sistem</p>
                <div class="space-y-1">
                    <div x-data="{ usersOpen: {{ request()->routeIs('users.*') ? 'true' : 'false' }} }">
                        <button @click="usersOpen = !usersOpen"
                            class="nav-item flex items-center justify-between w-full px-4 py-3 rounded-lg font-medium {{ request()->routeIs('users.*') ? 'active' : '' }}">
                            <span class="flex items-center">
                                <i class="fas fa-users mr-3 text-indigo-300"></i> Pengguna
                            </span>
                            <i
                                :class="usersOpen ? 'fas fa-chevron-up text-indigo-300' : 'fas fa-chevron-down text-indigo-300'"></i>
                        </button>
                        <div x-show="usersOpen" class="ml-8 mt-1 space-y-1" x-cloak>
                            <a href="{{ route('users.index') }}"
                                class="submenu-item block px-3 py-2 rounded text-sm {{ request()->routeIs('users.index') ? 'active' : '' }}">
                                <i class="fas fa-list mr-2 text-indigo-300"></i> Daftar Pengguna
                            </a>
                            <a href="{{ route('users.create') }}"
                                class="submenu-item block px-3 py-2 rounded text-sm {{ request()->routeIs('users.create') ? 'active' : '' }}">
                                <i class="fas fa-user-plus mr-2 text-indigo-300"></i> Tambah Pengguna
                            </a>
                        </div>
                    </div>
                    <a href="{{ route('activity-logs.index') }}"
                        class="nav-item flex items-center px-4 py-3 rounded-lg font-medium {{ request()->routeIs('activity-logs.index') ? 'active' : '' }}">
                        <i class="fas fa-history mr-3 text-indigo-300"></i> Log Aktivitas
                    </a>
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

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col overflow-hidden ml-64">
        <!-- Notification Area - Modern Design -->
        <div class="fixed top-4 right-4 z-50 w-80 space-y-3">
            @if (session('success'))
                <div class="notification success animate-slideInRight">
                    <div class="p-4 flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <h3 class="text-sm font-semibold text-white">Sukses!</h3>
                            <p class="text-xs mt-1 text-white opacity-90">{{ session('success') }}</p>
                        </div>
                        <button class="notification-close ml-2"
                            onclick="this.parentElement.parentElement.style.opacity = '0'; setTimeout(() => this.parentElement.parentElement.remove(), 300)">
                            <svg class="h-5 w-5 text-white opacity-70 hover:opacity-100" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                    <div class="h-1 bg-white bg-opacity-30 w-full">
                        <div class="h-full bg-white bg-opacity-80 notification-progress"
                            style="animation: progressBar 5s linear forwards;"></div>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="notification error animate-slideInRight">
                    <div class="p-4 flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <h3 class="text-sm font-semibold text-white">Error!</h3>
                            <p class="text-xs mt-1 text-white opacity-90">{{ session('error') }}</p>
                        </div>
                        <button class="notification-close ml-2"
                            onclick="this.parentElement.parentElement.style.opacity = '0'; setTimeout(() => this.parentElement.parentElement.remove(), 300)">
                            <svg class="h-5 w-5 text-white opacity-70 hover:opacity-100" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                    <div class="h-1 bg-white bg-opacity-30 w-full">
                        <div class="h-full bg-white bg-opacity-80 notification-progress"
                            style="animation: progressBar 5s linear forwards;"></div>
                    </div>
                </div>
            @endif

            @if (session('warning'))
                <div class="notification warning animate-slideInRight">
                    <div class="p-4 flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <h3 class="text-sm font-semibold text-white">Peringatan!</h3>
                            <p class="text-xs mt-1 text-white opacity-90">{{ session('warning') }}</p>
                        </div>
                        <button class="notification-close ml-2"
                            onclick="this.parentElement.parentElement.style.opacity = '0'; setTimeout(() => this.parentElement.parentElement.remove(), 300)">
                            <svg class="h-5 w-5 text-white opacity-70 hover:opacity-100" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                    <div class="h-1 bg-white bg-opacity-30 w-full">
                        <div class="h-full bg-white bg-opacity-80 notification-progress"
                            style="animation: progressBar 5s linear forwards;"></div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto p-6">
            @yield('content')
        </main>
    </div>

    <!-- JavaScript Functions -->
    <script>
        // Auto dismiss notifications after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            // Add progress bar animation
            const style = document.createElement('style');
            style.textContent = `
                @keyframes progressBar {
                    from { width: 100%; }
                    to { width: 0%; }
                }

                .animate-slideInRight {
                    animation: slideInRight 0.5s forwards;
                }
            `;
            document.head.appendChild(style);

            // Auto remove notifications after 5 seconds
            setTimeout(function() {
                const notifications = document.querySelectorAll('.notification');
                notifications.forEach(notification => {
                    notification.style.opacity = '0';
                    setTimeout(() => notification.remove(), 300);
                });
            }, 5000);
        });

        // Confirm delete dialog
        document.addEventListener("DOMContentLoaded", function() {
            const deleteForms = document.querySelectorAll(".delete-form");

            deleteForms.forEach(form => {
                form.addEventListener("submit", function(e) {
                    e.preventDefault();

                    Swal.fire({
                        title: 'Konfirmasi Hapus',
                        text: "Data yang dihapus tidak dapat dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#EF4444',
                        cancelButtonColor: '#6B7280',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        backdrop: 'rgba(0,0,0,0.4)',
                        background: '#1F2937',
                        color: '#F9FAFB',
                        customClass: {
                            confirmButton: 'px-4 py-2 rounded-lg shadow-md hover:shadow-lg transition',
                            cancelButton: 'px-4 py-2 rounded-lg shadow-md hover:shadow-lg transition'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
</body>

</html>
