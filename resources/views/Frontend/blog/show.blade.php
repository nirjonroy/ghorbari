@extends('Frontend.layouts.master')

@section('title', $blogData['post']->title . ' | Land Site')
@section('body_class', 'frontend-page blog-detail-page')

@php
  $page = $blogData['page'] ?? null;
  $post = $blogData['post'];
  $categories = $blogData['categories'] ?? collect();
  $recentPosts = $blogData['recent_posts'] ?? collect();
  $relatedPosts = $blogData['related_posts'] ?? collect();
  $comments = collect($blogData['comments'] ?? []);
  $tags = collect($post->tags ?? [])->filter();
  $imageFor = fn ($item, $fallback = 'post_img_1.jpg') => $item?->featured_image_path
      ? asset($item->featured_image_path)
      : asset('frontend/assets/images/'.$fallback);
  $dateFor = fn ($item) => $item?->published_at
      ? $item->published_at->format('F d, Y')
      : $item?->created_at?->format('F d, Y');
  $categoryFor = fn ($item) => optional($item?->category)->name ?: 'Real Estate';
@endphp

@section('content')
  <main data-api-url="{{ route('api.frontend.blog.show', ['slug' => $post->slug]) }}">
    <section class="blog-detail-hero">
      <div class="container">
        <a href="{{ route('frontend.blog.index') }}" class="blog-back-link"><i class="bi bi-arrow-left"></i> Back To Blog</a>
        <span class="article-category">{{ $categoryFor($post) }}</span>
        <h1>{{ $post->title }}</h1>
        <p>{{ $post->excerpt }}</p>
        <div class="article-meta">
          <span><i class="bi bi-person-circle"></i> {{ $post->author_name }}</span>
          <span><i class="bi bi-calendar3"></i> {{ $dateFor($post) }}</span>
          <span><i class="bi bi-clock"></i> {{ max(1, ceil(str_word_count(strip_tags($post->content)) / 200)) }} min read</span>
        </div>
      </div>
    </section>

    <section class="blog-detail-section">
      <div class="container">
        <div class="row g-5">
          <div class="col-lg-8">
            <article class="article-content">
              <img src="{{ $imageFor($post, 'post_img_1.jpg') }}" alt="{{ $post->title }}" class="article-cover">
              @if($post->excerpt)
                <p class="lead">{{ $post->excerpt }}</p>
              @endif

              {!! $post->content !!}

              @if($post->quote)
                <blockquote>{{ $post->quote }}</blockquote>
              @endif

              @if($tags->isNotEmpty())
                <div class="mt-4">
                  <h2>{{ $page?->article_tags_title ?: 'Article Tags' }}</h2>
                  <div class="d-flex flex-wrap gap-2">
                    @foreach($tags as $tag)
                      <span class="article-category mb-0">{{ $tag }}</span>
                    @endforeach
                  </div>
                </div>
              @endif
            </article>
          </div>

          <aside class="col-lg-4">
            <div class="article-sidebar">
              <h2>Need Local Guidance?</h2>
              <p>Connect with a Land Site advisor to compare neighborhoods, pricing, and documents before you decide.</p>
              <button class="btn btn-danger w-100" type="button" data-bs-toggle="modal" data-bs-target="#authModal">Talk To An Agent</button>
            </div>

            <div class="article-sidebar">
              <h2>{{ $page?->categories_title ?: 'Popular Topics' }}</h2>
              @forelse($categories as $category)
                <a href="{{ route('frontend.blog.index', ['category' => $category->slug]) }}">{{ $category->name }}</a>
              @empty
                <p class="mb-0">No categories found.</p>
              @endforelse
            </div>

            <div class="article-sidebar">
              <h2>{{ $page?->latest_posts_title ?: 'Latest Posts' }}</h2>
              @forelse($recentPosts as $recentPost)
                <a href="{{ route('frontend.blog.show', ['slug' => $recentPost->slug]) }}">{{ $recentPost->title }}</a>
              @empty
                <p class="mb-0">No recent posts found.</p>
              @endforelse
            </div>
          </aside>
        </div>
      </div>
    </section>

    @if($comments->isNotEmpty())
      <section class="related-articles-section">
        <div class="container">
          <h2 class="section-title">{{ $page?->comments_section_title ?: 'Comments' }}</h2>
          <div class="row g-4">
            @foreach($comments as $comment)
              <div class="col-md-6">
                <div class="article-sidebar h-100 mb-0">
                  <h2>{{ $comment->name ?: optional($comment->user)->name ?: 'Reader' }}</h2>
                  <p class="mb-0">{{ $comment->comment }}</p>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </section>
    @endif

    <section class="related-articles-section">
      <div class="container">
        <h2 class="section-title">Related Articles</h2>
        <div class="row g-4">
          @forelse($relatedPosts as $relatedPost)
            <div class="col-md-6 col-lg-4">
              <article class="blog-card">
                <a href="{{ route('frontend.blog.show', ['slug' => $relatedPost->slug]) }}" class="blog-image-link" aria-label="Read {{ $relatedPost->title }}">
                  <img src="{{ $imageFor($relatedPost, 'post_img_'.(($loop->iteration % 3) + 1).'.jpg') }}" alt="{{ $relatedPost->title }}">
                </a>
                <div class="blog-body">
                  <span>{{ $categoryFor($relatedPost) }}</span>
                  <h3><a href="{{ route('frontend.blog.show', ['slug' => $relatedPost->slug]) }}">{{ $relatedPost->title }}</a></h3>
                  <p>{{ $relatedPost->excerpt ?: str($relatedPost->content)->stripTags()->limit(120) }}</p>
                  <a href="{{ route('frontend.blog.show', ['slug' => $relatedPost->slug]) }}">Read Article <i class="bi bi-arrow-right"></i></a>
                </div>
              </article>
            </div>
          @empty
            <div class="col-12">
              <div class="article-sidebar mb-0">
                <p class="mb-0">No related articles found.</p>
              </div>
            </div>
          @endforelse
        </div>
      </div>
    </section>
  </main>
@endsection
