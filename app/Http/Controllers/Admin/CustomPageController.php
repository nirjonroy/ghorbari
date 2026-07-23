<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomPage;
use App\Models\SiteInfo;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CustomPageController extends Controller
{
    public function index(): View
    {
        $pages = CustomPage::query()
            ->latest()
            ->paginate(15);

        return view('Admin.custom_pages.index', compact('pages'));
    }

    public function create(): View
    {
        return view('Admin.custom_pages.create', [
            'page' => new CustomPage(['status' => 'draft', 'template_type' => 'default']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);

        if ($request->hasFile('meta_image')) {
            $data['meta_image'] = $this->storeImage($request);
        }

        CustomPage::create($data);

        return redirect()
            ->route('admin.custom-pages.index')
            ->with('status', 'Custom page created successfully.');
    }

    public function edit(CustomPage $customPage): View
    {
        return view('Admin.custom_pages.edit', ['page' => $customPage]);
    }

    public function update(Request $request, CustomPage $customPage): RedirectResponse
    {
        $data = $this->validatedData($request, $customPage);

        if ($request->hasFile('meta_image')) {
            $data['meta_image'] = $this->storeImage($request, $customPage->meta_image);
        }

        $customPage->update($data);

        return redirect()
            ->route('admin.custom-pages.index')
            ->with('status', 'Custom page updated successfully.');
    }

    public function destroy(CustomPage $customPage): RedirectResponse
    {
        if ($customPage->meta_image && File::exists(public_path($customPage->meta_image))) {
            File::delete(public_path($customPage->meta_image));
        }

        $customPage->delete();

        return redirect()
            ->route('admin.custom-pages.index')
            ->with('status', 'Custom page deleted successfully.');
    }

    private function validatedData(Request $request, ?CustomPage $page = null): array
    {
        $request->merge([
            'url_path' => $this->normalizePath((string) $request->input('url_path')),
        ]);

        $data = $request->validate([
            'page_name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('custom_pages', 'slug')->ignore($page)],
            'url_path' => ['required', 'string', 'max:255', Rule::unique('custom_pages', 'url_path')->ignore($page)],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'short_description' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'meta_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'status' => ['required', 'string', Rule::in(['draft', 'published', 'inactive'])],
            'published_at' => ['nullable', 'date'],
        ]);

        $data['slug'] = $this->uniqueSlug($data['slug'] ?: $data['page_name'], $page);
        $data['template_type'] = 'default';

        unset($data['meta_image']);

        return $data;
    }

    private function uniqueSlug(string $value, ?CustomPage $page = null): string
    {
        $baseSlug = Str::slug($value) ?: 'custom-page';
        $slug = $baseSlug;
        $counter = 2;

        while (CustomPage::query()
            ->where('slug', $slug)
            ->when($page, fn ($query) => $query->whereKeyNot($page->id))
            ->exists()) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    private function normalizePath(string $path): string
    {
        return trim(preg_replace('#/+#', '/', trim($path)), '/');
    }

    private function storeImage(Request $request, ?string $oldPath = null): string
    {
        $siteInfo = SiteInfo::query()->first();

        return (new ImageUploadService())->storeConverted(
            $request->file('meta_image'),
            'uploads/custom-pages',
            $siteInfo?->blog_page_image_width,
            $siteInfo?->blog_page_image_height,
            $oldPath,
            $siteInfo?->image_output_format ?? 'webp'
        );
    }
}
