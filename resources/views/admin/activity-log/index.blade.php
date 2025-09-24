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
                            <th>Deskripsi</th>
                            <th>Objek</th>
                            <th>Oleh</th>
                            <th>Waktu</th>
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
            $('#activity-log-table').DataTable({
                processing: true,
                serverSide: true,
                order: [
                    [3, "desc"]
                ], // Urutkan berdasarkan kolom waktu (indeks 3) secara descending
                ajax: '{!! route('admin.activity-log.data') !!}',
                columns: [{
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'subject',
                        name: 'subject.id'
                    }, // 'subject.id' hanya untuk sorting
                    {
                        data: 'causer',
                        name: 'causer.name'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    }
                ]
            });
        });
    </script>
@endpush
