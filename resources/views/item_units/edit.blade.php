@extends('layouts.app')

@section('content')
    <h1>Edit Unit Barang</h1>

    <form action="{{ route('item-units.update', $itemUnit->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div>
            <label>SKU</label>
            <input type="text" name="sku" value="{{ old('sku', $itemUnit->sku) }}" required>
        </div>

        <div>
            <label>Kondisi</label>
            <input type="text" name="condition" value="{{ old('condition', $itemUnit->condition) }}" required>
        </div>

        <div>
            <label>Catatan</label>
            <textarea name="notes">{{ old('notes', $itemUnit->notes) }}</textarea>
        </div>

        <div>
            <label>Sumber Perolehan</label>
            <input type="text" name="acquisition_source"
                value="{{ old('acquisition_source', $itemUnit->acquisition_source) }}" required>
        </div>

        <div>
            <label>Tanggal Perolehan</label>
            <input type="date" name="acquisition_date" value="{{ old('acquisition_date', $itemUnit->acquisition_date) }}"
                required>
        </div>

        <div>
            <label>Catatan Perolehan</label>
            <textarea name="acquisition_notes">{{ old('acquisition_notes', $itemUnit->acquisition_notes) }}</textarea>
        </div>

        <div>
            <label>Status</label>
            <select name="status" required>
                @foreach (['available', 'borrowed', 'unknown'] as $status)
                    <option value="{{ $status }}" {{ old('status', $itemUnit->status) == $status ? 'selected' : '' }}>
                        {{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label>Jumlah</label>
            <input type="number" name="quantity" value="{{ old('quantity', $itemUnit->quantity) }}" min="1"
                required>
        </div>

        <div>
            <label>Item</label>
            <select name="item_id" required>
                @foreach ($items as $item)
                    <option value="{{ $item->id }}"
                        {{ old('item_id', $itemUnit->item_id) == $item->id ? 'selected' : '' }}>{{ $item->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label>Gudang</label>
            <select name="warehouse_id" required>
                @foreach ($warehouses as $warehouse)
                    <option value="{{ $warehouse->id }}"
                        {{ old('warehouse_id', $itemUnit->warehouse_id) == $warehouse->id ? 'selected' : '' }}>
                        {{ $warehouse->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit">💾 Update</button>
    </form>
@endsection
