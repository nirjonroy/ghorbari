<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ManagesSeoFields;
use App\Http\Controllers\Controller;
use App\Models\Division;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DivisionController extends Controller
{
    use ManagesSeoFields;

    public function index(): View
    {
        $divisions = Division::query()
            ->withCount('districts')
            ->orderBy('name')
            ->paginate(15);

        return view('Admin.divisions.index', compact('divisions'));
    }

    public function create(): View
    {
        return view('Admin.divisions.create', ['division' => new Division()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->applySeoImage($request, $this->validatedData($request), null, 'uploads/locations/seo');

        Division::create($data);

        return redirect()
            ->route('admin.divisions.index')
            ->with('status', 'Division created successfully.');
    }

    public function edit(Division $division): View
    {
        return view('Admin.divisions.edit', compact('division'));
    }

    public function update(Request $request, Division $division): RedirectResponse
    {
        $data = $this->applySeoImage($request, $this->validatedData($request, $division), $division, 'uploads/locations/seo');

        $division->update($data);

        return redirect()
            ->route('admin.divisions.index')
            ->with('status', 'Division updated successfully.');
    }

    public function destroy(Division $division): RedirectResponse
    {
        $division->delete();

        return redirect()
            ->route('admin.divisions.index')
            ->with('status', 'Division deleted successfully.');
    }

    private function validatedData(Request $request, ?Division $division = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('divisions', 'name')->ignore($division)],
        ] + $this->seoValidationRules());

        $data['slug'] = Str::slug($data['name']);
        $data['status'] = $request->boolean('status');

        return $data;
    }
}
