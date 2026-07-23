<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ManagesSeoFields;
use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\City;
use App\Models\District;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AreaController extends Controller
{
    use ManagesSeoFields;

    public function index(): View
    {
        $areas = Area::query()
            ->with(['district.division', 'city'])
            ->orderBy('name')
            ->paginate(15);

        return view('Admin.areas.index', compact('areas'));
    }

    public function create(): View
    {
        return view('Admin.areas.create', [
            'area' => new Area(),
            'districts' => $this->districts(),
            'cities' => $this->cities(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->applySeoImage($request, $this->validatedData($request), null, 'uploads/locations/seo');

        Area::create($data);

        return redirect()
            ->route('admin.areas.index')
            ->with('status', 'Area created successfully.');
    }

    public function edit(Area $area): View
    {
        return view('Admin.areas.edit', [
            'area' => $area,
            'districts' => $this->districts(),
            'cities' => $this->cities(),
        ]);
    }

    public function update(Request $request, Area $area): RedirectResponse
    {
        $data = $this->applySeoImage($request, $this->validatedData($request, $area), $area, 'uploads/locations/seo');

        $area->update($data);

        return redirect()
            ->route('admin.areas.index')
            ->with('status', 'Area updated successfully.');
    }

    public function destroy(Area $area): RedirectResponse
    {
        $area->delete();

        return redirect()
            ->route('admin.areas.index')
            ->with('status', 'Area deleted successfully.');
    }

    private function validatedData(Request $request, ?Area $area = null): array
    {
        $data = $request->validate([
            'district_id' => ['required', 'exists:districts,id'],
            'city_id' => ['nullable', 'exists:cities,id'],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('areas', 'name')
                    ->where('district_id', $request->input('district_id'))
                    ->ignore($area),
            ],
            'post_office' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:255'],
        ] + $this->seoValidationRules());

        $data['slug'] = Str::slug($data['name']);
        $data['status'] = $request->boolean('status');

        return $data;
    }

    private function districts()
    {
        return District::query()
            ->with('division')
            ->where('status', true)
            ->orderBy('name')
            ->get();
    }

    private function cities()
    {
        return City::query()
            ->with('district')
            ->where('status', true)
            ->orderBy('name')
            ->get();
    }
}
