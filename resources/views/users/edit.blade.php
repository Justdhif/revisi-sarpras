@extends('layouts.app')

@section('title', 'SISFO Sarpras - Edit Pengguna')

@section('heading')
    <a href="{{ route('users.index') }}">
        <i class="fas fa-users ml-2 mr-1 text-indigo-300"></i>
        Pengguna
    </a>
@endsection

@section('subheading', ' / Edit Data Pengguna')

@section('content')
    <div class="min-h-screen flex flex-col justify-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
            <div class="flex justify-center">
                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Edit Data Pengguna
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Perbarui informasi pengguna di bawah ini
            </p>
        </div>

        <div class="mt-8 mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="bg-white py-8 px-6 shadow-lg rounded-lg sm:px-10 border border-gray-100">
                <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Left Column -->
                        <div class="space-y-6 lg:col-span-2">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">Nama
                                        Lengkap</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        <input type="text" name="name" id="name" value="{{ $user->name }}"
                                            required
                                            class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-3 border"
                                            placeholder="Nama lengkap pengguna">
                                    </div>
                                </div>

                                <div>
                                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        <input type="text" name="username" id="username" value="{{ $user->username }}"
                                            required
                                            class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-3 border"
                                            placeholder="Username unik">
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <input type="email" name="email" id="email" value="{{ $user->email }}"
                                            required
                                            class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-3 border"
                                            placeholder="contoh@email.com">
                                    </div>
                                </div>

                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700">Telepon</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                        </div>
                                        <input type="text" name="phone" id="phone" value="{{ $user->phone }}"
                                            class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-3 border"
                                            placeholder="081234567890">
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label for="origin" class="block text-sm font-medium text-gray-700">Asal</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <input type="text" name="origin" id="origin" value="{{ $user->origin }}"
                                        class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-3 border"
                                        placeholder="Asal pengguna">
                                </div>
                            </div>
                        </div>

                        <!-- Right Column - Profile Picture -->
                        <div class="lg:col-span-1">
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 h-full flex flex-col">
                                <label for="profile_picture" class="block text-sm font-medium text-gray-700 mb-4">Foto
                                    Profil</label>

                                <!-- Preview Container -->
                                <div id="imagePreview"
                                    class="{{ $user->profile_picture ? '' : 'hidden' }} flex-1 flex flex-col">
                                    <div class="relative flex-1 bg-gray-50 rounded-md overflow-hidden">
                                        <img id="previewImage"
                                            src="{{ $user->profile_picture }}"
                                            class="w-full h-full max-h-64 object-contain">
                                        <button type="button" id="removeImageBtn"
                                            class="absolute top-2 right-2 p-1 bg-white rounded-full shadow-md text-gray-500 hover:text-red-500 hover:bg-red-50 transition-colors"
                                            onclick="removeImage()" title="Hapus Gambar">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Upload Content (shown when no image) -->
                                <div id="uploadContent"
                                    class="{{ $user->profile_picture ? 'hidden' : '' }} flex-1 flex flex-col items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-600">Upload foto profil pengguna</p>
                                    <p class="mt-1 text-xs text-gray-500 mb-4">Format: JPG, PNG (Maksimal 2MB)</p>
                                    <label for="profile_picture" class="cursor-pointer">
                                        <div
                                            class="px-4 py-2 bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100 transition-colors text-center">
                                            <span id="fileName" class="text-sm font-medium">Pilih Gambar</span>
                                            <input type="file" name="profile_picture" id="profile_picture"
                                                accept="image/*" class="hidden" onchange="previewFile()">
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between pt-6 mt-8 border-t border-gray-200">
                        <a href="{{ route('users.index') }}"
                            class="flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Kembali
                        </a>
                        <button type="submit"
                            class="flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Update Pengguna
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function previewFile() {
            const preview = document.getElementById('previewImage');
            const fileInput = document.getElementById('profile_picture');
            const file = fileInput.files[0];
            const reader = new FileReader();
            const previewContainer = document.getElementById('imagePreview');
            const uploadContent = document.getElementById('uploadContent');
            const fileName = document.getElementById('fileName');

            reader.onloadend = function() {
                preview.src = reader.result;
                previewContainer.classList.remove('hidden');
                uploadContent.classList.add('hidden');
                fileName.textContent = file.name;
            }

            if (file) {
                reader.readAsDataURL(file);
            }
        }

        function removeImage() {
            document.getElementById('profile_picture').value = "";
            document.getElementById('previewImage').src = "";
            document.getElementById('imagePreview').classList.add('hidden');
            document.getElementById('uploadContent').classList.remove('hidden');
            document.getElementById('fileName').textContent = "Pilih Gambar";
        }
    </script>
@endsection
