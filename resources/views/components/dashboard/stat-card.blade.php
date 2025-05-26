<!-- resources/views/components/dashboard/stat-card.blade.php -->
@props([
    'title',
    'value',
    'trend', // Sekarang dinamis
    'trendColor' => 'blue', // Default color
    'icon',
])

<div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500 truncate">{{ $title }}</p>
            <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $value }}</p>
        </div>
        <div>{!! $icon !!}</div>
    </div>
    <div class="mt-4 flex items-center">
        @if (str_starts_with($trend, '+'))
            <span class="text-{{ $trendColor }}-600 flex items-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18">
                    </path>
                </svg>
                <span class="ml-1 text-sm font-medium">{{ $trend }}</span>
            </span>
        @elseif(str_starts_with($trend, '-'))
            <span class="text-red-600 flex items-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                </svg>
                <span class="ml-1 text-sm font-medium">{{ $trend }}</span>
            </span>
        @else
            <span class="text-gray-500 text-sm font-medium">{{ $trend }}</span>
        @endif
        <span class="text-gray-500 text-sm ml-2">vs periode sebelumnya</span>
    </div>
</div>
