@extends('layouts.app')
@section('title', 'Edit User')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Form Edit User: {{ $user->name }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ old('name', $user->name) }}" required />
                                <label for="name">Nama</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="form-floating form-floating-outline">
                                <input type="email" class="form-control" id="email" name="email"
                                    value="{{ old('email', $user->email) }}" required />
                                <label for="email">Email</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="form-floating form-floating-outline">
                                <input type="password" class="form-control" id="password" name="password" />
                                <label for="password">Password Baru (kosongkan jika tidak diubah)</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="form-floating form-floating-outline">
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation" />
                                <label for="password_confirmation">Konfirmasi Password Baru</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="form-floating form-floating-outline">
                                <select class="form-select" id="role" name="role" required>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->name }}" @selected($user->hasRole($role->name))>{{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="role">Role</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="form-floating form-floating-outline">
                                <select class="form-select" id="group_id" name="group_id">
                                    <option value="">Tidak ada grup</option>
                                    @foreach ($groups as $group)
                                        <option value="{{ $group->id }}" @selected($user->group_id == $group->id)>{{ $group->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="group_id">Grup (Opsional)</label>
                            </div>
                        </div>
                    </div>
                    <div class="pt-2 text-end">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary me-1">Batal</a>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
