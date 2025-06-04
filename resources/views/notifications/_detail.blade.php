<div class="flex-1 p-6 overflow-y-auto">
    <!-- Loading State -->
    <template x-if="loading">
        <div class="animate-pulse space-y-4">
            <div class="h-7 bg-gray-200 rounded w-3/4"></div>
            <div class="h-4 bg-gray-100 rounded w-1/3"></div>
            <div class="h-5 bg-gray-100 rounded w-full mt-4"></div>
            <div class="h-5 bg-gray-100 rounded w-5/6"></div>
            <div class="h-5 bg-gray-100 rounded w-4/6"></div>
            <div class="h-32 bg-gray-100 rounded w-full mt-6"></div>
        </div>
    </template>

    <!-- Content -->
    <template x-if="selected && !loading">
        <div class="space-y-6">
            <!-- Header -->
            <div class="border-b pb-4">
                <div class="flex justify-between items-start">
                    <h2 class="text-2xl font-semibold text-gray-800" x-text="selected.title"></h2>
                    <span class="text-sm text-gray-500" x-text="selected.created_at"></span>
                </div>
                <p class="mt-2 text-gray-600" x-text="selected.message"></p>
            </div>

            <!-- Related Info Section -->
            <template x-if="selected.related_type">
                <div class="bg-blue-50 border border-blue-100 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-blue-800 mb-2">Informasi Terkait</h3>
                    <div class="grid grid-cols-2 gap-2 text-sm">
                        <div>
                            <p class="text-gray-500">Jenis</p>
                            <p class="font-medium text-gray-700" x-text="selected.related_type"></p>
                        </div>
                        <div>
                            <p class="text-gray-500">ID Referensi</p>
                            <p class="font-medium text-gray-700" x-text="selected.related_id"></p>
                        </div>
                    </div>
                    <a :href="selected.related_url"
                        class="mt-3 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 transition-colors">
                        Lihat Detail
                        <svg class="ml-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </a>
                </div>
            </template>

            <!-- Request Details Section -->
            <template x-if="selected.details && selected.details.length">
                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 px-4 py-3 border-b">
                        <h3 class="text-sm font-medium text-gray-700">Detail Permintaan</h3>
                    </div>
                    <ul class="divide-y divide-gray-200">
                        <template x-for="item in selected.details" :key="item.id">
                            <li class="px-4 py-3">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <img :src="item.image_url" class="w-12 h-12 rounded-md object-cover"
                                            alt="Item image">
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium text-gray-800 truncate" x-text="item.name"></p>
                                        <p x-if="item.quantity" class="text-xs text-gray-500">Jumlah: <span
                                                x-text="item.quantity"></span></p>
                                    </div>
                                </div>
                            </li>
                        </template>
                    </ul>
                </div>
            </template>

            <!-- User Info Section -->
            <template x-if="selected.user">
                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 px-4 py-3 border-b">
                        <h3 class="text-sm font-medium text-gray-700">Informasi Pengguna</h3>
                    </div>
                    <div class="p-4">
                        <div class="flex items-center space-x-4">
                            <img :src="selected.user.profile_picture"
                                class="w-14 h-14 rounded-full object-cover border-2 border-white shadow-sm" />
                            <div>
                                <p class="font-medium text-gray-800" x-text="selected.user.name"></p>
                                <div class="mt-1 flex items-center text-sm text-gray-500">
                                    <svg class="flex-shrink-0 mr-1.5 h-4 w-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <span x-text="selected.user.email"></span>
                                </div>
                                <div x-if="selected.user.phone" class="mt-1 flex items-center text-sm text-gray-500">
                                    <svg class="flex-shrink-0 mr-1.5 h-4 w-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                        </path>
                                    </svg>
                                    <span x-text="selected.user.phone"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </template>

    <!-- Empty State -->
    <template x-if="!selected && !loading">
        <div class="flex flex-col items-center justify-center h-full text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                </path>
            </svg>
            <h3 class="mt-2 text-lg font-medium text-gray-900">Tidak ada notifikasi dipilih</h3>
            <p class="mt-1 text-sm text-gray-500 max-w-md">Pilih notifikasi dari daftar untuk melihat detail lengkap</p>
        </div>
    </template>
</div>
