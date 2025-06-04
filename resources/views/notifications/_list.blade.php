<div class="w-1/3 border-r border-gray-100 bg-white flex flex-col h-full shadow-sm">
    <!-- Header with Gradient Background -->
    <div class="px-6 py-4">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <h2 class="text-xl font-semibold text-gray-800">Notifikasi</h2>
                <span x-text="unreadCount"
                    class="px-2.5 py-0.5 bg-indigo-100 text-indigo-600 text-xs font-bold rounded-full flex items-center justify-center min-w-6 h-6">
                </span>
            </div>
            <button @click="markAllAsRead"
                class="text-white hover:text-indigo-100 focus:outline-none flex items-center space-x-1">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span class="text-sm font-medium">Tandai semua dibaca</span>
            </button>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="p-4 space-y-3 border-b border-gray-100">
        <div class="grid grid-cols-2 gap-3">
            <!-- Search Input -->
            <div class="relative col-span-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" x-model="search" @input.debounce.500ms="fetchNotifications(true)"
                    placeholder="Cari notifikasi..."
                    class="w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-300 focus:border-indigo-300 text-sm shadow-sm transition-all duration-150">
            </div>

            <!-- Status Filter -->
            <div class="relative col-span-1">
                <select x-model="status" @change="fetchNotifications(true)"
                    class="appearance-none w-full pl-3 pr-8 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-300 focus:border-indigo-300 bg-white text-sm text-gray-700 shadow-sm transition-all duration-150">
                    <option value="">Semua Notifikasi</option>
                    <option value="unread">Belum Dibaca</option>
                    <option value="read">Sudah Dibaca</option>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification List -->
    <div class="flex-1 overflow-y-auto">
        <!-- Skeleton Loader -->
        <template x-if="loadingList">
            <div class="p-4 space-y-4">
                <template x-for="i in 5" :key="i">
                    <div class="flex space-x-4">
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 rounded-full bg-gray-200"></div>
                        </div>
                        <div class="flex-1 space-y-3">
                            <div class="h-3.5 bg-gray-200 rounded w-3/4"></div>
                            <div class="h-3 bg-gray-200 rounded w-1/2"></div>
                            <div class="h-3 bg-gray-200 rounded"></div>
                        </div>
                    </div>
                </template>
            </div>
        </template>

        <!-- Empty State -->
        <template x-if="!loadingList && notifications.length === 0">
            <div class="p-8 text-center">
                <div class="mx-auto h-24 w-24 text-gray-300 mb-4">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-700 mb-1">Tidak ada notifikasi</h3>
                <p class="text-sm text-gray-500 max-w-xs mx-auto">Tidak ada notifikasi yang sesuai dengan filter
                    pencarian Anda.</p>
            </div>
        </template>

        <!-- Notification Items -->
        <template x-if="!loadingList && notifications.length > 0">
            <div class="divide-y divide-gray-100">
                <template x-for="notif in notifications" :key="notif.id">
                    <div :class="{
                        'bg-indigo-50/50': selected && selected.id === notif.id,
                        'bg-white hover:bg-gray-50': !(selected && selected.id === notif.id),
                        'opacity-90': notif.read_status === 'read'
                    }"
                        class="cursor-pointer p-4 transition-all duration-200 relative"
                        @click="selectNotification(notif.id)">
                        <!-- Unread Indicator -->
                        <div x-show="notif.read_status === 'unread'"
                            class="absolute left-0 top-0 bottom-0 w-1 bg-indigo-500 rounded-r"></div>

                        <div class="flex space-x-3">
                            <!-- Avatar/Icon -->
                            <div class="flex-shrink-0">
                                <div
                                    class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-baseline">
                                    <h3 class="text-sm font-semibold text-gray-900 truncate" x-text="notif.title"></h3>
                                    <span class="text-xs text-gray-500 whitespace-nowrap ml-2"
                                        x-text="notif.created_at"></span>
                                </div>
                                <p class="mt-1 text-sm text-gray-600 line-clamp-2" x-text="notif.message"></p>

                                <!-- Status Badge -->
                                <div x-show="notif.read_status === 'unread'" class="mt-2">
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                        Baru
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </template>
    </div>
</div>
