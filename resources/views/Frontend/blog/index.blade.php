@extends('Frontend.layouts.master')

@section('title', (($blogData['page']->hero_title ?? null) ?: 'Blog') . ' | Land Site')
@section('meta_title', ($blogData['page'] ?? null)?->meta_title ?: ($blogData['page'] ?? null)?->seo_title ?: (($blogData['page']->hero_title ?? null) ?: 'Blog'))
@section('meta_description', ($blogData['page'] ?? null)?->meta_description ?: ($blogData['page'] ?? null)?->seo_description ?: 'Fresh blogs, market guides, buying tips, rental advice, and Bangladesh real estate updates.')
@section('keywords', ($blogData['page'] ?? null)?->keywords)
@section('author', ($blogData['page'] ?? null)?->author)
@section('publisher', ($blogData['page'] ?? null)?->publisher)
@section('copyright', ($blogData['page'] ?? null)?->copyright)
@section('site_name', ($blogData['page'] ?? null)?->site_name)
@section('meta_image', ($blogData['page'] ?? null)?->meta_image ?: ($blogData['page'] ?? null)?->hero_background_path)
@section('robots', ($blogData['page'] ?? null)?->robots ?? 'index_follow')
@section('canonical_url', route('frontend.blog.index'))
@section('updated_time', optional(($blogData['page'] ?? null)?->updated_at)->toIso8601String())
@section('body_class', 'frontend-page blog-page')

@php
  $page = $blogData['page'] ?? null;
  $posts = $blogData['posts'] ?? collect();
  $featuredPost = $blogData['featured_post'] ?? null;
  $categories = $blogData['categories'] ?? collect();
  $recentPosts = $blogData['recent_posts'] ?? collect();
  $imageFor = fn ($post, $fallback = 'post_img_1.jpg') => $post?->featured_image_path
      ? asset($post->featured_image_path)
      : asset('frontend/assets/images/'.$fallback);
  $dateFor = fn ($post) => $post?->published_at
      ? $post->published_at->format('F d, Y')
      : $post?->created_at?->format('F d, Y');
  $categoryFor = fn ($post) => optional($post?->category)->name ?: 'Real Estate';
@endphp

@section('content')
  <main data-api-url="{{ route('api.frontend.blog.index') }}">
    <section class="blog-detail-hero">
      <div class="container">
        <a href="{{ route('frontend.home') }}" class="blog-back-link"><i class="bi bi-arrow-left"></i> Back To Home</a>
        <span class="article-category">Land Site Blog</span>
        <h1>{{ $page?->hero_title ?: 'Real Estate Articles & Market Guides' }}</h1>
        <p>Fresh blogs, market guides, buying tips, rental advice, and Bangladesh real estate updates.</p>
      </div>
    </section>

    @if($featuredPost)
      <section class="blog-detail-section">
        <div class="container">
          <div class="row g-5 align-items-center">
            <div class="col-lg-6">
              <img src="{{ $imageFor($featuredPost, 'post_img_1.jpg') }}" alt="{{ $featuredPost->title }}" class="article-cover mb-lg-0">
            </div>
            <div class="col-lg-6">
              <span class="article-category">{{ $categoryFor($featuredPost) }}</span>
              <h2 class="section-title">{{ $featuredPost->title }}</h2>
              <p class="text-secondary">{{ $featuredPost->excerpt }}</p>
              <div class="article-meta mb-4">
                <span><i class="bi bi-person-circle"></i> {{ $featuredPost->author_name }}</span>
                <span><i class="bi bi-calendar3"></i> {{ $dateFor($featuredPost) }}</span>
              </div>
              <a href="{{ route('frontend.blog.show', ['slug' => $featuredPost->slug]) }}" class="btn btn-danger px-4">Read Article</a>
            </div>
          </div>
        </div>
      </section>
    @endif

    <section class="blogs-section">
      <div class="container">
        <div class="row g-5">
          <div class="col-lg-8">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-4">
              <div>
                <h2 class="section-title">{{ $page?->latest_posts_title ?: 'Latest Articles' }}</h2>
                <p class="text-secondary mb-0">Browse the newest real estate insights and property guides.</p>
              </div>
              <form action="{{ route('frontend.blog.index') }}" method="GET" class="d-flex gap-2">
                <input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search articles">
                <button class="btn btn-dark" type="submit"><i class="bi bi-search"></i></button>
              </form>
            </div>

            <div class="row g-4">
              @forelse($posts as $post)
                <div class="col-md-6">
                  <article class="blog-card">
                    <a href="{{ route('frontend.blog.show', ['slug' => $post->slug]) }}" class="blog-image-link" aria-label="Read {{ $post->title }}">
                      <img src="{{ $imageFor($post, 'post_img_'.(($loop->iteration % 3) + 1).'.jpg') }}" alt="{{ $post->title }}">
                    </a>
                    <div class="blog-body">
                      <span>{{ $categoryFor($post) }}</span>
                      <h3><a href="{{ route('frontend.blog.show', ['slug' => $post->slug]) }}">{{ $post->title }}</a></h3>
                      <p>{{ $post->excerpt ?: str($post->content)->stripTags()->limit(130) }}</p>
                      <a href="{{ route('frontend.blog.show', ['slug' => $post->slug]) }}">Read Article <i class="bi bi-arrow-right"></i></a>
                    </div>
                  </article>
                </div>
              @empty
                <div class="col-12">
                  <div class="article-sidebar mb-0">
                    <h2>No blog posts found</h2>
                    <p class="mb-0">Publish blog posts from the admin panel to show them here.</p>
                  </div>
                </div>
              @endforelse
            </div>

            @if(method_exists($posts, 'links') && $posts->hasPages())
              <div class="mt-4">
                {{ $posts->links() }}
              </div>
            @endif
          </div>

          <aside class="col-lg-4">
            <div class="article-sidebar">
              <h2>{{ $page?->categories_title ?: 'Categories' }}</h2>
              @forelse($categories as $category)
                <a href="{{ route('frontend.blog.index', ['category' => $category->slug]) }}">{{ $category->name }} <span class="text-secondary">({{ $category->posts_count }})</span></a>
              @empty
                <p class="mb-0">No categories found.</p>
              @endforelse
            </div>

            <div class="article-sidebar">
              <h2>{{ $page?->recommendation_title ?: 'Recent Articles' }}</h2>
              @forelse($recentPosts as $recentPost)
                <a href="{{ route('frontend.blog.show', ['slug' => $recentPost->slug]) }}">{{ $recentPost->title }}</a>
              @empty
                <p class="mb-0">No recent articles found.</p>
              @endforelse
            </div>
          </aside>
        </div>
      </div>
    </section>
  </main>
@endsection
