@extends('layouts.app')

@section('title', 'Activity Log')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Riwayat Aktivitas Sistem</h5>
            </div>
            <div class="card-datatable table-responsive">
                <table class="table table-striped" id="activity-log-table">
                    <thead>
                        <tr>
                            <th>Aksi</th>
                            <th>Deskripsi</th>
                            <th>Waktu</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Detail Perubahan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Atribut</th>
                                <th>Data Lama</th>
                                <th>Data Baru</th>
                            </tr>
                        </thead>
                        <tbody id="detailModalBody">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            // Inisialisasi Datatable
            $('#activity-log-table').DataTable({
                processing: true,
                serverSide: true,
                order: [
                    [2, "desc"]
                ], // Urutkan berdasarkan kolom waktu (indeks 2)
                ajax: '{!! route('admin.activity-log.data') !!}',
                columns: [{
                        data: 'event',
                        name: 'event'
                    },
                    {
                        data: 'description_formatted',
                        name: 'description'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'detail',
                        name: 'detail',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            // Logika untuk menampilkan Modal
            $(document).on('click', '.btn-detail', function() {
                var oldData = $(this).data('old');
                var newData = $(this).data('new');
                var modalBody = $('#detailModalBody');

                modalBody.empty(); // Kosongkan isi modal sebelumnya

                for (var key in newData) {
                    if (newData.hasOwnProperty(key)) {
                        var oldValue = oldData[key] !== undefined ? oldData[key] : '<em>(tidak ada)</em>';
                        var newValue = newData[key];

                        // Hanya tampilkan jika ada perubahan
                        if (JSON.stringify(oldValue) !== JSON.stringify(newValue)) {
                            var row = '<tr>' +
                                '<td>' + key + '</td>' +
                                '<td><span class="text-danger">' + oldValue + '</span></td>' +
                                '<td><span class="text-success">' + newValue + '</span></td>' +
                                '</tr>';
                            modalBody.append(row);
                        }
                    }
                }

                // Tampilkan modal
                var detailModal = new bootstrap.Modal(document.getElementById('detailModal'));
                detailModal.show();
            });
        });
    </script>
@endpush
