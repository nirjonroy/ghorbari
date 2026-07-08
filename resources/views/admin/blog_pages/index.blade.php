@extends('Admin.layouts.master')

@section('title', 'Blog Page Settings')

@section('content')
      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Blog Page Settings</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Blog Page Settings</li>
                </ol>
              </div>
            </div>
          </div>
        </div>

        <div class="app-content">
          <div class="container-fluid">
            <div class="card">
              <div class="card-header d-flex align-items-center">
                <h3 class="card-title">Page Settings</h3>
                <a href="{{ route('admin.blog-pages.edit') }}" class="btn btn-primary btn-sm ms-auto">
                  <i class="bi bi-pencil-square me-1"></i> Edit Page Settings
                </a>
              </div>
              <div class="card-body">
                @if (session('status'))
                  <div class="alert alert-success" role="alert">{{ session('status') }}</div>
                @endif

                @if ($blogPage)
                  <dl class="row mb-0">
                    <dt class="col-sm-3">Hero Title</dt>
                    <dd class="col-sm-9">{{ $blogPage->hero_title }}</dd>

                    <dt class="col-sm-3">Hero Background</dt>
                    <dd class="col-sm-9">{{ $blogPage->hero_background_path ?? 'Not set' }}</dd>

                    <dt class="col-sm-3">Home Section Title</dt>
                    <dd class="col-sm-9">{{ $blogPage->home_section_title ?? 'Not set' }}</dd>

                    <dt class="col-sm-3">Categories Title</dt>
                    <dd class="col-sm-9">{{ $blogPage->categories_title ?? 'Not set' }}</dd>

                    <dt class="col-sm-3">Recommendation Title</dt>
                    <dd class="col-sm-9">{{ $blogPage->recommendation_title ?? 'Not set' }}</dd>

                    <dt class="col-sm-3">Latest Posts Title</dt>
                    <dd class="col-sm-9">{{ $blogPage->latest_posts_title ?? 'Not set' }}</dd>

                    <dt class="col-sm-3">Tags Title</dt>
                    <dd class="col-sm-9">{{ $blogPage->tags_title ?? 'Not set' }}</dd>

                    <dt class="col-sm-3">Read Button Text</dt>
                    <dd class="col-sm-9">{{ $blogPage->read_button_text ?? 'Not set' }}</dd>

                    <dt class="col-sm-3">Article Tags Title</dt>
                    <dd class="col-sm-9">{{ $blogPage->article_tags_title ?? 'Not set' }}</dd>

                    <dt class="col-sm-3">Comments Section Title</dt>
                    <dd class="col-sm-9">{{ $blogPage->comments_section_title ?? 'Not set' }}</dd>
                  </dl>
                @else
                  <p class="mb-0 text-secondary">No blog page settings have been saved yet.</p>
                @endif
              </div>
            </div>
          </div>
        </div>
      </main>
@endsection
