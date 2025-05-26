<div id="edit-modal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900/60 backdrop-blur-sm">
    <!-- Konten Modal -->
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="w-full max-w-md rounded-2xl bg-white shadow-xl">
            <!-- Header Modal -->
            <div class="px-8 py-6 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-semibold text-gray-900">Edit Kategori</h3>
                    <p class="text-sm text-gray-500 mt-1">Perbarui informasi kategori</p>
                </div>
                <button onclick="closeModal('edit-modal')"
                    class="p-2 rounded-full hover:bg-gray-50 transition-colors duration-200 text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>

            <!-- Form Input -->
            <form method="POST" id="edit-category-form" class="px-8 py-6 pt-2 space-y-6">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit-id">

                <div>
                    <label for="edit-category-name" class="block text-sm font-medium text-gray-700 mb-2">Nama Kategori
                        <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="text" id="edit-category-name" name="name" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all duration-200 outline-none placeholder-gray-400 text-gray-700 shadow-sm hover:border-gray-300"
                            placeholder="Masukkan nama kategori">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="edit-category-description"
                        class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                    <div class="relative">
                        <textarea id="edit-category-description" name="description" rows="3"
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all duration-200 outline-none resize-none placeholder-gray-400 text-gray-700 shadow-sm hover:border-gray-300"
                            placeholder="Deskripsi kategori"></textarea>
                    </div>
                </div>

                <!-- Footer Modal -->
                <div class="flex justify-end gap-3 pt-5 border-t border-gray-100">
                    <button type="button" onclick="closeModal('edit-modal')"
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
                            Simpan Perubahan
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
