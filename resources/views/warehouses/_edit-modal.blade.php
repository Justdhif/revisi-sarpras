<div id="edit-modal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900/60 backdrop-blur-sm">
    <!-- Konten Modal -->
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="w-full max-w-lg rounded-2xl bg-white shadow-xl">
            <!-- Header Modal -->
            <div class="px-8 py-6 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-semibold text-gray-900">Edit Gudang</h3>
                    <p class="text-sm text-gray-500 mt-1">Perbarui informasi gudang</p>
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
            <form method="POST" id="edit-warehouse-form" class="px-8 py-6 pt-2 space-y-6">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit-id">

                <div>
                    <label for="edit-name" class="block text-sm font-medium text-gray-700 mb-2">Nama Gudang <span
                            class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="text" id="edit-name" name="name" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all duration-200 outline-none placeholder-gray-400 text-gray-700 shadow-sm hover:border-gray-300"
                            placeholder="Masukkan nama gudang">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="edit-location" class="block text-sm font-medium text-gray-700 mb-2">Lokasi <span
                            class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="text" id="edit-location" name="location" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all duration-200 outline-none placeholder-gray-400 text-gray-700 shadow-sm hover:border-gray-300"
                            placeholder="Masukkan lokasi gudang">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="edit-capacity" class="block text-sm font-medium text-gray-700 mb-2">Kapasitas <span
                            class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="number" id="edit-capacity" name="capacity" min="0" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all duration-200 outline-none placeholder-gray-400 text-gray-700 shadow-sm hover:border-gray-300"
                            placeholder="Masukkan kapasitas gudang">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path
                                    d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="edit-description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                    <div class="relative">
                        <textarea id="edit-description" name="description" rows="3"
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all duration-200 outline-none resize-none placeholder-gray-400 text-gray-700 shadow-sm hover:border-gray-300"
                            placeholder="Masukkan deskripsi gudang (opsional)"></textarea>
                    </div>
                </div>

                <!-- Footer Modal -->
                <div class="flex justify-end gap-3 pt-5 border-t border-gray-100">
                    <button type="button" onclick="closeModal('edit-modal')"
                        class="px-6 py-3 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-700 hover:bg-gray-50 transition-colors duration-200 shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-gray-200">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-6 py-3 text-sm font-medium rounded-lg bg-gradient-to-r from-indigo-600 to-indigo-500 text-white hover:from-indigo-700 hover:to-indigo-600 transition-all duration-200 shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:ring-offset-2">
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
