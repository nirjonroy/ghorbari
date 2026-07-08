<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class SliderController extends Controller
{
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
        return $request->validate([
            'title_one' => ['nullable', 'string', 'max:255'],
            'title_two' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:4096'],
            'link' => ['nullable', 'url', 'max:255'],
            'serial' => ['required', 'integer', 'min:0'],
            'slider_location' => ['nullable', 'string', 'max:255'],
            'product_slug' => ['nullable', 'string', 'max:255'],
        ]);
    }

    private function storeImage(Request $request, ?string $oldPath = null): string
    {
        $directory = public_path('uploads/sliders');

        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        if ($oldPath && File::exists(public_path($oldPath))) {
            File::delete(public_path($oldPath));
        }

        $file = $request->file('image');
        $filename = 'slider-'.time().'-'.uniqid().'.'.$file->getClientOriginalExtension();

        $file->move($directory, $filename);

        return 'uploads/sliders/'.$filename;
    }
}
