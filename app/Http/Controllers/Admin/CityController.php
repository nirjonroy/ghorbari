<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\District;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CityController extends Controller
{
    public function index(): View
    {
        $cities = City::query()
            ->with('district.division')
            ->withCount('areas')
            ->orderBy('name')
            ->paginate(15);

        return view('Admin.cities.index', compact('cities'));
    }

    public function create(): View
    {
        return view('Admin.cities.create', [
            'city' => new City(),
            'districts' => $this->districts(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        City::create($this->validatedData($request));

        return redirect()
            ->route('admin.cities.index')
            ->with('status', 'City created successfully.');
    }

    public function edit(City $city): View
    {
        return view('Admin.cities.edit', [
            'city' => $city,
            'districts' => $this->districts(),
        ]);
    }

    public function update(Request $request, City $city): RedirectResponse
    {
        $city->update($this->validatedData($request, $city));

        return redirect()
            ->route('admin.cities.index')
            ->with('status', 'City updated successfully.');
    }

    public function destroy(City $city): RedirectResponse
    {
        $city->delete();

        return redirect()
            ->route('admin.cities.index')
            ->with('status', 'City deleted successfully.');
    }

    private function validatedData(Request $request, ?City $city = null): array
    {
        $data = $request->validate([
            'district_id' => ['required', 'exists:districts,id'],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('cities', 'name')
                    ->where('district_id', $request->input('district_id'))
                    ->ignore($city),
            ],
        ]);

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
}
