<?php

namespace App\Http\Controllers\Admin;

use App\Models\Group;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreGroupRequest;
use App\Http\Requests\Admin\UpdateGroupRequest;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.groups.index');
    }

    // Di dalam GroupController.php

    public function data()
    {
        $groups = Group::with('parent')->select('groups.*');

        return DataTables::of($groups)
            ->addIndexColumn()
            ->addColumn('checkbox', function ($group) {
                return '<input type="checkbox" name="group_checkbox[]" class="group_checkbox" value="' . $group->id . '" />';
            })
            ->addColumn('parent', function ($group) {
                return $group->parent->name ?? '-';
            })
            ->addColumn('action', function ($group) {
                // -- PERBARUI BAGIAN INI --
                $editUrl = route('admin.groups.edit', $group->id);
                $deleteUrl = route('admin.groups.destroy', $group->id);

                // Tombol Edit
                $editButton = '<a href="' . $editUrl . '" class="btn btn-xs btn-warning">Edit</a>';

                $deleteButton = '
                <button type="button" class="btn btn-xs btn-danger delete-btn" data-url="' . $deleteUrl . '">Hapus</button>';

                return $editButton . ' ' . $deleteButton;
            })
            ->rawColumns(['checkbox', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $groups = Group::all();
        return view('admin.groups.create', compact('groups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGroupRequest $request)
    {
        Group::create($request->validated());

        return redirect()->route('admin.groups.index')->with('success', 'Grup baru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Group $group)
    {
        // Ambil semua grup KECUALI grup yang sedang diedit, untuk pilihan parent
        $groups = Group::where('id', '!=', $group->id)->get();
        return view('admin.groups.edit', compact('group', 'groups'));
    }

    public function update(UpdateGroupRequest $request, Group $group)
    {
        // Validasi berjalan otomatis dari UpdateGroupRequest
        $group->update($request->validated());

        return redirect()->route('admin.groups.index')->with('success', 'Grup berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group)
    {
        // CEK APAKAH GRUP INI PUNYA ANAK
        if ($group->children()->count() > 0) {
            return redirect()->route('admin.groups.index')
                ->with('error', 'Grup tidak dapat dihapus karena memiliki anak grup.');
        }

        $group->delete();
        return redirect()->route('admin.groups.index')->with('success', 'Grup berhasil dihapus.');
    }

    public function bulkDelete(Request $request)
    {
        $ids = collect($request->ids);
        $successNames = [];
        $errorNames = [];

        if ($ids->isNotEmpty()) {
            $groupsToDelete = Group::whereIn('id', $ids)->with(['children', 'users'])->get();

            foreach ($groupsToDelete as $group) {
                $childIds = $group->children->pluck('id');
                $remainingChildren = $childIds->diff($ids);

                // Jika grup aman untuk dihapus (tidak punya anak tersisa & tidak punya user)
                if ($remainingChildren->isEmpty() && $group->users->isEmpty()) {
                    $group->delete(); // Langsung hapus
                    $successNames[] = $group->name;
                } else {
                    $errorNames[] = $group->name;
                }
            }

            // Buat pesan respons dinamis
            $message = '';
            if (!empty($successNames)) {
                $message .= 'Berhasil menghapus grup: ' . implode(', ', $successNames) . '. ';
            }
            if (!empty($errorNames)) {
                $message .= 'Gagal menghapus grup (masih memiliki relasi): ' . implode(', ', $errorNames) . '.';
            }

            return response()->json(['success' => true, 'message' => trim($message)]);
        }

        return response()->json(['error' => "Tidak ada grup yang dipilih."], 422);
    }
}
