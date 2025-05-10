<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#F0F9FF',
                            100: '#E0F2FE',
                            200: '#BAE6FD',
                            300: '#7DD3FC',
                            400: '#38BDF8',
                            500: '#0EA5E9',
                            600: '#0284C7',
                            700: '#0369A1',
                            800: '#075985',
                            900: '#0C4A6E',
                        },
                        accent: '#1E40AF'
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gradient-to-br from-primary-50 to-white flex items-center justify-center min-h-screen font-sans">
    <div class="text-center px-6 max-w-2xl">
        <div class="mb-10">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-32 w-32 mx-auto text-primary-600" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <h1 class="text-8xl font-bold text-primary-700 mb-6 tracking-tighter opacity-90">404</h1>
        <h2 class="text-3xl md:text-4xl font-semibold text-primary-900 mb-4">Halaman Tidak Ditemukan</h2>
        <p class="text-primary-800/80 mb-8 text-lg leading-relaxed max-w-lg mx-auto">
            Maaf, halaman yang Anda cari tidak dapat ditemukan. Mungkin telah dipindahkan atau tidak tersedia lagi.
        </p>
        <div class="flex justify-center space-x-4">
            <a href="{{ url('/') }}"
                class="inline-block bg-gradient-to-r from-primary-600 to-accent text-white px-8 py-3.5 rounded-lg hover:opacity-90 transition-all duration-300 font-medium shadow-md hover:shadow-lg transform hover:-translate-y-0.5 focus:ring-2 focus:ring-primary-300">
                Kembali ke Beranda
            </a>
            <a href="#"
                class="inline-block border-2 border-primary-600 text-primary-600 px-8 py-3 rounded-lg hover:bg-primary-50 transition-all duration-300 font-medium">
                Hubungi Dukungan
            </a>
        </div>
    </div>

    <!-- Decorative elements -->
    <div class="fixed inset-0 overflow-hidden -z-10 opacity-15">
        <div class="absolute top-1/4 left-1/4 w-64 h-64 rounded-full bg-primary-300 filter blur-3xl animate-float">
        </div>
        <div
            class="absolute bottom-1/4 right-1/4 w-64 h-64 rounded-full bg-primary-400 filter blur-3xl animate-float-delay">
        </div>
    </div>

    <style>
        @keyframes float {

            0%,
            100% {
                transform: translateY(0) translateX(0);
            }

            50% {
                transform: translateY(-20px) translateX(10px);
            }
        }

        .animate-float {
            animation: float 8s ease-in-out infinite;
        }

        .animate-float-delay {
            animation: float 8s ease-in-out 2s infinite;
        }
    </style>
</body>

</html>
