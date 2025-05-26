@extends('layouts.app')

@section('title', 'SISFO Sarpras - Edit Barang')

@section('heading')
    <a href="{{ route('items.index') }}">
        <i class="fas fa-box ml-2 mr-1 text-indigo-300"></i>
        Barang
    </a>
@endsection

@section('subheading', ' / Edit Barang ' . ucfirst($item->name))

@section('content')
    <div class="min-h-screen flex flex-col justify-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-indigo-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Edit Data Barang {{ ucfirst($item->name) }}
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Perbarui informasi barang berikut
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-6xl">
            <div class="bg-white py-8 px-6 shadow-lg rounded-lg sm:px-10 border border-gray-100">
                <form class="space-y-6" action="{{ route('items.update', $item) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="flex flex-col md:flex-row gap-8">
                        <!-- Left Column - Form Inputs -->
                        <div class="w-full md:w-1/2 space-y-6">
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Barang</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                            </svg>
                                        </div>
                                        <input type="text" name="name" id="name" required
                                            class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-3 border"
                                            value="{{ old('name', $item->name) }}" placeholder="Contoh: Printer LaserJet">
                                    </div>
                                </div>

                                <div>
                                    <label for="type" class="block text-sm font-medium text-gray-700">Jenis Barang</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                        </div>
                                        <select name="type" id="type" required
                                            class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-3 border appearance-none bg-white">
                                            <option value="consumable" @if (old('type', $item->type) == 'consumable') selected @endif>Barang
                                                Habis Pakai</option>
                                            <option value="non-consumable" @if (old('type', $item->type) == 'non-consumable') selected @endif>Barang
                                                Tidak Habis Pakai</option>
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
                                            <option value="{{ $cat->id }}" @if (old('category_id', $item->category_id) == $cat->id) selected @endif>
                                                {{ $cat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                <div class="mt-1">
                                    <textarea id="description" name="description" rows="4"
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border border-gray-300 rounded-md py-2 px-3 resize-none"
                                        placeholder="Deskripsi detail tentang barang">{{ old('description', $item->description) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column - Image Upload -->
                        <div class="w-full md:w-1/2">
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 h-full flex flex-col">
                                <label for="image_url" class="block text-sm font-medium text-gray-700 mb-4">Gambar Barang</label>

                                <!-- Current Image (shown when no new image selected) -->
                                <div id="currentImageContainer" class="@if($item->image_url) mb-4 @else hidden @endif">
                                    <p class="text-sm text-gray-500 mb-2">Gambar saat ini:</p>
                                    @if($item->image_url)
                                        <img src="{{ asset($item->image_url) }}" alt="Gambar Barang"
                                            class="w-full h-48 object-contain rounded-md border border-gray-200">
                                    @endif
                                </div>

                                <!-- New Image Preview -->
                                <div id="imagePreviewContainer" class="hidden flex-1 flex flex-col">
                                    <div class="relative flex-1 bg-gray-50 rounded-md overflow-hidden">
                                        <img id="imagePreview" class="w-full h-full max-h-64 object-contain">
                                        <button type="button" id="removeImageBtn" class="absolute top-2 right-2 p-1 bg-white rounded-full shadow-md text-gray-500 hover:text-red-500 hover:bg-red-50 transition-colors" onclick="removeImage()" title="Hapus Gambar">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                    <p class="text-sm text-gray-500 mt-2">Pratinjau gambar baru:</p>
                                </div>

                                <!-- Upload Content (shown when no image is selected) -->
                                <div id="uploadContent" class="flex-1 flex flex-col items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-600">Upload gambar baru</p>
                                    <p class="mt-1 text-xs text-gray-500 mb-4">Format: JPG, PNG (Maksimal 2MB)</p>
                                    <label for="image_url" class="cursor-pointer">
                                        <div class="px-4 py-2 bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100 transition-colors text-center">
                                            <span id="fileName" class="text-sm font-medium">Pilih Gambar</span>
                                            <input type="file" name="image_url" id="image_url" accept="image/*" class="hidden" onchange="previewFile()">
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-6">
                        <a href="{{ url()->previous() }}"
                            class="flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
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
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Perbarui Barang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Image preview functionality
        document.getElementById('image_url').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                const previewContainer = document.getElementById('imagePreviewContainer');
                const currentImageContainer = document.getElementById('currentImageContainer');
                const uploadContent = document.getElementById('uploadContent');
                const fileName = document.getElementById('fileName');

                reader.onloadend = function() {
                    document.getElementById('imagePreview').src = reader.result;
                    previewContainer.classList.remove('hidden');
                    currentImageContainer.classList.add('hidden');
                    uploadContent.classList.add('hidden');
                    fileName.textContent = file.name;
                }

                reader.readAsDataURL(file);
            }
        });

        function removeImage() {
            document.getElementById('image_url').value = "";
            document.getElementById('imagePreview').src = "";
            document.getElementById('imagePreviewContainer').classList.add('hidden');
            document.getElementById('currentImageContainer').classList.remove('hidden');
            document.getElementById('uploadContent').classList.remove('hidden');
            document.getElementById('fileName').textContent = "Pilih Gambar";
        }
    </script>
@endsection
