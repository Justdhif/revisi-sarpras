@props(['icon', 'title', 'description'])

<div class="px-6 py-12 text-center">
    <div class="flex flex-col items-center justify-center">
        {!! $icon !!}
        <h3 class="mt-3 text-sm font-medium text-gray-900">{{ $title }}</h3>
        <p class="mt-1 text-sm text-gray-500">{{ $description }}</p>
    </div>
</div>
