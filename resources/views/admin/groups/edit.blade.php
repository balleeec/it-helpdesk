@extends('layouts.app')

@section('title', 'Edit Grup')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Form Edit Grup: {{ $group->name }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.groups.update', $group->id) }}" method="POST">
                    @csrf
                    @method('PUT') {{-- Method spoofing untuk update --}}
                    <div class="form-floating form-floating-outline mb-4">
                        <input type="text" class="form-control" id="name" name="name"
                            placeholder="Masukkan Nama Grup" value="{{ old('name', $group->name) }}" required />
                        <label for="name">Nama Grup</label>
                    </div>
                    <div class="form-floating form-floating-outline mb-4">
                        <input type="email" class="form-control" id="email" name="email"
                            placeholder="email.notifikasi@contoh.com" value="{{ old('email', $group->email) }}" />
                        <label for="email">Email Notifikasi (Opsional)</label>
                    </div>
                    <div class="form-floating form-floating-outline mb-4">
                        <select class="form-select" id="parent_id" name="parent_id">
                            <option value="">Tidak Ada (Jadikan Induk Grup)</option>
                            @foreach ($groups as $parent)
                                <option value="{{ $parent->id }}" @selected(old('parent_id', $group->parent_id) == $parent->id)>
                                    {{ $parent->name }}
                                </option>
                            @endforeach
                        </select>
                        <label for="parent_id">Induk Grup (Opsional)</label>
                    </div>
                    <div class="pt-2 text-end">
                        <a href="{{ route('admin.groups.index') }}" class="btn btn-secondary me-1">Batal</a>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
