@extends('layouts.app')

@section('title', 'Tambah Grup Baru')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Form Tambah Grup Baru</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.groups.store') }}" method="POST">
                    @csrf
                    <div class="form-floating form-floating-outline mb-4">
                        <input type="text" class="form-control" id="name" name="name"
                            placeholder="Masukkan Nama Grup" required />
                        <label for="name">Nama Grup</label>
                    </div>
                    <div class="form-floating form-floating-outline mb-4">
                        <input type="email" class="form-control" id="email" name="email"
                            placeholder="email@contoh.com" />
                        <label for="email">Email (Opsional)</label>
                    </div>
                    <div class="form-floating form-floating-outline mb-4">
                        <select class="form-select" id="parent_id" name="parent_id">
                            <option value="" selected>Tidak Ada (Jadikan Induk Grup)</option>
                            @foreach ($groups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                        <label for="parent_id">Induk Grup (Opsional)</label>
                    </div>
                    <div class="pt-2 text-end">
                        <a href="{{ route('admin.groups.index') }}" class="btn btn-secondary me-1">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
