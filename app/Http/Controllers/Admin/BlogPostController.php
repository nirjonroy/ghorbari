<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\SiteInfo;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BlogPostController extends Controller
{
    public function index(): View
    {
        $posts = BlogPost::query()
            ->with('category')
            ->latest()
            ->paginate(15);

        return view('Admin.blog_posts.index', compact('posts'));
    }

    public function create(): View
    {
        return view('Admin.blog_posts.create', [
            'post' => new BlogPost(),
            'categories' => $this->categories(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);

        if ($request->hasFile('featured_image_path')) {
            $data['featured_image_path'] = $this->storeImage($request);
        }

        BlogPost::create($data);

        return redirect()
            ->route('admin.blog-posts.index')
            ->with('status', 'Blog post created successfully.');
    }

    public function edit(BlogPost $blogPost): View
    {
        return view('Admin.blog_posts.edit', [
            'post' => $blogPost,
            'categories' => $this->categories(),
        ]);
    }

    public function update(Request $request, BlogPost $blogPost): RedirectResponse
    {
        $data = $this->validatedData($request, $blogPost);

        if ($request->hasFile('featured_image_path')) {
            $data['featured_image_path'] = $this->storeImage($request, $blogPost->featured_image_path);
        }

        $blogPost->update($data);

        return redirect()
            ->route('admin.blog-posts.index')
            ->with('status', 'Blog post updated successfully.');
    }

    public function destroy(BlogPost $blogPost): RedirectResponse
    {
        if ($blogPost->featured_image_path && File::exists(public_path($blogPost->featured_image_path))) {
            File::delete(public_path($blogPost->featured_image_path));
        }

        $blogPost->delete();

        return redirect()
            ->route('admin.blog-posts.index')
            ->with('status', 'Blog post deleted successfully.');
    }

    private function validatedData(Request $request, ?BlogPost $post = null): array
    {
        $data = $request->validate([
            'blog_category_id' => ['nullable', 'exists:blog_categories,id'],
            'title' => ['required', 'string', 'max:255', Rule::unique('blog_posts', 'title')->ignore($post)],
            'author_name' => ['required', 'string', 'max:255'],
            'excerpt' => ['required', 'string'],
            'content' => ['required', 'string'],
            'quote' => ['nullable', 'string'],
            'featured_image_path' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'featured_image_source' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:255'],
            'tags_input' => ['nullable', 'string'],
            'published_at' => ['nullable', 'date'],
        ]);

        $data['slug'] = Str::slug($data['title']);
        $data['tags'] = $this->commaSeparatedValues($data['tags_input'] ?? null);
        $data['is_published'] = $request->boolean('is_published');
        $data['show_on_home'] = $request->boolean('show_on_home');

        unset($data['tags_input'], $data['featured_image_path']);

        return $data;
    }

    private function storeImage(Request $request, ?string $oldPath = null): string
    {
        $siteInfo = SiteInfo::query()->first();

        return (new ImageUploadService())->storeConverted(
            $request->file('featured_image_path'),
            'uploads/blog/posts',
            null,
            null,
            $oldPath,
            $siteInfo?->image_output_format ?? 'webp'
        );
    }

    private function commaSeparatedValues(?string $value): ?array
    {
        if (! $value) {
            return null;
        }

        return collect(explode(',', $value))
            ->map(fn ($item) => trim($item))
            ->filter()
            ->values()
            ->all();
    }

    private function categories()
    {
        return BlogCategory::query()
            ->where('is_active', true)
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();
    }
}
