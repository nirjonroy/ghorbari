<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\SiteInfo;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function index(): View
    {
        $abouts = About::query()
            ->orderBy('display_order')
            ->orderByDesc('id')
            ->paginate(15);

        return view('Admin.abouts.index', compact('abouts'));
    }

    public function create(): View
    {
        return view('Admin.abouts.create', ['about' => new About()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);

        if ($request->hasFile('image')) {
            $data['image'] = $this->storeImage($request);
        }

        About::create($data);

        return redirect()
            ->route('admin.abouts.index')
            ->with('status', 'About content created successfully.');
    }

    public function show(About $about): View
    {
        return view('Admin.abouts.show', compact('about'));
    }

    public function edit(About $about): View
    {
        return view('Admin.abouts.edit', compact('about'));
    }

    public function update(Request $request, About $about): RedirectResponse
    {
        $data = $this->validatedData($request, $about);

        if ($request->hasFile('image')) {
            $data['image'] = $this->storeImage($request, $about->image);
        }

        $about->update($data);

        return redirect()
            ->route('admin.abouts.index')
            ->with('status', 'About content updated successfully.');
    }

    public function destroy(About $about): RedirectResponse
    {
        if ($about->image && File::exists(public_path($about->image))) {
            File::delete(public_path($about->image));
        }

        $about->delete();

        return redirect()
            ->route('admin.abouts.index')
            ->with('status', 'About content deleted successfully.');
    }

    private function validatedData(Request $request, ?About $about = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('abouts', 'slug')->ignore($about)],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'short_description' => ['nullable', 'string'],
            'long_description' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'image_alt_text' => ['nullable', 'string', 'max:255'],
            'mission_title' => ['nullable', 'string', 'max:255'],
            'mission_description' => ['nullable', 'string'],
            'vision_title' => ['nullable', 'string', 'max:255'],
            'vision_description' => ['nullable', 'string'],
            'display_order' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'string', 'max:50'],
        ]);

        unset($data['image']);
        $data['slug'] = $this->uniqueSlug($data['slug'] ?: $data['title'], $about);

        return $data;
    }

    private function uniqueSlug(string $value, ?About $about = null): string
    {
        $baseSlug = Str::slug($value) ?: 'about';
        $slug = $baseSlug;
        $counter = 2;

        while (About::query()
            ->where('slug', $slug)
            ->when($about, fn ($query) => $query->where('id', '!=', $about->id))
            ->exists()) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    private function storeImage(Request $request, ?string $oldPath = null): string
    {
        $siteInfo = SiteInfo::query()->first();

        return (new ImageUploadService())->storeConverted(
            $request->file('image'),
            'uploads/abouts',
            null,
            null,
            $oldPath,
            $siteInfo?->image_output_format ?? 'webp'
        );
    }
}
