@extends('Frontend.layouts.master')

@php
  $page = $customPageData['page'];
  $title = $page->meta_title ?: $page->page_name;
  $coverImage = $page->meta_image ? asset($page->meta_image) : null;
@endphp

@section('title', $title . ' | Land Site')
@section('body_class', 'frontend-page blog-detail-page custom-page')

@section('content')
  <main data-api-url="{{ $customPageData['api_url'] }}">
    <section class="blog-detail-hero">
      <div class="container">
        <a href="{{ route('frontend.home') }}" class="blog-back-link"><i class="bi bi-arrow-left"></i> Back To Home</a>
        <span class="article-category">Custom Page</span>
        <h1>{{ $page->page_name }}</h1>
        @if($page->subtitle || $page->short_description)
          <p>{{ $page->subtitle ?: $page->short_description }}</p>
        @endif
        <div class="article-meta">
          <span><i class="bi bi-link-45deg"></i> /{{ $page->url_path }}/</span>
          <span><i class="bi bi-calendar3"></i> {{ optional($page->published_at ?: $page->created_at)->format('F d, Y') }}</span>
          <span><i class="bi bi-clock"></i> {{ max(1, ceil(str_word_count(strip_tags($page->content)) / 200)) }} min read</span>
        </div>
      </div>
    </section>

    <section class="blog-detail-section">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-9">
            <article class="article-content">
              @if($coverImage)
                <img src="{{ $coverImage }}" alt="{{ $page->page_name }}" class="article-cover">
              @endif

              @if($page->short_description)
                <p class="lead">{{ $page->short_description }}</p>
              @endif

              {!! $page->content !!}
            </article>
          </div>
        </div>
      </div>
    </section>
  </main>
@endsection
