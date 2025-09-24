@extends('layouts.app')

@section('title', 'Manajemen Grup')

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
                <h5 class="mb-0">Daftar Grup</h5>
                <div>
                    <button id="bulk-delete-btn" class="btn btn-danger" style="display: none;">
                        <i class="ri-delete-bin-line me-1"></i> Hapus yang Dipilih
                    </button>
                    <a href="{{ route('admin.groups.create') }}" class="btn btn-primary">
                        <i class="ri-add-line me-1"></i> Tambah Grup
                    </a>
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <table class="table table-striped" id="groups-table">
                    <thead>
                        <tr>
                            <th width="1%"><input type="checkbox" id="select_all_ids"></th>
                            <th>No</th>
                            <th>Nama Grup</th>
                            <th>Email</th>
                            <th>Induk Grup</th>
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
            // Inisialisasi Datatable
            var table = $('#groups-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('admin.groups.data') !!}',
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
                        data: 'parent',
                        name: 'parent.name'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            // =================================================================================
            // ## LOGIKA SWEETALERT UNTUK HAPUS SATUAN ##
            // =================================================================================
            $(document).on('click', '.delete-btn', function() {
                var deleteUrl = $(this).data('url');

                Swal.fire({
                    title: 'Anda yakin?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Buat form dinamis dan submit
                        var form = $('<form>', {
                            'method': 'POST',
                            'action': deleteUrl
                        });
                        var token = $('<input>', {
                            'type': 'hidden',
                            'name': '_token',
                            'value': '{{ csrf_token() }}'
                        });
                        var method = $('<input>', {
                            'type': 'hidden',
                            'name': '_method',
                            'value': 'DELETE'
                        });
                        form.append(token, method).appendTo('body').submit();
                    }
                })
            });


            // =================================================================================
            // ## LOGIKA SWEETALERT UNTUK HAPUS MASSAL ##
            // =================================================================================
            $("#select_all_ids").on('click', function() {
                $(".group_checkbox").prop('checked', $(this).prop('checked'));
                toggleBulkDeleteButton();
            });

            $(document).on('change', '.group_checkbox', function() {
                toggleBulkDeleteButton();
            });

            function toggleBulkDeleteButton() {
                if ($('.group_checkbox:checked').length > 0) {
                    $('#bulk-delete-btn').show();
                } else {
                    $('#bulk-delete-btn').hide();
                }
            }

            $('#bulk-delete-btn').on('click', function() {
                var ids = [];
                $('.group_checkbox:checked').each(function() {
                    ids.push($(this).val());
                });

                if (ids.length > 0) {
                    Swal.fire({
                        title: 'Anda yakin?',
                        text: "Anda akan menghapus " + ids.length + " data grup!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, hapus semua!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ route('admin.groups.bulk-delete') }}",
                                type: 'POST',
                                data: {
                                    _token: "{{ csrf_token() }}",
                                    ids: ids
                                },
                                success: function(response) {
                                    Swal.fire({
                                        title: 'Proses Selesai',
                                        text: response
                                            .message, // Tampilkan pesan gabungan dari server
                                        icon: 'info'
                                    }).then(function() {
                                        table.ajax.reload();
                                        $('#select_all_ids').prop('checked',
                                            false);
                                        toggleBulkDeleteButton();
                                    });
                                },
                                error: function(xhr) {
                                    // Ambil pesan error spesifik dari respons JSON server
                                    var errorMessage = xhr.responseJSON.error;
                                    Swal.fire(
                                        'Gagal!',
                                        errorMessage, // Tampilkan pesan error dari server
                                        'error'
                                    );
                                }
                            });
                        }
                    });
                } else {
                    Swal.fire('Info', 'Silakan pilih setidaknya satu grup untuk dihapus.', 'info');
                }
            });
        });
    </script>
@endpush
