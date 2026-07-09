<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class AdminRoleController extends Controller
{
    public function index(): View
    {
        $admins = Admin::query()
            ->with('roles')
            ->orderBy('name')
            ->paginate(15);

        return view('Admin.admin_roles.index', compact('admins'));
    }

    public function create(): View
    {
        return view('Admin.admin_roles.create', [
            'admin' => new Admin(),
            'roles' => $this->roles(),
            'adminRoles' => [],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['string', Rule::exists('roles', 'name')->where('guard_name', 'admin')],
        ]);

        $admin = Admin::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $admin->syncRoles($data['roles'] ?? []);

        return redirect()
            ->route('admin.admin-roles.index')
            ->with('status', 'Admin user created successfully.');
    }

    public function edit(Admin $admin): View
    {
        return view('Admin.admin_roles.edit', [
            'admin' => $admin,
            'roles' => $this->roles(),
            'adminRoles' => $admin->roles()->pluck('name')->all(),
        ]);
    }

    public function update(Request $request, Admin $admin): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('admins', 'email')->ignore($admin)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['string', Rule::exists('roles', 'name')->where('guard_name', 'admin')],
        ]);

        $admin->fill([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        if (! empty($data['password'])) {
            $admin->password = Hash::make($data['password']);
        }

        $admin->save();
        $admin->syncRoles($data['roles'] ?? []);

        return redirect()
            ->route('admin.admin-roles.index')
            ->with('status', 'Admin user updated successfully.');
    }

    private function roles()
    {
        return Role::query()
            ->where('guard_name', 'admin')
            ->orderBy('name')
            ->get();
    }
}
