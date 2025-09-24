<?php

namespace App\Http\Controllers\Admin;

use App\Models\Group;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index()
    {
        return view('admin.activity-log.index');
    }

    public function data()
    {
        $activities = Activity::with(['causer', 'subject'])->latest();

        return DataTables::of($activities)
            ->addColumn('event', function ($activity) {
                // ... (kode badge event tidak berubah)
                $badgeColor = match ($activity->event) {
                    'created' => 'success',
                    'updated' => 'info',
                    'deleted' => 'danger',
                    default => 'secondary',
                };
                return '<span class="badge bg-' . $badgeColor . '">' . strtoupper($activity->event) . '</span>';
            })
            ->addColumn('description_formatted', function ($activity) {
                // ... (kode deskripsi tidak berubah)
                $causer = $activity->causer->name ?? 'Sistem';
                $subjectType = class_basename($activity->subject_type);
                $subjectName = $activity->properties->get('attributes')['name'] ?? $activity->properties->get('old')['name'] ?? '(ID: ' . $activity->subject_id . ')';
                return "<strong>{$causer}</strong> melakukan aksi '{$activity->event}' pada <strong>{$subjectType}</strong> '{$subjectName}'";
            })
            ->addColumn('detail', function ($activity) {
                $properties = $activity->properties;
                if ($properties->has('old') && $properties->has('attributes')) {

                    // --- AWAL PERUBAHAN ---
                    $oldProps = $properties->get('old', []);
                    $newProps = $properties->get('attributes', []);

                    // Ubah 'parent_id' menjadi nama grup di data LAMA
                    if (isset($oldProps['parent_id'])) {
                        $oldParent = Group::find($oldProps['parent_id']);
                        $oldProps['Induk Grup'] = $oldParent ? $oldParent->name : 'Tidak Ada';
                        unset($oldProps['parent_id']); // Hapus key parent_id
                    }

                    // Ubah 'parent_id' menjadi nama grup di data BARU
                    if (isset($newProps['parent_id'])) {
                        $newParent = Group::find($newProps['parent_id']);
                        $newProps['Induk Grup'] = $newParent ? $newParent->name : 'Tidak Ada';
                        unset($newProps['parent_id']); // Hapus key parent_id
                    }

                    $oldData = htmlspecialchars(json_encode($oldProps), ENT_QUOTES, 'UTF-8');
                    $newData = htmlspecialchars(json_encode($newProps), ENT_QUOTES, 'UTF-8');
                    // --- AKHIR PERUBAHAN ---

                    return '<button class="btn btn-xs btn-primary btn-detail" data-old="' . $oldData . '" data-new="' . $newData . '">Lihat Detail</button>';
                }
                return '-';
            })
            ->editColumn('created_at', function ($activity) {
                return $activity->created_at->format('d M Y, H:i');
            })
            ->rawColumns(['event', 'description_formatted', 'detail'])
            ->make(true);
    }
}
