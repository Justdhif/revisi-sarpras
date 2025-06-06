<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SISFO Sarpras - Taruna Bhakti')</title>
    <link rel="icon" href="{{ asset('images/logo_sarpras.jpg') }}" type="image/x-icon">

    <!-- CSS & JS Libraries -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Tailwind Configuration -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        border: "hsl(var(--border))",
                        input: "hsl(var(--input))",
                        ring: "hsl(var(--ring))",
                        background: "hsl(var(--background))",
                        foreground: "hsl(var(--foreground))",
                        primary: {
                            DEFAULT: "hsl(var(--primary))",
                            foreground: "hsl(var(--primary-foreground))",
                        },
                        secondary: {
                            DEFAULT: "hsl(var(--secondary))",
                            foreground: "hsl(var(--secondary-foreground))",
                        },
                        destructive: {
                            DEFAULT: "hsl(var(--destructive))",
                            foreground: "hsl(var(--destructive-foreground))",
                        },
                        muted: {
                            DEFAULT: "hsl(var(--muted))",
                            foreground: "hsl(var(--muted-foreground))",
                        },
                        accent: {
                            DEFAULT: "hsl(var(--accent))",
                            foreground: "hsl(var(--accent-foreground))",
                        },
                        popover: {
                            DEFAULT: "hsl(var(--popover))",
                            foreground: "hsl(var(--popover-foreground))",
                        },
                        card: {
                            DEFAULT: "hsl(var(--card))",
                            foreground: "hsl(var(--card-foreground))",
                        },
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
</head>

<body class="font-sans antialiased min-h-screen flex">
    @include('components.sidebar')

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col overflow-hidden ml-64">
        @include('components.header')

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
                            confirmButton: 'px-4 py-2 rounded-md shadow-sm hover:shadow-md transition',
                            cancelButton: 'px-4 py-2 rounded-md shadow-sm hover:shadow-md transition'
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
