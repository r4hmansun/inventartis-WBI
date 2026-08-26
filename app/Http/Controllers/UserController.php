<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index()
    {
        $users = User::with('department')
            ->orderBy('name')
            ->paginate(10)
            ->onEachSide(1)
            ->withQueryString();

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(Request $request)
    {
        if (!$request->user()->isSuperAdmin()) {
            abort(403, 'Akses ditolak. Hanya Super Admin yang memiliki wewenang untuk menambahkan akun pengguna.');
        }

        $departments = Department::active()->orderBy('name')->get();
        $roles = [
            'user' => 'User / Staf Departemen',
            'finance' => 'Bagian Keuangan',
            'inventory' => 'Bagian Inventaris',
            'admin' => 'Admin (Admin Biasa)',
            'super_admin' => 'Super Admin (Kelola Role & User)',
        ];

        return view('users.create', compact('departments', 'roles'));
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        if (!$request->user()->isSuperAdmin()) {
            abort(403, 'Akses ditolak. Hanya Super Admin yang memiliki wewenang untuk menambahkan akun pengguna.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'role' => ['required', Rule::in(['user', 'finance', 'inventory', 'admin', 'super_admin'])],
        ]);

        User::create($validated);

        return redirect()->route('users.index')
            ->with('success', 'User baru berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(Request $request, User $user)
    {
        if (!$request->user()->isSuperAdmin()) {
            abort(403, 'Akses ditolak. Hanya Super Admin yang memiliki wewenang untuk mengubah informasi dan role pengguna lain.');
        }

        $departments = Department::active()->orderBy('name')->get();
        $roles = [
            'user' => 'User / Staf Departemen',
            'finance' => 'Bagian Keuangan',
            'inventory' => 'Bagian Inventaris',
            'admin' => 'Admin (Admin Biasa)',
            'super_admin' => 'Super Admin (Kelola Role & User)',
        ];

        return view('users.edit', compact('user', 'departments', 'roles'));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        if (!$request->user()->isSuperAdmin()) {
            abort(403, 'Akses ditolak. Hanya Super Admin yang memiliki wewenang untuk mengubah informasi dan role pengguna lain.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'role' => ['required', Rule::in(['user', 'finance', 'inventory', 'admin', 'super_admin'])],
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('users.index')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }
}
