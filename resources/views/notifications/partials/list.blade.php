@forelse ($notifications as $notif)
    <div class="notification-item cursor-pointer px-4 py-3 border-b border-gray-100 flex items-start {{ optional($selected)->id === $notif->id ? 'bg-gray-50' : '' }}"
        data-id="{{ $notif->id }}">
        <div class="flex-shrink-0 mt-1 mr-3">
            @if ($notif->type === 'borrow_request')
                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-hand-holding text-blue-500 text-sm"></i>
                </div>
            @elseif($notif->type === 'return_request')
                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                    <i class="fas fa-undo text-green-500 text-sm"></i>
                </div>
            @else
                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center">
                    <i class="fas fa-info-circle text-indigo-500 text-sm"></i>
                </div>
            @endif
        </div>
        <div class="flex-1 min-w-0">
            <div class="flex justify-between items-baseline">
                <h3 class="text-sm font-medium text-gray-900 truncate">{{ $notif->title }}</h3>
                <span
                    class="text-xs text-gray-500 ml-2 whitespace-nowrap">{{ $notif->created_at->diffForHumans() }}</span>
            </div>
            <p class="text-xs text-gray-500 mt-1 truncate">{{ $notif->body }}</p>
            @if (!$notif->is_read)
                <span
                    class="inline-block mt-1 px-1.5 py-0.5 text-xs font-medium bg-indigo-100 text-indigo-800 rounded-full unread-badge">Baru</span>
            @endif
        </div>
    </div>
@empty
    <div class="p-4 text-center text-gray-500">
        <i class="fas fa-bell-slash text-2xl mb-2"></i>
        <p>Tidak ada notifikasi</p>
    </div>
@endforelse
