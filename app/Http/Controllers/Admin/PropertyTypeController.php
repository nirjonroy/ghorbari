<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ManagesSeoFields;
use App\Http\Controllers\Controller;
use App\Models\PropertyType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PropertyTypeController extends Controller
{
    use ManagesSeoFields;

    public function index(): View
    {
        $propertyTypes = PropertyType::query()
            ->orderBy('name')
            ->paginate(15);

        return view('Admin.property_types.index', compact('propertyTypes'));
    }

    public function create(): View
    {
        return view('Admin.property_types.create', ['propertyType' => new PropertyType()]);
    }

    public function store(Request $request): RedirectResponse
    {
        PropertyType::create($this->applySeoImage($request, $this->validatedData($request), null, 'uploads/property-types/seo'));

        return redirect()
            ->route('admin.property-types.index')
            ->with('status', 'Property type created successfully.');
    }

    public function show(PropertyType $propertyType)
    {
        abort(404);
    }

    public function edit(PropertyType $propertyType): View
    {
        return view('Admin.property_types.edit', compact('propertyType'));
    }

    public function update(Request $request, PropertyType $propertyType): RedirectResponse
    {
        $propertyType->update($this->applySeoImage($request, $this->validatedData($request, $propertyType), $propertyType, 'uploads/property-types/seo'));

        return redirect()
            ->route('admin.property-types.index')
            ->with('status', 'Property type updated successfully.');
    }

    public function destroy(PropertyType $propertyType): RedirectResponse
    {
        $propertyType->delete();

        return redirect()
            ->route('admin.property-types.index')
            ->with('status', 'Property type deleted successfully.');
    }

    private function validatedData(Request $request, ?PropertyType $propertyType = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['nullable', 'string', 'max:120', Rule::unique('property_types', 'slug')->ignore($propertyType)],
            'icon' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:50'],
        ] + $this->seoValidationRules());

        unset($data['meta_image']);
        $data['slug'] = $this->uniqueSlug($data['slug'] ?: $data['name'], $propertyType);

        return $data;
    }

    private function uniqueSlug(string $value, ?PropertyType $propertyType = null): string
    {
        $baseSlug = Str::slug($value) ?: 'property-type';
        $slug = $baseSlug;
        $counter = 2;

        while (PropertyType::query()
            ->where('slug', $slug)
            ->when($propertyType, fn ($query) => $query->where('id', '!=', $propertyType->id))
            ->exists()) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}
