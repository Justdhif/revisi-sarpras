@props(['user', 'description', 'logName', 'time'])

<div class="px-6 py-4 hover:bg-gray-50 transition-colors">
    <div class="flex items-start gap-4">
        <div class="flex-shrink-0">
            <div class="h-10 w-10 bg-blue-50 rounded-full flex items-center justify-center">
                <span class="text-blue-600 font-medium">{{ substr($user['username'] ?? 'U', 0, 1) }}</span>
            </div>
        </div>
        <div class="flex-1 min-w-0">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-900 truncate">{{ $user['username'] ?? '-' }}</p>
                <time class="text-xs text-gray-500 flex items-center">
                    <svg class="w-3 h-3 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ $time }}
                </time>
            </div>
            <p class="text-sm text-gray-600 mt-1">{{ $description }}</p>
            <span
                class="inline-block mt-2 text-xs px-2 py-1 bg-gray-100 text-gray-600 rounded-full">{{ $logName }}</span>
        </div>
    </div>
</div>
