@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create Item</h1>
        <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Type</label>
                <select name="type" class="form-control" required>
                    <option value="consumable">Consumable</option>
                    <option value="non-consumable">Non-Consumable</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Category</label>
                <select name="category_id" class="form-control" required>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label>Gambar Barang</label>
                <input type="file" name="image_url" accept="image/*">
            </div>
            <div class="mb-3">
                <label>Description</label>
                <textarea name="description" class="form-control"></textarea>
            </div>
            <button class="btn btn-success">Save</button>
        </form>
    </div>
@endsection
