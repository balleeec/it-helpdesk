<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\DataTables;

class ActivityLogController extends Controller
{
    public function index()
    {
        return view('admin.activity-log.index');
    }

    public function data()
    {
        // Ambil data log, eager load relasi causer (user) dan subject (model yg diubah)
        $activities = Activity::with(['causer', 'subject'])->latest();

        return DataTables::of($activities)
            ->editColumn('description', function ($activity) {
                return $activity->description;
            })
            ->editColumn('subject', function ($activity) {
                // Tampilkan info model apa yang diubah
                if ($activity->subject) {
                    return class_basename($activity->subject) . ' (ID: ' . $activity->subject->id . ')';
                }
                return 'N/A';
            })
            ->editColumn('causer', function ($activity) {
                // Tampilkan nama user yang melakukan aksi
                return $activity->causer->name ?? 'Sistem';
            })
            ->editColumn('created_at', function ($activity) {
                // Format tanggal
                return $activity->created_at->format('d M Y, H:i');
            })
            ->rawColumns([]) // Tidak ada kolom HTML
            ->make(true);
    }
}
