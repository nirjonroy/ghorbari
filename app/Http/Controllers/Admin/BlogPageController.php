<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPage;
use App\Models\SiteInfo;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogPageController extends Controller
{
    public function index(): View
    {
        $blogPage = BlogPage::query()->first();

        return view('Admin.blog_pages.index', compact('blogPage'));
    }

    public function edit(): View
    {
        $blogPage = BlogPage::query()->firstOrNew();

        return view('Admin.blog_pages.edit', compact('blogPage'));
    }

    public function update(Request $request): RedirectResponse
    {
        $blogPage = BlogPage::query()->first();

        $data = $request->validate([
            'hero_title' => ['required', 'string', 'max:255'],
            'hero_background_path' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'hero_background_source' => ['nullable', 'string', 'max:255'],
            'home_section_title' => ['nullable', 'string', 'max:255'],
            'categories_title' => ['nullable', 'string', 'max:255'],
            'recommendation_title' => ['nullable', 'string', 'max:255'],
            'latest_posts_title' => ['nullable', 'string', 'max:255'],
            'tags_title' => ['nullable', 'string', 'max:255'],
            'read_button_text' => ['nullable', 'string', 'max:255'],
            'article_tags_title' => ['nullable', 'string', 'max:255'],
            'comments_section_title' => ['nullable', 'string', 'max:255'],
        ]);

        unset($data['hero_background_path']);

        if ($request->hasFile('hero_background_path')) {
            $siteInfo = SiteInfo::query()->first();

            $data['hero_background_path'] = (new ImageUploadService())->storeConverted(
                $request->file('hero_background_path'),
                'uploads/blog/page',
                null,
                null,
                $blogPage?->hero_background_path,
                $siteInfo?->image_output_format ?? 'webp'
            );
        }

        BlogPage::query()->updateOrCreate(
            ['id' => optional($blogPage)->id],
            $data
        );

        return redirect()
            ->route('admin.blog-pages.index')
            ->with('status', 'Blog page settings updated successfully.');
    }
}
