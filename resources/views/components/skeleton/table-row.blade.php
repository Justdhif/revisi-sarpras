<div id="skeleton-loader" class="hidden">
    <div class="bg-white rounded-lg shadow overflow-hidden border border-gray-200 mb-4">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    @foreach ($headers as $header)
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @for ($i = 0; $i < 5; $i++)
                    <tr>
                        @foreach ($headers as $index => $header)
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($index === count($headers) - 1 && in_array('Aksi', $headers))
                                    <div class="flex space-x-2">
                                        <div class="h-8 bg-gray-200 rounded w-8 animate-pulse"></div>
                                        <div class="h-8 bg-gray-200 rounded w-8 animate-pulse"></div>
                                    </div>
                                @else
                                    <div
                                        class="h-4 bg-gray-200 rounded @if ($index === 0) w-3/4 @elseif($index === 1) w-1/2 @elseif($index === 2) w-1/3 @else w-1/4 @endif animate-pulse">
                                    </div>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
    <div class="flex justify-between items-center px-4 py-3 bg-white border-t border-gray-200 rounded-b-lg">
        <div class="h-4 bg-gray-200 rounded w-1/4 animate-pulse"></div>
        <div class="flex space-x-2">
            <div class="h-8 bg-gray-200 rounded w-8 animate-pulse"></div>
            <div class="h-8 bg-gray-200 rounded w-8 animate-pulse"></div>
            <div class="h-8 bg-gray-200 rounded w-8 animate-pulse"></div>
        </div>
    </div>
</div>
