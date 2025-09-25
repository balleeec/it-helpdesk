<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\DataTables;

class RoleController extends Controller
{
    public function index()
    {
        return view('admin.roles.index');
    }

    public function data()
    {
        $roles = Role::with('permissions')->select('roles.*');
        return DataTables::of($roles)
            ->addColumn('permissions', function ($role) {
                return $role->permissions->sortBy('name')->pluck('display_name')->map(function ($name) {
                    return '<span class="badge bg-primary me-1 my-1">' . $name . '</span>';
                })->implode(' ');
            })
            ->addColumn('user_count', function ($role) {
                return $role->users()->count();
            })
            ->addColumn('action', function ($role) {
                $editUrl = route('admin.roles.edit', $role->id);
                $editButton = '<a href="' . $editUrl . '" class="btn btn-xs btn-warning me-1">Edit</a>';

                $deleteButton = '<button type="button" class="btn btn-xs btn-danger delete-btn"
                    data-url="' . route('admin.roles.destroy', $role->id) . '"
                    data-role="' . $role->name . '"
                    data-user-count="' . $role->users()->count() . '">Hapus</button>';

                return $editButton . $deleteButton;
            })
            ->rawColumns(['permissions', 'action'])
            ->make(true);
    }

    public function create()
    {
        $permissions = Permission::orderBy('display_name')->get();
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'nullable|array'
        ]);

        $role = Role::create(['name' => $request->name]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role baru berhasil dibuat.');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::orderBy('display_name')->get();
        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    // app/Http/Controllers/Admin/RoleController.php

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
            'permissions' => 'nullable|array'
        ]);

        $oldName = $role->getOriginal('name');
        $oldPermissions = $role->permissions->pluck('display_name')->toArray();

        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions ?? []);

        $newName = $role->name;
        $newPermissions = $role->fresh()->permissions->pluck('display_name')->toArray();

        activity()
            ->performedOn($role)
            ->causedBy(auth()->user()) // Opsional, tapi sangat direkomendasikan
            ->inLog('Role') // <-- PERBAIKAN: Method yang benar adalah inLog()
            ->event('updated')
            ->withProperties([
                'old' => [
                    'name' => $oldName,
                    'permissions' => $oldPermissions,
                ],
                'attributes' => [
                    'name' => $newName,
                    'permissions' => $newPermissions,
                ]
            ])
            ->log('Role ini telah di-updated');

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role berhasil diperbarui.');
    }

    public function destroy(Role $role)
    {
        $userCount = $role->users()->count();

        if ($userCount > 0) {
            return response()->json([
                'success' => false,
                'message' => "Role '{$role->name}' tidak dapat dihapus karena masih digunakan oleh {$userCount} pengguna."
            ], 422);
        }

        $role->delete();

        return response()->json([
            'success' => true,
            'message' => 'Role berhasil dihapus.'
        ]);
    }
}
