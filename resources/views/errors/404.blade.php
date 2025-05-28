<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan | SISFO Sarpras</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/logo_sarpras.jpg') }}" type="image/x-icon">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            600: '#0284C7',
                            700: '#0369A1',
                        },
                        accent: '#1E40AF',
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-50 min-h-screen font-sans flex items-center justify-center">
    <div class="container mx-auto px-6 py-12 max-w-4xl">
        <div class="text-center">
            <!-- Error Code -->
            <div class="mb-8">
                <span class="text-8xl font-bold text-primary-700 opacity-90">404</span>
            </div>

            <!-- Error Message -->
            <h1 class="text-3xl font-semibold text-gray-800 mb-4">Halaman Tidak Ditemukan</h1>
            <div class="w-24 h-1 bg-gradient-to-r from-primary-600 to-accent mx-auto mb-6 rounded-full"></div>

            <p class="text-gray-600 mb-8 max-w-xl mx-auto">
                Maaf, halaman yang Anda cari tidak dapat ditemukan. Periksa URL atau kembali ke beranda.
            </p>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ url('/') }}"
                   class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                    Kembali ke Beranda
                </a>
                <a href="#"
                   class="border border-primary-600 text-primary-600 hover:bg-primary-50 px-6 py-3 rounded-lg font-medium transition-colors">
                    Hubungi Tim IT
                </a>
            </div>

            <!-- Logo -->
            <div class="mt-12">
                <img src="{{ asset('images/logo_sarpras.jpg') }}" alt="SISFO Sarpras" class="h-12 mx-auto opacity-80">
            </div>
        </div>
    </div>

    <style>
        body {
            background-image: radial-gradient(circle at 1px 1px, #e5e5e5 1px, transparent 0);
            background-size: 20px 20px;
        }
    </style>
</body>

</html>
