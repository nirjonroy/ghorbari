<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ManagesSeoFields;
use App\Http\Controllers\Controller;
use App\Models\Amenity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AmenityController extends Controller
{
    use ManagesSeoFields;

    public function index(): View
    {
        $amenities = Amenity::query()
            ->orderBy('name')
            ->paginate(15);

        return view('Admin.amenities.index', compact('amenities'));
    }

    public function create(): View
    {
        return view('Admin.amenities.create', ['amenity' => new Amenity()]);
    }

    public function store(Request $request): RedirectResponse
    {
        Amenity::create($this->applySeoImage($request, $this->validatedData($request), null, 'uploads/amenities/seo'));

        return redirect()
            ->route('admin.amenities.index')
            ->with('status', 'Amenity created successfully.');
    }

    public function show(Amenity $amenity)
    {
        abort(404);
    }

    public function edit(Amenity $amenity): View
    {
        return view('Admin.amenities.edit', compact('amenity'));
    }

    public function update(Request $request, Amenity $amenity): RedirectResponse
    {
        $amenity->update($this->applySeoImage($request, $this->validatedData($request, $amenity), $amenity, 'uploads/amenities/seo'));

        return redirect()
            ->route('admin.amenities.index')
            ->with('status', 'Amenity updated successfully.');
    }

    public function destroy(Amenity $amenity): RedirectResponse
    {
        $amenity->delete();

        return redirect()
            ->route('admin.amenities.index')
            ->with('status', 'Amenity deleted successfully.');
    }

    private function validatedData(Request $request, ?Amenity $amenity = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['nullable', 'string', 'max:120', Rule::unique('amenities', 'slug')->ignore($amenity)],
            'icon' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:50'],
        ] + $this->seoValidationRules());

        unset($data['meta_image']);
        $data['slug'] = $this->uniqueSlug($data['slug'] ?: $data['name'], $amenity);

        return $data;
    }

    private function uniqueSlug(string $value, ?Amenity $amenity = null): string
    {
        $baseSlug = Str::slug($value) ?: 'amenity';
        $slug = $baseSlug;
        $counter = 2;

        while (Amenity::query()
            ->where('slug', $slug)
            ->when($amenity, fn ($query) => $query->where('id', '!=', $amenity->id))
            ->exists()) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}
