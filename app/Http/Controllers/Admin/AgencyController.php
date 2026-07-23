<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ManagesSeoFields;
use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\SiteInfo;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AgencyController extends Controller
{
    use ManagesSeoFields;

    public function index(): View
    {
        $agencies = Agency::query()
            ->withCount('agents')
            ->orderBy('name')
            ->paginate(15);

        return view('Admin.agencies.index', compact('agencies'));
    }

    public function create(): View
    {
        return view('Admin.agencies.create', ['agency' => new Agency()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);

        if ($request->hasFile('logo')) {
            $data['logo'] = $this->storeLogo($request);
        }
        $data = $this->applySeoImage($request, $data, null, 'uploads/agencies/seo');

        Agency::create($data);

        return redirect()->route('admin.agencies.index')->with('status', 'Agency created successfully.');
    }

    public function edit(Agency $agency): View
    {
        return view('Admin.agencies.edit', compact('agency'));
    }

    public function update(Request $request, Agency $agency): RedirectResponse
    {
        $data = $this->validatedData($request, $agency);

        if ($request->hasFile('logo')) {
            $data['logo'] = $this->storeLogo($request, $agency->logo);
        }
        $data = $this->applySeoImage($request, $data, $agency, 'uploads/agencies/seo');

        $agency->update($data);

        return redirect()->route('admin.agencies.index')->with('status', 'Agency updated successfully.');
    }

    public function destroy(Agency $agency): RedirectResponse
    {
        if ($agency->logo && File::exists(public_path($agency->logo))) {
            File::delete(public_path($agency->logo));
        }

        $agency->delete();

        return redirect()->route('admin.agencies.index')->with('status', 'Agency deleted successfully.');
    }

    private function validatedData(Request $request, ?Agency $agency = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'string', 'max:180', Rule::unique('agencies', 'slug')->ignore($agency)],
            'email' => ['nullable', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'website' => ['nullable', 'url', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'string', 'max:50'],
        ] + $this->seoValidationRules());

        unset($data['logo'], $data['meta_image']);
        $data['slug'] = $this->uniqueSlug($data['slug'] ?: $data['name'], $agency);

        return $data;
    }

    private function uniqueSlug(string $value, ?Agency $agency = null): string
    {
        $baseSlug = Str::slug($value) ?: 'agency';
        $slug = $baseSlug;
        $counter = 2;

        while (Agency::query()
            ->where('slug', $slug)
            ->when($agency, fn ($query) => $query->where('id', '!=', $agency->id))
            ->exists()) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    private function storeLogo(Request $request, ?string $oldPath = null): string
    {
        $siteInfo = SiteInfo::query()->first();

        return (new ImageUploadService())->storeConverted(
            $request->file('logo'),
            'uploads/agencies',
            $siteInfo?->agency_logo_width,
            $siteInfo?->agency_logo_height,
            $oldPath,
            $siteInfo?->image_output_format ?? 'webp'
        );
    }
}
