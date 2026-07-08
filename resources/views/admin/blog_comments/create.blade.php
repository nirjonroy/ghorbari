@extends('Admin.layouts.master')

@section('title', 'Add Blog Comment')

@section('content')
      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Add Blog Comment</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('admin.blog-comments.index') }}">Blog Comments</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Add</li>
                </ol>
              </div>
            </div>
          </div>
        </div>

        <div class="app-content">
          <div class="container-fluid">
            <form method="POST" action="{{ route('admin.blog-comments.store') }}">
              @include('Admin.blog_comments.partials.form', ['title' => 'Comment Information'])
            </form>
          </div>
        </div>
      </main>
@endsection
