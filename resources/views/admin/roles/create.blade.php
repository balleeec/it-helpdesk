@extends('layouts.app')

@section('title', 'Tambah Role Baru')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Tambah Role Baru</h5>
                <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.roles.store') }}" method="POST" id="roleForm">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline mb-4">
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" placeholder="Masukkan Nama Role"
                                    value="{{ old('name') }}" required />
                                <label for="name">Nama Role *</label>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0">Assign Permissions</h6>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="selectAllPermissions">
                                <label class="form-check-label" for="selectAllPermissions">
                                    Pilih Semua
                                </label>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    @foreach ($permissions->chunk(4) as $chunk)
                                        @foreach ($chunk as $permission)
                                            <div class="col-md-3 mb-3">
                                                <div class="form-check">
                                                    <input class="form-check-input permission-checkbox" type="checkbox"
                                                        name="permissions[]" value="{{ $permission->name }}"
                                                        id="perm_{{ $permission->id }}"
                                                        {{ in_array($permission->name, old('permissions', [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                        {{ $permission->display_name ?? $permission->name }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-2 text-end">
                        <button type="reset" class="btn btn-secondary me-2">
                            <i class="fas fa-redo me-1"></i> Reset
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Simpan Role
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection



@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Select all permissions functionality
            const selectAll = document.getElementById('selectAllPermissions');
            const permissionCheckboxes = document.querySelectorAll('.permission-checkbox');

            selectAll.addEventListener('change', function() {
                permissionCheckboxes.forEach(checkbox => {
                    checkbox.checked = selectAll.checked;
                });
            });

            // Uncheck select all if any permission is unchecked
            permissionCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    if (!this.checked) {
                        selectAll.checked = false;
                    } else {
                        // Check if all permissions are checked
                        const allChecked = Array.from(permissionCheckboxes).every(cb => cb.checked);
                        selectAll.checked = allChecked;
                    }
                });
            });
        });
    </script>
@endpush
