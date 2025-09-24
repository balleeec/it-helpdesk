@extends('layouts.app')
@section('title', 'Tambah Kategori')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Form Tambah Kategori</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf
                    <div class="form-floating form-floating-outline mb-4">
                        <input type="text" class="form-control" id="name" name="name"
                            placeholder="Masukkan Nama Kategori" required />
                        <label for="name">Nama Kategori</label>
                    </div>
                    <div class="form-floating form-floating-outline mb-4">
                        <select class="form-select" id="parent_id" name="parent_id">
                            <option value="" selected>Tidak Ada (Jadikan Induk Kategori)</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <label for="parent_id">Induk Kategori (Opsional)</label>
                    </div>
                    <div class="pt-2 text-end">
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary me-1">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
