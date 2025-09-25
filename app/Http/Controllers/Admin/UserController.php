<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.users.index');
    }

    public function data()
    {
        $users = User::with(['roles', 'group'])->select('users.*');
        $auth = auth()->user(); // Tambahkan ini

        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('checkbox', function ($user) use ($auth) {
                // Jangan tampilkan checkbox untuk user sendiri
                if ($auth->id === $user->id) {
                    return '';
                }
                return '<input type="checkbox" name="user_checkbox[]" class="user_checkbox" value="' . $user->id . '" />';
            })
            ->addColumn('role', fn($user) => $user->getRoleNames()->first() ?? '-')
            ->addColumn('group', fn($user) => $user->group->name ?? '-')
            ->addColumn('action', function ($user) use ($auth) { // Tambahkan use ($auth)
                $editUrl = route('admin.users.edit', $user->id);
                $editButton = '<a href="' . $editUrl . '" class="btn btn-xs btn-warning">Edit</a>';

                // Perbaikan: $auth->id !== $user->id (bukan $users->id)
                if ($auth->id !== $user->id) {
                    $deleteButton = '<button type="button" class="btn btn-xs btn-danger delete-btn" data-url="' . route('admin.users.destroy', $user->id) . '">Hapus</button>';
                    return $editButton . ' ' . $deleteButton;
                }

                return $editButton; // Return edit button saja jika user sedang login
            })
            ->rawColumns(['checkbox', 'action'])
            ->make(true);
    }

    public function create()
    {
        $roles = Role::all();
        $groups = Group::all();
        return view('admin.users.create', compact('roles', 'groups'));
    }

    public function store(StoreUserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'group_id' => $request->group_id,
        ]);

        $user->assignRole($request->role);

        return redirect()->route('admin.users.index')->with('success', 'User baru berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $groups = Group::all();
        return view('admin.users.edit', compact('user', 'roles', 'groups'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $userData = $request->validated();
        if (empty($userData['password'])) {
            unset($userData['password']);
        } else {
            $userData['password'] = Hash::make($userData['password']);
        }

        $user->update($userData);
        $user->syncRoles($request->role);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        if (is_array($ids) && count($ids) > 0) {
            $users = User::whereIn('id', $ids)->get();
            foreach ($users as $user) {
                $user->delete();
            }
            return response()->json(['success' => "User yang dipilih berhasil dihapus."]);
        }
        return response()->json(['error' => "Tidak ada user yang dipilih."], 422);
    }
}
