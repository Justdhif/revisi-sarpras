@forelse ($notifications as $notif)
    <a href="{{ route('notifications.index', ['selected' => $notif->id] + request()->query()) }}"
        class="notification-item block p-4 border-b border-gray-100 transition-all duration-200 hover:bg-gray-50
        {{ $selected && $selected->id == $notif->id ? 'bg-indigo-50 border-l-4 border-indigo-500' : '' }}
        relative group"
        data-id="{{ $notif->id }}" data-read="{{ $notif->is_read ? 'true' : 'false' }}">

        <!-- Active State Indicator (only shows when selected) -->
        @if ($selected && $selected->id == $notif->id)
            <div class="absolute right-4 top-1/2 transform -translate-y-1/2">
                <svg class="h-5 w-5 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </div>
        @endif

        <!-- Unread Indicator -->
        @if (!$notif->is_read)
            <div class="absolute top-4 left-3 h-2.5 w-2.5 rounded-full bg-indigo-600"></div>
            <div class="absolute top-4 left-3 h-2.5 w-2.5 rounded-full bg-indigo-200 animate-ping"></div>
        @endif

        <div class="pl-5 pr-8">
            <div class="flex justify-between items-start">
                <h3
                    class="{{ $notif->is_read ? 'text-gray-700' : 'font-semibold text-gray-900' }} text-sm leading-snug">
                    {{ Str::limit($notif->message, 70) }}
                </h3>
            </div>

            <div class="flex items-center mt-2 space-x-2">
                <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                    {{ $notif->notification_type == 'request_peminjaman' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                    {{ $notif->notification_type == 'request_peminjaman' ? 'Peminjaman' : 'Pengembalian' }}
                </span>
                <span class="text-xs text-gray-500">{{ $notif->created_at->diffForHumans() }}</span>

                @if ($notif->is_read)
                    <span class="text-xs text-gray-400 ml-auto flex items-center">
                        <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                        Dibaca
                    </span>
                @endif
            </div>
        </div>
    </a>
@empty
    <div class="text-center py-12">
        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 mb-4">
            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-1">Tidak ada notifikasi</h3>
        <p class="text-gray-500">Tidak ditemukan notifikasi yang sesuai</p>
    </div>
@endforelse

@if ($notifications->hasPages())
    <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
        {{ $notifications->withQueryString()->onEachSide(1)->links() }}
    </div>
@endif
