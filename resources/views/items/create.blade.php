@extends('layouts.app')

@section('title', 'SISFO Sarpras - Tambah Barang')

@section('heading')
    <a href="{{ route('items.index') }}">
        <i class="fas fa-box ml-2 mr-1 text-indigo-300"></i>
        Barang
    </a>
@endsection

@section('subheading', ' / Tambah Barang Baru')

@section('content')
    <div class="min-h-screen flex flex-col justify-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-center">
                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Tambah Barang Baru
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Lengkapi formulir untuk menambahkan barang ke inventaris
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-6xl">
            <div class="bg-white py-8 px-6 shadow-lg rounded-lg sm:px-10 border border-gray-100">
                <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="flex flex-col md:flex-row gap-8">
                        <!-- Left Column - Form Inputs -->
                        <div class="w-full md:w-1/2 space-y-6">
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">Nama
                                        Barang</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                            </svg>
                                        </div>
                                        <input type="text" name="name" id="name" required
                                            class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-3 border"
                                            placeholder="Contoh: Printer LaserJet">
                                    </div>
                                </div>

                                <div>
                                    <label for="type" class="block text-sm font-medium text-gray-700">Jenis
                                        Barang</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                        </div>
                                        <select name="type" id="type" required
                                            class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-3 border appearance-none bg-white">
                                            <option value="consumable">Barang Habis Pakai</option>
                                            <option value="non-consumable">Barang Tidak Habis Pakai</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-700">Kategori</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                        </svg>
                                    </div>
                                    <select name="category_id" id="category_id" required
                                        class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-3 border appearance-none bg-white">
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                <div class="mt-1">
                                    <textarea id="description" name="description" rows="4"
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border border-gray-300 rounded-md py-2 px-3 resize-none"
                                        placeholder="Deskripsi detail tentang barang"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column - Image Upload -->
                        <div class="w-full md:w-1/2">
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 h-full flex flex-col">
                                <label for="image_url" class="block text-sm font-medium text-gray-700 mb-4">Gambar
                                    Barang</label>

                                <!-- Preview Container -->
                                <div id="imagePreview" class="hidden flex-1 flex flex-col">
                                    <div class="relative flex-1 bg-gray-50 rounded-md overflow-hidden">
                                        <img id="previewImage" class="w-full h-full max-h-64 object-contain">
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
                                <div id="uploadContent" class="flex-1 flex flex-col items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-600">Upload gambar barang</p>
                                    <p class="mt-1 text-xs text-gray-500 mb-4">Format: JPG, PNG (Maksimal 2MB)</p>
                                    <label for="image_url" class="cursor-pointer">
                                        <div
                                            class="px-4 py-2 bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100 transition-colors text-center">
                                            <span id="fileName" class="text-sm font-medium">Pilih Gambar</span>
                                            <input type="file" name="image_url" id="image_url" accept="image/*"
                                                class="hidden" onchange="previewFile()">
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between pt-6 mt-8 border-t border-gray-200">
                        <a href="{{ url()->previous() }}"
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
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Tambah Barang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function previewFile() {
            const preview = document.getElementById('previewImage');
            const fileInput = document.getElementById('image_url');
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
            document.getElementById('image_url').value = "";
            document.getElementById('previewImage').src = "";
            document.getElementById('imagePreview').classList.add('hidden');
            document.getElementById('uploadContent').classList.remove('hidden');
            document.getElementById('fileName').textContent = "Pilih Gambar";
        }
    </script>
@endsection
