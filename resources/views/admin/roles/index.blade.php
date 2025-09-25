@extends('layouts.app')

@section('title', 'Manajemen Role & Permission')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Role & Permission</h5>
                <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Tambah Role
                </a>
            </div>
            <div class="card-datatable table-responsive">
                <table class="table table-striped" id="roles-table">
                    <thead>
                        <tr>
                            <th width="20%">Nama Role</th>
                            <th width="15%">Jumlah Pengguna</th>
                            <th width="45%">Permissions</th>
                            <th width="20%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data akan diisi oleh DataTables -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('#roles-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('admin.roles.data') !!}',
                columns: [{
                        data: 'name',
                        name: 'name',
                        className: 'fw-semibold'
                    },
                    {
                        data: 'user_count',
                        name: 'user_count',
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'permissions',
                        name: 'permissions.name',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ],

            });

            // SweetAlert for delete confirmation
            $(document).on('click', '.delete-btn', function() {
                var deleteUrl = $(this).data('url');
                var roleName = $(this).data('role');
                var userCount = $(this).data('user-count');

                if (userCount > 0) {
                    Swal.fire({
                        title: 'Tidak Dapat Dihapus',
                        text: `Role "${roleName}" tidak dapat dihapus karena masih digunakan oleh ${userCount} pengguna.`,
                        icon: 'error',
                        confirmButtonText: 'Mengerti'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: `Role "${roleName}" akan dihapus permanen!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: deleteUrl,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}',
                                _method: 'DELETE'
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        title: 'Terhapus!',
                                        text: response.message,
                                        icon: 'success',
                                        timer: 1500,
                                        showConfirmButton: false
                                    }).then(() => {
                                        $('#roles-table').DataTable().ajax
                                            .reload();
                                    });
                                }
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    title: 'Error!',
                                    text: xhr.responseJSON?.message ||
                                        'Terjadi kesalahan saat menghapus role.',
                                    icon: 'error'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
