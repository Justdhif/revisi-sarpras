<div x-show="items.length > 0" class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Nama Barang
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Jenis
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Kategori
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Gambar
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Deskripsi
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <template x-for="item in items" :key="item.id">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div
                                    class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900" x-text="item.name"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                x-bind:class="{
                                    'bg-blue-100 text-blue-800': item.type === 'consumable',
                                    'bg-green-100 text-green-800': item.type === 'non-consumable'
                                }"
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                                <span x-text="item.type === 'consumable' ? 'Consumable' : 'Non-Consumable'"></span>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-600" x-text="item.category ? item.category.name : '-'"></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <template x-if="item.image_url">
                                <img x-bind:src="item.image_url" class="h-10 w-10 rounded-lg object-cover shadow-sm"
                                    x-bind:alt="item.name">
                            </template>
                            <template x-if="!item.image_url">
                                <span class="text-gray-400">-</span>
                            </template>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-600 max-w-xs truncate" x-text="item.description || '-'"></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-2">
                                <a x-bind:href="'{{ route('items.index') }}/' + item.id"
                                    class="text-amber-600 hover:text-amber-900 bg-amber-50 hover:bg-amber-100 px-3 py-1.5 rounded-md transition-colors duration-200 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                        <path fill-rule="evenodd"
                                            d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Lihat
                                </a>
                                <a x-bind:href="'{{ route('items.index') }}/' + item.id + '/edit'"
                                    class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-md transition-colors duration-200 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path
                                            d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                    Edit
                                </a>
                                <form x-bind:action="'{{ route('items.index') }}/' + item.id" method="POST"
                                    class="inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-rose-600 hover:text-rose-900 bg-rose-50 hover:bg-rose-100 px-3 py-1.5 rounded-md transition-colors duration-200 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div x-show="pagination.total > 0"
        class="mt-6 bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 rounded-b-lg">
        <div class="flex-1 flex justify-between sm:hidden">
            <button x-on:click="previousPage()" x-bind:disabled="pagination.current_page === 1"
                x-bind:class="{
                    'opacity-50 cursor-not-allowed': pagination.current_page === 1,
                    'hover:bg-gray-50': pagination.current_page !== 1
                }"
                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white">
                Previous
            </button>
            <button x-on:click="nextPage()" x-bind:disabled="pagination.current_page === pagination.last_page"
                x-bind:class="{
                    'opacity-50 cursor-not-allowed': pagination.current_page === pagination.last_page,
                    'hover:bg-gray-50': pagination.current_page !== pagination.last_page
                }"
                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white">
                Next
            </button>
        </div>
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    Showing
                    <span class="font-medium" x-text="pagination.from"></span>
                    to
                    <span class="font-medium" x-text="pagination.to"></span>
                    of
                    <span class="font-medium" x-text="pagination.total"></span>
                    results
                </p>
            </div>
            <div>
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                    <button x-on:click="previousPage()" x-bind:disabled="pagination.current_page === 1"
                        x-bind:class="{
                            'opacity-50 cursor-not-allowed': pagination.current_page === 1,
                            'hover:bg-gray-50': pagination.current_page !== 1
                        }"
                        class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500">
                        <span class="sr-only">Previous</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                            aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>

                    <template x-for="page in pagination.links" :key="page.label">
                        <template x-if="page.url">
                            <button x-on:click="goToPage(page.url)" x-bind:disabled="page.active"
                                x-bind:class="{
                                    'z-10 bg-indigo-50 border-indigo-500 text-indigo-600': page.active,
                                    'bg-white border-gray-300 text-gray-500 hover:bg-gray-50': !page.active
                                }"
                                class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                                x-text="page.label" aria-current="page">
                            </button>
                        </template>
                        <template x-if="!page.url && page.label !== '...'">
                            <span
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700"
                                x-text="page.label"></span>
                        </template>
                    </template>

                    <button x-on:click="nextPage()" x-bind:disabled="pagination.current_page === pagination.last_page"
                        x-bind:class="{
                            'opacity-50 cursor-not-allowed': pagination.current_page === pagination.last_page,
                            'hover:bg-gray-50': pagination.current_page !== pagination.last_page
                        }"
                        class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500">
                        <span class="sr-only">Next</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </nav>
            </div>
        </div>
    </div>
</div>
