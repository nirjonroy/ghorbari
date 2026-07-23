<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ManagesSeoFields;
use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Division;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DistrictController extends Controller
{
    use ManagesSeoFields;

    public function index(): View
    {
        $districts = District::query()
            ->with('division')
            ->withCount('areas')
            ->orderBy('name')
            ->paginate(15);

        return view('Admin.districts.index', compact('districts'));
    }

    public function create(): View
    {
        return view('Admin.districts.create', [
            'district' => new District(),
            'divisions' => $this->divisions(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->applySeoImage($request, $this->validatedData($request), null, 'uploads/locations/seo');

        District::create($data);

        return redirect()
            ->route('admin.districts.index')
            ->with('status', 'District created successfully.');
    }

    public function edit(District $district): View
    {
        return view('Admin.districts.edit', [
            'district' => $district,
            'divisions' => $this->divisions(),
        ]);
    }

    public function update(Request $request, District $district): RedirectResponse
    {
        $data = $this->applySeoImage($request, $this->validatedData($request, $district), $district, 'uploads/locations/seo');

        $district->update($data);

        return redirect()
            ->route('admin.districts.index')
            ->with('status', 'District updated successfully.');
    }

    public function destroy(District $district): RedirectResponse
    {
        $district->delete();

        return redirect()
            ->route('admin.districts.index')
            ->with('status', 'District deleted successfully.');
    }

    private function validatedData(Request $request, ?District $district = null): array
    {
        $data = $request->validate([
            'division_id' => ['required', 'exists:divisions,id'],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('districts', 'name')
                    ->where('division_id', $request->input('division_id'))
                    ->ignore($district),
            ],
        ] + $this->seoValidationRules());

        $data['slug'] = Str::slug($data['name']);
        $data['status'] = $request->boolean('status');

        return $data;
    }

    private function divisions()
    {
        return Division::query()
            ->where('status', true)
            ->orderBy('name')
            ->get();
    }
}
