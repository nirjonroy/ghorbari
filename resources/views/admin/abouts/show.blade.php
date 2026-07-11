@extends('Admin.layouts.master')

@section('title', 'About Content Details')

@section('content')
      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">About Content Details</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('admin.abouts.index') }}">About Us</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Details</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <div class="app-content">
          <div class="container-fluid">
            <div class="card">
              <div class="card-header d-flex align-items-center">
                <h3 class="card-title">{{ $about->title }}</h3>
                <a href="{{ route('admin.abouts.edit', $about) }}" class="btn btn-warning btn-sm ms-auto"><i class="bi bi-pencil-square"></i> Edit</a>
              </div>
              <div class="card-body">
                <div class="row g-4">
                  @if ($about->image)
                    <div class="col-md-4">
                      <img src="{{ asset($about->image) }}" alt="{{ $about->image_alt_text ?? $about->title }}" class="img-fluid rounded">
                    </div>
                  @endif
                  <div class="{{ $about->image ? 'col-md-8' : 'col-12' }}">
                    <div class="mb-2"><strong>Slug:</strong> {{ $about->slug }}</div>
                    <div class="mb-2"><strong>Subtitle:</strong> {{ $about->subtitle ?? 'Not set' }}</div>
                    <div class="mb-2"><strong>Status:</strong> {{ ucfirst($about->status) }}</div>
                    <div class="mb-2"><strong>Display Order:</strong> {{ $about->display_order }}</div>
                    <div class="mb-3"><strong>Short Description:</strong><div>{{ $about->short_description ?? 'Not set' }}</div></div>
                    <div><strong>Long Description:</strong><div class="mt-2">{!! nl2br(e($about->long_description)) !!}</div></div>
                  </div>
                  <div class="col-md-6">
                    <h5>{{ $about->mission_title ?? 'Mission' }}</h5>
                    <div>{!! nl2br(e($about->mission_description ?? 'Not set')) !!}</div>
                  </div>
                  <div class="col-md-6">
                    <h5>{{ $about->vision_title ?? 'Vision' }}</h5>
                    <div>{!! nl2br(e($about->vision_description ?? 'Not set')) !!}</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
@endsection
