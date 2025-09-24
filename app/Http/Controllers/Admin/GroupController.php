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
                $editButton = '<a href="' . $editUrl . '" class="btn btn-sm btn-warning">Edit</a>';

                $deleteButton = '
                <button type="button" class="btn btn-sm btn-danger delete-btn" data-url="' . $deleteUrl . '">Hapus</button>';

                return $editButton . ' ' . $deleteButton;
            })
            ->rawColumns(['checkbox', 'action'])
            ->make(true);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        if (is_array($ids) && count($ids) > 0) {
            Group::whereIn('id', $ids)->delete();
            return response()->json(['success' => "Grup yang dipilih berhasil dihapus."]);
        }
        return response()->json(['error' => "Tidak ada grup yang dipilih."], 422);
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
        $group->delete();
        return redirect()->route('admin.groups.index')->with('success', 'Grup berhasil dihapus.');
    }
}
