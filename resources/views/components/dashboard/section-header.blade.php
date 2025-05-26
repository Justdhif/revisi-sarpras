@props(['title', 'link' => '#', 'linkText' => 'View All'])

<div class="px-6 py-4 border-b border-gray-200">
    <div class="flex items-center justify-between">
        <h2 class="text-lg font-semibold text-gray-900">{{ $title }}</h2>
        <a href="{{ $link }}"
            class="text-sm font-medium text-blue-600 hover:text-blue-700">{{ $linkText }}</a>
    </div>
</div>
