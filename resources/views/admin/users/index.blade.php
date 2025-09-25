@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar User</h5>
                <div>
                    <button id="bulk-delete-btn" class="btn btn-danger" style="display: none;">Hapus yang Dipilih</button>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Tambah User</a>
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <table class="table table-striped" id="users-table">
                    <thead>
                        <tr>
                            <th width="1%"><input type="checkbox" id="select_all_ids"></th>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Grup</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            var table = $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('admin.users.data') !!}',
                columns: [{
                        data: 'checkbox',
                        name: 'checkbox',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'role',
                        name: 'role'
                    },
                    {
                        data: 'group',
                        name: 'group.name'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            // Hapus Satuan
            $(document).on('click', '.delete-btn', function() {
                var deleteUrl = $(this).data('url');
                Swal.fire({
                    title: 'Anda yakin?',
                    text: "Data akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var form = $('<form>', {
                            'method': 'POST',
                            'action': deleteUrl
                        });
                        form.append('{{ csrf_field() }}', '{{ method_field('DELETE') }}').appendTo(
                            'body').submit();
                    }
                })
            });

            // Hapus Massal
            $("#select_all_ids").on('click', function() {
                $(".user_checkbox").prop('checked', $(this).prop('checked'));
                toggleBulkDeleteButton();
            });

            $(document).on('change', '.user_checkbox', function() {
                toggleBulkDeleteButton();
            });

            function toggleBulkDeleteButton() {
                $('#bulk-delete-btn').toggle($('.user_checkbox:checked').length > 0);
            }

            $('#bulk-delete-btn').on('click', function() {
                var ids = [];
                $('.user_checkbox:checked').each(function() {
                    ids.push($(this).val());
                });

                if (ids.length > 0) {
                    Swal.fire({
                        title: 'Anda yakin?',
                        text: "Hapus " + ids.length + " user yang dipilih?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ route('admin.users.bulk-delete') }}",
                                type: 'POST',
                                data: {
                                    _token: "{{ csrf_token() }}",
                                    ids: ids
                                },
                                success: function(response) {
                                    Swal.fire('Dihapus!', response.success, 'success')
                                        .then(function() {
                                            table.ajax.reload();
                                            $('#select_all_ids').prop('checked',
                                                false);
                                            toggleBulkDeleteButton();
                                        });
                                }
                            });
                        }
                    });
                }
            });
        });
    </script>
@endpush
