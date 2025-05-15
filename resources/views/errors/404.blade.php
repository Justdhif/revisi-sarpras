<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan | SISFO Sarpras</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
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
                        accent: '#1E40AF',
                        dark: '#0F172A',
                        luxury: {
                            gold: '#D4AF37',
                            silver: '#C0C0C0'
                        }
                    },
                    boxShadow: {
                        'luxury': '0 10px 30px -10px rgba(0, 0, 0, 0.2)',
                        'luxury-lg': '0 15px 40px -10px rgba(0, 0, 0, 0.25)'
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gradient-to-br from-gray-50 to-white min-h-screen font-sans overflow-x-hidden">
    <!-- Main Content -->
    <div class="container mx-auto px-6 flex flex-col items-center justify-center min-h-screen py-16 relative z-10">
        <!-- 404 Illustration -->
        <div class="relative mb-12 animate__animated animate__fadeIn">
            <div class="absolute -inset-4 bg-primary-100 rounded-full opacity-70 blur-lg animate-pulse"></div>
            <div class="relative bg-white p-8 rounded-2xl shadow-luxury border border-gray-100">
                <img src="{{ asset('images/logo_sarpras.jpg') }}" alt="" class="w-full h-full">
            </div>
        </div>

        <!-- Error Message -->
        <div class="text-center max-w-3xl mb-12">
            <h1
                class="text-8xl font-bold text-primary-700 mb-6 tracking-tighter opacity-90 animate__animated animate__fadeInDown">
                404</h1>
            <h2
                class="text-3xl md:text-4xl font-semibold text-dark mb-4 animate__animated animate__fadeInDown animate__delay-1s">
                Halaman Tidak Ditemukan</h2>
            <div
                class="w-24 h-1 bg-gradient-to-r from-primary-400 to-accent mx-auto mb-6 rounded-full animate__animated animate__fadeIn animate__delay-1s">
            </div>
            <p
                class="text-gray-600 mb-6 text-lg leading-relaxed max-w-2xl mx-auto animate__animated animate__fadeIn animate__delay-1s">
                Maaf, halaman yang Anda cari tidak dapat ditemukan dalam SISFO Sarpras. Ini mungkin karena:
            </p>
            <ul
                class="text-gray-600 text-left max-w-md mx-auto mb-8 space-y-2 animate__animated animate__fadeIn animate__delay-1s">
                <li class="flex items-start">
                    <svg class="h-5 w-5 text-primary-500 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    URL yang dimasukkan tidak tepat
                </li>
                <li class="flex items-start">
                    <svg class="h-5 w-5 text-primary-500 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Halaman telah dipindahkan atau dihapus
                </li>
                <li class="flex items-start">
                    <svg class="h-5 w-5 text-primary-500 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Anda tidak memiliki izin untuk mengakses halaman ini
                </li>
            </ul>
        </div>

        <!-- Action Buttons -->
        <div
            class="flex flex-col sm:flex-row justify-center gap-4 animate__animated animate__fadeInUp animate__delay-1s">
            <a href="{{ url('/') }}"
                class="inline-flex items-center justify-center bg-gradient-to-r from-primary-600 to-accent text-white px-8 py-3.5 rounded-xl hover:opacity-90 transition-all duration-300 font-medium shadow-md hover:shadow-luxury-lg transform hover:-translate-y-0.5 focus:ring-2 focus:ring-primary-300 focus:ring-offset-2">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                    </path>
                </svg>
                Kembali ke Beranda
            </a>
            <a href="#"
                class="inline-flex items-center justify-center border-2 border-primary-600 text-primary-600 px-8 py-3 rounded-xl hover:bg-primary-50/50 transition-all duration-300 font-medium hover:shadow-md">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z">
                    </path>
                </svg>
                Hubungi Tim IT
            </a>
        </div>
    </div>

    <!-- Decorative elements -->
    <div class="fixed inset-0 overflow-hidden -z-10 opacity-10">
        <!-- Grid Pattern -->
        <div
            class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCIgdmlld0JveD0iMCAwIDQwIDQwIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNlNWU1ZTUiIGZpbGwtb3BhY2l0eT0iMC40Ij48cGF0aCBkPSJNMCAwaDQwdjQwSDB6Ii8+PC9nPjwvZz48L3N2Zz4=')]">
        </div>

        <!-- Floating circles -->
        <div
            class="absolute top-1/4 left-1/4 w-64 h-64 rounded-full bg-primary-200 filter blur-3xl opacity-30 animate-float">
        </div>
        <div
            class="absolute bottom-1/3 right-1/4 w-80 h-80 rounded-full bg-accent filter blur-3xl opacity-20 animate-float-delay">
        </div>
        <div
            class="absolute top-1/3 right-1/3 w-96 h-96 rounded-full bg-primary-300 filter blur-3xl opacity-10 animate-float-delay-2">
        </div>
    </div>

    <style>
        @keyframes float {

            0%,
            100% {
                transform: translateY(0) translateX(0);
            }

            50% {
                transform: translateY(-30px) translateX(15px);
            }
        }

        @keyframes float-reverse {

            0%,
            100% {
                transform: translateY(0) translateX(0);
            }

            50% {
                transform: translateY(20px) translateX(-10px);
            }
        }

        .animate-float {
            animation: float 10s ease-in-out infinite;
        }

        .animate-float-delay {
            animation: float 12s ease-in-out 2s infinite;
        }

        .animate-float-delay-2 {
            animation: float-reverse 14s ease-in-out 1s infinite;
        }

        .shadow-luxury {
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.15),
                0 0 0 1px rgba(255, 255, 255, 0.8) inset;
        }

        .shadow-luxury-lg {
            box-shadow: 0 15px 40px -10px rgba(0, 0, 0, 0.2),
                0 0 0 1px rgba(255, 255, 255, 0.8) inset;
        }
    </style>
</body>

</html>
