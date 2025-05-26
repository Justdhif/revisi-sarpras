@extends('layouts.app')

@section('title', 'Detail Kategori Item')

@section('heading')
    <a href="{{ route('categories.index') }}">
        <i class="fas fa-tags ml-2 mr-1 text-indigo-300"></i>
        Kategori
    </a>
@endsection

@section('subheading', ' / Detail Kategori ' . ucfirst($category->name))

@section('content')
    @include('categories._edit-modal')

    <div class="space-y-6">
        <!-- Header Section with Back Button -->
        <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
            <div>
                <a href="{{ route('categories.index') }}"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 mb-4">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
                <h1 class="text-2xl font-semibold text-gray-800">Kategori: {{ $category->name }}</h1>
                <p class="text-sm text-gray-500">
                    Terakhir diperbarui: {{ $category->updated_at->format('d M Y, H:i') }}
                </p>
            </div>
            <div class="flex gap-2">
                <button
                    onclick="openEditModal({{ $category->id }}, '{{ $category->name }}', '{{ $category->description }}')"
                    class="inline-flex items-center text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-4 py-2 rounded-lg">
                    <i class="fas fa-edit mr-2"></i> Edit
                </button>
                <form method="POST" action="{{ route('categories.destroy', $category->id) }}" class="delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="inline-flex items-center text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-4 py-2 rounded-lg">
                        <i class="fas fa-trash mr-2"></i> Hapus
                    </button>
                </form>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="rounded-lg border border-blue-100 bg-blue-50 p-5 flex items-start">
                <div class="rounded-full p-3 mr-4 text-lg bg-blue-100 text-blue-600">
                    <i class="fas fa-box"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-medium text-gray-500">Total Item</h3>
                    <p class="text-xl font-semibold mt-1">{{ $category->items->count() }}</p>
                    <p class="text-xs text-gray-500 mt-1">Dalam kategori ini</p>
                </div>
            </div>

            <div class="rounded-lg border border-purple-100 bg-purple-50 p-5 flex items-start">
                <div class="rounded-full p-3 mr-4 text-lg bg-purple-100 text-purple-600">
                    <i class="fas fa-calendar-plus"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-medium text-gray-500">Dibuat Pada</h3>
                    <p class="text-xl font-semibold mt-1">{{ $category->created_at->format('d M Y') }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $category->created_at->diffForHumans() }}</p>
                </div>
            </div>

            <div class="rounded-lg border border-green-100 bg-green-50 p-5 flex items-start">
                <div class="rounded-full p-3 mr-4 text-lg bg-green-100 text-green-600">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-medium text-gray-500">Status</h3>
                    <p class="text-xl font-semibold mt-1">Aktif</p>
                    <p class="text-xs text-gray-500 mt-1">Kategori tersedia</p>
                </div>
            </div>
        </div>

        <!-- Description Card -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
            <div
                class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-info-circle mr-2"></i>
                    Deskripsi Kategori
                </h3>
            </div>
            <div class="p-6 text-gray-700">
                {{ $category->description ?? 'Tidak ada deskripsi tersedia untuk kategori ini.' }}
            </div>
        </div>

        <!-- Items Section -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
            <div
                class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-boxes mr-2"></i>Item dalam Kategori
                    </h3>
                    <p class="text-sm text-gray-600">Total {{ $category->items->count() }} item terdaftar</p>
                </div>
                <a href="{{ route('items.create') }}?category_id={{ $category->id }}"
                    class="inline-flex items-center bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg">
                    <i class="fas fa-plus mr-2"></i>Tambah Item Baru
                </a>
            </div>

            @if ($category->items->isEmpty())
                <div class="p-12 text-center">
                    <i class="fas fa-box-open text-gray-400 text-5xl mx-auto mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-700">Tidak ada Item</h3>
                    <p class="mt-1 text-sm text-gray-500">Belum ada Item yang terdaftar dalam kategori ini.</p>
                    <a href="{{ route('items.create') }}?category_id={{ $category->id }}"
                        class="inline-flex items-center mt-4 bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg">
                        <i class="fas fa-plus mr-2"></i>Tambah Item
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama Item</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tipe</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Dibuat</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($category->items as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if ($item->image_url)
                                                <img src="{{ asset($item->image_url) }}" alt="{{ $item->name }}"
                                                    class="h-10 w-10 rounded-lg object-cover mr-3 border border-gray-200">
                                            @else
                                                <div
                                                    class="h-10 w-10 rounded-lg bg-gray-100 flex items-center justify-center mr-3 border border-gray-200">
                                                    <i class="fas fa-box text-gray-400"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="font-medium text-gray-900">{{ $item->name }}</div>
                                                <div class="text-sm text-gray-500 truncate max-w-xs">
                                                    {{ $item->description ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                            {{ $item->type ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500 flex items-center">
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ $item->created_at->diffForHumans() }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right space-x-2">
                                        <a href="{{ route('items.show', $item->id) }}"
                                            class="inline-flex items-center px-3 py-1.5 text-sm rounded text-amber-600 hover:text-amber-800 bg-amber-50 hover:bg-amber-100">
                                            <i class="fas fa-eye mr-1"></i> Lihat
                                        </a>
                                        <a href="{{ route('items.edit', $item->id) }}"
                                            class="inline-flex items-center px-3 py-1.5 text-sm rounded text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100">
                                            <i class="fas fa-edit mr-1"></i> Edit
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <script>
        function openEditModal(id, name, description) {
            const form = document.getElementById('edit-category-form');
            form.action = `{{ route('categories.update', ':id') }}`.replace(':id', id);
            document.getElementById('edit-id').value = id;
            document.getElementById('edit-category-name').value = name;
            document.getElementById('edit-category-description').value = description;

            // Show modal
            document.getElementById('edit-modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('edit-modal').classList.add('hidden');
        }
    </script>
@endsection
