@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Warehouse</h1>
        <form action="{{ route('warehouses.update', $warehouse) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $warehouse->name) }}" required>
            </div>
            <div class="mb-3">
                <label for="location" class="form-label">Location</label>
                <textarea name="location" class="form-control" required>{{ old('location', $warehouse->location) }}</textarea>
            </div>
            <div class="mb-3">
                <label for="capacity" class="form-label">Capacity</label>
                <input type="number" name="capacity" class="form-control"
                    value="{{ old('capacity', $warehouse->capacity) }}" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" class="form-control">{{ old('description', $warehouse->description) }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
@endsection
