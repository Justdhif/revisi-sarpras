@props(['title', 'value', 'icon', 'trend' => '+0%', 'trendColor' => 'blue', 'trendText' => 'vs last month'])

@php
    $trendColors = [
        'blue' => 'text-blue-600 bg-blue-50',
        'emerald' => 'text-emerald-600 bg-emerald-50',
        'amber' => 'text-amber-600 bg-amber-50',
        'rose' => 'text-rose-600 bg-rose-50',
    ];
@endphp

<div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
    <div class="flex flex-col h-full">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-medium text-gray-500">{{ $title }}</h3>
            <div class="bg-{{ $trendColor }}-100/50 p-2 rounded-lg">
                {!! $icon !!}
            </div>
        </div>
        <div class="mt-auto">
            <p class="text-3xl font-bold text-gray-900">{{ number_format($value) }}</p>
            <div class="flex items-center mt-2">
                <span
                    class="text-xs font-medium {{ $trendColors[$trendColor] }} px-2 py-1 rounded-full">{{ $trend }}</span>
                <span class="text-xs text-gray-500 ml-2">{{ $trendText }}</span>
            </div>
        </div>
    </div>
</div>
