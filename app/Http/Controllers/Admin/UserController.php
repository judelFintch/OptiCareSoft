<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:users.manage');
    }

    public function index()
    {
        $users = User::with('roles')->orderBy('name')->paginate(20);
        return view('pages.admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::orderBy('name')->get();
        return view('pages.admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'phone'    => 'nullable|string|max:20',
            'specialty'=> 'nullable|string|max:100',
            'role'     => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'password'  => Hash::make($validated['password']),
            'phone'     => $validated['phone'] ?? null,
            'specialty' => $validated['specialty'] ?? null,
            'is_active' => true,
        ]);

        $user->assignRole($validated['role']);

        return redirect()->route('admin.users.index')->with('success', "Utilisateur {$user->name} créé.");
    }

    public function show(User $user)
    {
        $user->load('roles');
        return view('pages.admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::orderBy('name')->get();
        return view('pages.admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:100',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'password'  => 'nullable|min:8|confirmed',
            'phone'     => 'nullable|string|max:20',
            'specialty' => 'nullable|string|max:100',
            'role'      => 'required|exists:roles,name',
            'is_active' => 'boolean',
        ]);

        $user->update(array_filter([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'phone'     => $validated['phone'] ?? null,
            'specialty' => $validated['specialty'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
            'password'  => isset($validated['password']) ? Hash::make($validated['password']) : null,
        ], fn($v) => $v !== null));

        $user->syncRoles([$validated['role']]);

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur mis à jour.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }
        $user->update(['is_active' => false]);
        return redirect()->route('admin.users.index')->with('success', 'Compte désactivé.');
    }
}
