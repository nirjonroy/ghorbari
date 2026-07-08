<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BlogCategoryController extends Controller
{
    public function index(): View
    {
        $categories = BlogCategory::query()
            ->withCount('posts')
            ->orderBy('display_order')
            ->orderBy('name')
            ->paginate(15);

        return view('Admin.blog_categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('Admin.blog_categories.create', ['category' => new BlogCategory()]);
    }

    public function store(Request $request): RedirectResponse
    {
        BlogCategory::create($this->validatedData($request));

        return redirect()
            ->route('admin.blog-categories.index')
            ->with('status', 'Blog category created successfully.');
    }

    public function edit(BlogCategory $blogCategory): View
    {
        return view('Admin.blog_categories.edit', ['category' => $blogCategory]);
    }

    public function update(Request $request, BlogCategory $blogCategory): RedirectResponse
    {
        $blogCategory->update($this->validatedData($request, $blogCategory));

        return redirect()
            ->route('admin.blog-categories.index')
            ->with('status', 'Blog category updated successfully.');
    }

    public function destroy(BlogCategory $blogCategory): RedirectResponse
    {
        $blogCategory->delete();

        return redirect()
            ->route('admin.blog-categories.index')
            ->with('status', 'Blog category deleted successfully.');
    }

    private function validatedData(Request $request, ?BlogCategory $category = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('blog_categories', 'name')->ignore($category)],
            'description' => ['nullable', 'string'],
            'display_order' => ['required', 'integer', 'min:0'],
        ]);

        $data['slug'] = Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
