<div id="create-modal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900/60 backdrop-blur-sm">
    <!-- Konten Modal -->
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="w-full max-w-lg rounded-2xl bg-white shadow-xl">
            <!-- Header Modal -->
            <div class="px-8 py-6 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-semibold text-gray-900">Tambah Kategori Baru</h3>
                    <p class="text-sm text-gray-500 mt-1">Lengkapi form berikut untuk menambahkan kategori</p>
                </div>
                <button onclick="closeModal('create-modal')"
                    class="p-2 rounded-full hover:bg-gray-50 transition-colors duration-200 text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>

            <!-- Form Input -->
            <form action="{{ route('categories.store') }}" method="POST" class="px-8 py-6 pt-0 space-y-6">
                @csrf
                <div>
                    <label for="nama-kategori" class="block text-sm font-medium text-gray-700 mb-2">Nama Kategori
                        <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="text" id="nama-kategori" name="name" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all duration-200 outline-none placeholder-gray-400 text-gray-700 shadow-sm hover:border-gray-300"
                            placeholder="Contoh: Elektronik">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="deskripsi-kategori"
                        class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                    <div class="relative">
                        <textarea id="deskripsi-kategori" name="description" rows="3"
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all duration-200 outline-none resize-none placeholder-gray-400 text-gray-700 shadow-sm hover:border-gray-300"
                            placeholder="Masukkan deskripsi kategori (opsional)"></textarea>
                    </div>
                </div>

                <!-- Footer Modal (Tombol Aksi) -->
                <div class="flex justify-end gap-3 pt-5 border-t border-gray-100">
                    <button type="button" onclick="closeModal('create-modal')"
                        class="px-6 py-3 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-700 hover:bg-gray-50 transition-colors duration-200 shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-gray-200">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-6 py-3 text-sm font-medium rounded-lg bg-gradient-to-r from-blue-600 to-blue-500 text-white hover:from-blue-700 hover:to-blue-600 transition-all duration-200 shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-200 focus:ring-offset-2">
                        <span class="flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            Simpan Kategori
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
