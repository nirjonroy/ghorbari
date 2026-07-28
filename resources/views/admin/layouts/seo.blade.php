@extends('Admin.layouts.master')

@section('title', $title ?? 'SEO Manager')

@section('content')
      <main class="app-main">
        <div class="app-content">
          <div class="container-fluid py-3">
            @yield('seo_content')
          </div>
        </div>
      </main>
@endsection
