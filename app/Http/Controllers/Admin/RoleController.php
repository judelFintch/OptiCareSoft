<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $this->authorize('roles.manage');
        $roles = Role::withCount('permissions')->get();
        return view('pages.admin.roles.index', compact('roles'));
    }

    public function show(Role $role)
    {
        $this->authorize('roles.manage');

        $allPermissions = Permission::orderBy('name')->get()->groupBy(function ($p) {
            return explode('.', $p->name)[0];
        });

        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('pages.admin.roles.show', compact('role', 'allPermissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $this->authorize('roles.manage');

        $permissions = $request->input('permissions', []);
        $role->syncPermissions($permissions);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($role)
            ->log("Permissions du rôle [{$role->name}] mises à jour");

        return redirect()->route('admin.roles.show', $role)
            ->with('success', "Permissions du rôle {$role->name} mises à jour.");
    }
}
