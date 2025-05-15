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

        @keyframes progressBar {
            from { width: 100%; }
            to { width: 0%; }
        }
    </style>
</head>

<body class="font-sans antialiased min-h-screen flex">
    <!-- Sidebar -->
    @include('components.sidebar')

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col overflow-hidden ml-64">
        <!-- Alerts -->
        @include('components.alerts')

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto p-6">
            @yield('content')
            @livewireScripts
        </main>
    </div>

    <!-- JavaScript Functions -->
    <script>
        // Auto dismiss notifications after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
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
