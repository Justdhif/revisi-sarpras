@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Item</h1>
        <form action="{{ route('items.update', $item) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $item->name) }}" required>
            </div>
            <div class="mb-3">
                <label>Type</label>
                <select name="type" class="form-control" required>
                    <option value="consumable" @if ($item->type == 'consumable') selected @endif>Consumable</option>
                    <option value="non-consumable" @if ($item->type == 'non-consumable') selected @endif>Non-Consumable</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Category</label>
                <select name="category_id" class="form-control" required>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" @if ($item->category_id == $cat->id) selected @endif>
                            {{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label>Gambar Barang</label>
                <input type="file" name="image_url" accept="image/*">
            </div>
            <div class="mb-3">
                <label>Description</label>
                <textarea name="description" class="form-control">{{ old('description', $item->description) }}</textarea>
            </div>
            <button class="btn btn-primary">Update</button>
        </form>
    </div>
@endsection
