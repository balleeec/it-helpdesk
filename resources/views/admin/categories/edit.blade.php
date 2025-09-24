@extends('layouts.app')
@section('title', 'Edit Kategori')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Form Edit Kategori: {{ $category->name }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-floating form-floating-outline mb-4">
                        <input type="text" class="form-control" id="name" name="name"
                            value="{{ old('name', $category->name) }}" required />
                        <label for="name">Nama Kategori</label>
                    </div>
                    <div class="form-floating form-floating-outline mb-4">
                        <select class="form-select" id="parent_id" name="parent_id">
                            <option value="">Tidak Ada (Jadikan Induk Kategori)</option>
                            @foreach ($categories as $parent)
                                <option value="{{ $parent->id }}" @selected(old('parent_id', $category->parent_id) == $parent->id)>
                                    {{ $parent->name }}
                                </option>
                            @endforeach
                        </select>
                        <label for="parent_id">Induk Kategori (Opsional)</label>
                    </div>
                    <div class="pt-2 text-end">
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary me-1">Batal</a>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
