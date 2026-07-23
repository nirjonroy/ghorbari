<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ManagesSeoFields;
use App\Http\Controllers\Controller;
use App\Models\SiteInfo;
use App\Models\Slider;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class SliderController extends Controller
{
    use ManagesSeoFields;

    public function index(): View
    {
        $sliders = Slider::query()
            ->orderBy('serial')
            ->orderByDesc('id')
            ->paginate(15);

        return view('Admin.sliders.index', compact('sliders'));
    }

    public function create(): View
    {
        return view('Admin.sliders.create', ['slider' => new Slider()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['status'] = $request->boolean('status');

        if ($request->hasFile('image')) {
            $data['image'] = $this->storeImage($request);
        }
        $data = $this->applySeoImage($request, $data, null, 'uploads/sliders/seo');

        Slider::create($data);

        return redirect()
            ->route('admin.sliders.index')
            ->with('status', 'Slider created successfully.');
    }

    public function edit(Slider $slider): View
    {
        return view('Admin.sliders.edit', compact('slider'));
    }

    public function update(Request $request, Slider $slider): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['status'] = $request->boolean('status');

        if ($request->hasFile('image')) {
            $data['image'] = $this->storeImage($request, $slider->image);
        }
        $data = $this->applySeoImage($request, $data, $slider, 'uploads/sliders/seo');

        $slider->update($data);

        return redirect()
            ->route('admin.sliders.index')
            ->with('status', 'Slider updated successfully.');
    }

    public function destroy(Slider $slider): RedirectResponse
    {
        if ($slider->image && File::exists(public_path($slider->image))) {
            File::delete(public_path($slider->image));
        }

        $slider->delete();

        return redirect()
            ->route('admin.sliders.index')
            ->with('status', 'Slider deleted successfully.');
    }

    private function validatedData(Request $request): array
    {
        $data = $request->validate([
            'title_one' => ['nullable', 'string', 'max:255'],
            'title_two' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'link' => ['nullable', 'url', 'max:255'],
            'serial' => ['required', 'integer', 'min:0'],
            'slider_location' => ['nullable', 'string', 'max:255'],
            'product_slug' => ['nullable', 'string', 'max:255'],
        ] + $this->seoValidationRules());

        unset($data['meta_image']);

        return $data;
    }

    private function storeImage(Request $request, ?string $oldPath = null): string
    {
        $siteInfo = SiteInfo::query()->first();

        return (new ImageUploadService())->storeConverted(
            $request->file('image'),
            'uploads/sliders',
            $siteInfo?->slider_width,
            $siteInfo?->slider_height,
            $oldPath,
            $siteInfo?->image_output_format ?? 'webp'
        );
    }
}
