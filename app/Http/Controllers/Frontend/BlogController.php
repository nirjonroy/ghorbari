<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPage;
use App\Models\BlogPost;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(Request $request): View
    {
        return view('Frontend.blog.index', [
            'blogData' => $this->blogIndexData($request),
        ]);
    }

    public function show(string $slug): View
    {
        return view('Frontend.blog.show', [
            'blogData' => $this->blogDetailData($slug),
        ]);
    }

    public function apiIndex(Request $request): JsonResponse
    {
        return response()->json([
            'data' => $this->blogIndexData($request),
        ]);
    }

    public function apiShow(string $slug): JsonResponse
    {
        return response()->json([
            'data' => $this->blogDetailData($slug),
        ]);
    }

    private function blogIndexData(Request $request): array
    {
        if (! $this->tableExists(BlogPost::class)) {
            return [
                'page' => null,
                'featured_post' => null,
                'posts' => collect(),
                'categories' => collect(),
                'recent_posts' => collect(),
            ];
        }

        $posts = $this->publishedPosts()
            ->when($request->filled('category'), function (Builder $query) use ($request) {
                $query->whereHas('category', fn (Builder $category) => $category->where('slug', $request->string('category')));
            })
            ->when($request->filled('q'), function (Builder $query) use ($request) {
                $keyword = '%' . $request->string('q') . '%';
                $query->where(function (Builder $inner) use ($keyword) {
                    $inner->where('title', 'like', $keyword)
                        ->orWhere('excerpt', 'like', $keyword)
                        ->orWhere('content', 'like', $keyword);
                });
            })
            ->paginate(9)
            ->withQueryString();

        return [
            'page' => $this->tableExists(BlogPage::class) ? BlogPage::query()->first() : null,
            'featured_post' => $this->publishedPosts()->where('show_on_home', true)->first()
                ?: $this->publishedPosts()->first(),
            'posts' => $posts,
            'categories' => $this->categories(),
            'recent_posts' => $this->recentPosts(),
        ];
    }

    private function blogDetailData(string $slug): array
    {
        abort_unless($this->tableExists(BlogPost::class), 404);

        $post = $this->publishedPosts()
            ->with(['comments' => fn ($query) => $query->where('is_approved', true)->latest()])
            ->where('slug', $slug)
            ->firstOrFail();

        return [
            'page' => $this->tableExists(BlogPage::class) ? BlogPage::query()->first() : null,
            'post' => $post,
            'comments' => $post->getRelations()['comments'] ?? collect(),
            'categories' => $this->categories(),
            'recent_posts' => $this->recentPosts($post->id),
            'related_posts' => $this->publishedPosts()
                ->whereKeyNot($post->id)
                ->when($post->blog_category_id, fn (Builder $query) => $query->where('blog_category_id', $post->blog_category_id))
                ->take(3)
                ->get(),
        ];
    }

    private function publishedPosts(): Builder
    {
        return BlogPost::query()
            ->where('is_published', true)
            ->with('category:id,name,slug')
            ->orderByRaw('published_at IS NULL')
            ->latest('published_at')
            ->latest();
    }

    private function categories()
    {
        if (! $this->tableExists(BlogCategory::class)) {
            return collect();
        }

        return BlogCategory::query()
            ->where('is_active', true)
            ->withCount(['posts' => fn (Builder $query) => $query->where('is_published', true)])
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();
    }

    private function recentPosts(?int $exceptId = null)
    {
        return $this->publishedPosts()
            ->when($exceptId, fn (Builder $query) => $query->whereKeyNot($exceptId))
            ->take(5)
            ->get();
    }

    private function tableExists(string $model): bool
    {
        return Schema::hasTable((new $model())->getTable());
    }
}
