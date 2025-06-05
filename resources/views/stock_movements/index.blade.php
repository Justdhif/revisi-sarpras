@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Stock Movements</h1>

        @if (session('success'))
            <div class="bg-green-200 text-green-800 p-2 mb-4 rounded">{{ session('success') }}</div>
        @endif

        <form method="GET" action="{{ route('stock_movements.index') }}" class="mb-6 flex flex-wrap gap-4">
            <div>
                <label for="start_date" class="block mb-1 font-semibold">Start Date</label>
                <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                    class="border rounded p-2">
            </div>
            <div>
                <label for="end_date" class="block mb-1 font-semibold">End Date</label>
                <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                    class="border rounded p-2">
            </div>
            <div>
                <label for="type" class="block mb-1 font-semibold">Type</label>
                <select name="type" id="type" class="border rounded p-2">
                    <option value="" {{ request('type') == '' ? 'selected' : '' }}>All</option>
                    <option value="in" {{ request('type') == 'in' ? 'selected' : '' }}>Stock In</option>
                    <option value="out" {{ request('type') == 'out' ? 'selected' : '' }}>Stock Out</option>
                    <option value="damaged" {{ request('type') == 'damaged' ? 'selected' : '' }}>Damaged</option>
                </select>
            </div>
            <div class="self-end">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Filter</button>
            </div>
        </form>

        <table class="min-w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border border-gray-300 px-4 py-2">Date</th>
                    <th class="border border-gray-300 px-4 py-2">Item</th>
                    <th class="border border-gray-300 px-4 py-2">Unit</th>
                    <th class="border border-gray-300 px-4 py-2">Type</th>
                    <th class="border border-gray-300 px-4 py-2">Quantity</th>
                    <th class="border border-gray-300 px-4 py-2">Description</th>
                </tr>
            </thead>
            <tbody>
                @forelse($movements as $movement)
                    <tr>
                        <td class="border border-gray-300 px-4 py-2">{{ $movement->movement_date }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $movement->itemUnit->item->name }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $movement->itemUnit->name }}</td>
                        <td class="border border-gray-300 px-4 py-2 capitalize">{{ $movement->type }}</td>
                        <td class="border border-gray-300 px-4 py-2 text-right">{{ $movement->quantity }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $movement->description ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="border border-gray-300 px-4 py-2 text-center">No records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $movements->withQueryString()->links() }}
        </div>

        <hr class="my-6">

        <h2 class="text-xl font-semibold mb-4">Add Stock Movement</h2>

        <form method="POST" action="{{ route('stock_movements.store') }}" class="max-w-lg space-y-4">
            @csrf

            <div>
                <label for="item_unit_id" class="block mb-1 font-semibold">Item Unit</label>
                <select name="item_unit_id" id="item_unit_id" required class="border rounded p-2 w-full">
                    <option value="">-- Select Item Unit --</option>
                    @foreach (\App\Models\ItemUnit::with('item')->get() as $unit)
                        <option value="{{ $unit->id }}">{{ $unit->item->name }} - {{ $unit->sku }}</option>
                    @endforeach
                </select>
                @error('item_unit_id')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="type" class="block mb-1 font-semibold">Type</label>
                <select name="type" id="type" required class="border rounded p-2 w-full">
                    <option value="">-- Select Type --</option>
                    <option value="in">Stock In</option>
                    <option value="out">Stock Out</option>
                    <option value="damaged">Damaged</option>
                </select>
                @error('type')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="quantity" class="block mb-1 font-semibold">Quantity</label>
                <input type="number" name="quantity" id="quantity" value="{{ old('quantity', 1) }}" min="1"
                    required class="border rounded p-2 w-full" />
                @error('quantity')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="movement_date" class="block mb-1 font-semibold">Movement Date</label>
                <input type="date" name="movement_date" id="movement_date"
                    value="{{ old('movement_date', date('Y-m-d')) }}" required class="border rounded p-2 w-full" />
                @error('movement_date')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block mb-1 font-semibold">Description (optional)</label>
                <textarea name="description" id="description" rows="3" class="border rounded p-2 w-full">{{ old('description') }}</textarea>
            </div>

            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Add
                Movement</button>
        </form>
    </div>
@endsection
