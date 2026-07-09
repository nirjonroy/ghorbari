<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index(): View
    {
        $permissions = Permission::query()
            ->where('guard_name', 'admin')
            ->orderBy('name')
            ->paginate(20);

        return view('Admin.permissions.index', compact('permissions'));
    }

    public function create(): View
    {
        return view('Admin.permissions.create', ['permission' => new Permission(['guard_name' => 'admin'])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        Permission::create(['name' => $data['name'], 'guard_name' => 'admin']);

        return redirect()
            ->route('admin.permissions.index')
            ->with('status', 'Permission created successfully.');
    }

    public function edit(Permission $permission): View
    {
        abort_unless($permission->guard_name === 'admin', 404);

        return view('Admin.permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission): RedirectResponse
    {
        abort_unless($permission->guard_name === 'admin', 404);

        $data = $this->validatedData($request, $permission);
        $permission->update(['name' => $data['name']]);

        return redirect()
            ->route('admin.permissions.index')
            ->with('status', 'Permission updated successfully.');
    }

    public function destroy(Permission $permission): RedirectResponse
    {
        abort_unless($permission->guard_name === 'admin', 404);
        $permission->delete();

        return redirect()
            ->route('admin.permissions.index')
            ->with('status', 'Permission deleted successfully.');
    }

    private function validatedData(Request $request, ?Permission $permission = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('permissions', 'name')->where('guard_name', 'admin')->ignore($permission)],
        ]);
    }
}
