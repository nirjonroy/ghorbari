<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(): View
    {
        $roles = Role::query()
            ->where('guard_name', 'admin')
            ->withCount('permissions')
            ->orderBy('name')
            ->paginate(15);

        return view('Admin.roles.index', compact('roles'));
    }

    public function create(): View
    {
        return view('Admin.roles.create', [
            'role' => new Role(['guard_name' => 'admin']),
            'permissions' => $this->permissions(),
            'rolePermissions' => [],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $role = Role::create(['name' => $data['name'], 'guard_name' => 'admin']);
        $role->syncPermissions($data['permissions'] ?? []);

        return redirect()
            ->route('admin.roles.index')
            ->with('status', 'Role created successfully.');
    }

    public function edit(Role $role): View
    {
        abort_unless($role->guard_name === 'admin', 404);

        return view('Admin.roles.edit', [
            'role' => $role,
            'permissions' => $this->permissions(),
            'rolePermissions' => $role->permissions()->pluck('name')->all(),
        ]);
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        abort_unless($role->guard_name === 'admin', 404);

        $data = $this->validatedData($request, $role);
        $role->update(['name' => $data['name']]);
        $role->syncPermissions($data['permissions'] ?? []);

        return redirect()
            ->route('admin.roles.index')
            ->with('status', 'Role updated successfully.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        abort_unless($role->guard_name === 'admin', 404);
        $role->delete();

        return redirect()
            ->route('admin.roles.index')
            ->with('status', 'Role deleted successfully.');
    }

    private function validatedData(Request $request, ?Role $role = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('roles', 'name')->where('guard_name', 'admin')->ignore($role)],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', Rule::exists('permissions', 'name')->where('guard_name', 'admin')],
        ]);
    }

    private function permissions()
    {
        return Permission::query()
            ->where('guard_name', 'admin')
            ->orderBy('name')
            ->get();
    }
}
