<div class="bg-white rounded-lg shadow overflow-hidden border border-gray-200">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Nama Barang</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Jenis</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Kategori</th>
                <th scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gambar</th>
                <th scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dibuat Pada
                </th>
                <th scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @for ($i = 0; $i < 6; $i++)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="h-4 bg-gray-200 rounded w-3/4 animate-pulse"></div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="h-4 bg-gray-200 rounded w-1/2 animate-pulse"></div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="h-4 bg-gray-200 rounded w-1/3 animate-pulse"></div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="h-4 bg-gray-200 rounded w-1/4 animate-pulse"></div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="h-4 bg-gray-200 rounded w-1/4 animate-pulse"></div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex space-x-2">
                            <div class="h-7 bg-gray-200 rounded w-10 animate-pulse"></div>
                            <div class="h-7 bg-gray-200 rounded w-10 animate-pulse"></div>
                            <div class="h-7 bg-gray-200 rounded w-10 animate-pulse"></div>
                        </div>
                    </td>
                </tr>
            @endfor
        </tbody>
    </table>
    <div class="flex justify-between items-center px-4 py-3 bg-white border-t border-gray-200 rounded-b-lg">
        <div class="h-4 bg-gray-200 rounded w-1/4 animate-pulse"></div>
        <div class="flex space-x-2">
            <div class="h-8 bg-gray-200 rounded w-8 animate-pulse"></div>
            <div class="h-8 bg-gray-200 rounded w-8 animate-pulse"></div>
            <div class="h-8 bg-gray-200 rounded w-8 animate-pulse"></div>
        </div>
    </div>
</div>
