@extends('Frontend.layouts.master')

@section('title', 'Feed | Land Site')
@section('body_class', 'frontend-page user-dashboard-page')

@section('content')
@php
    $user = $dashboardData['user'];
    $profileCompletion = $dashboardData['profile_completion'];
    $avatar = $user->profile_photo_path ? asset($user->profile_photo_path) : asset('frontend/assets/images/avatar_1.jpg');
    $imageFor = function ($property) {
        $media = $property->media->firstWhere('is_primary', true) ?: $property->media->first();
        return $media ? asset($media->file_path) : asset('frontend/assets/images/property_img_1.jpg');
    };
@endphp

<main class="dashboard-page">
  <section class="dashboard-shell">
    @include('User.partials.sidebar')
    <section class="dashboard-main">
      <div class="dashboard-topbar">
        <div>
          <p>Feed</p>
          <h2>Latest Published Properties</h2>
        </div>
      </div>

      <section class="dashboard-card">
        <div class="saved-property-list">
          @forelse($properties as $property)
            <article class="saved-property">
              <img src="{{ $imageFor($property) }}" alt="{{ $property->title }}">
              <div>
                <h3>{{ $property->title }}</h3>
                <p>{{ collect([$property->area?->name, $property->city?->name, $property->district?->name])->filter()->join(', ') ?: optional($property->type)->name }}</p>
                <span>BDT {{ number_format((float) $property->price) }}</span>
              </div>
              <a href="{{ $property->detailUrl() }}" aria-label="View property"><i class="bi bi-arrow-right"></i></a>
            </article>
          @empty
            <article class="saved-property">
              <img src="{{ asset('frontend/assets/images/property_img_1.jpg') }}" alt="No feed">
              <div>
                <h3>No published properties found</h3>
                <p>Approved listings will appear in your feed.</p>
                <span>Feed</span>
              </div>
            </article>
          @endforelse
        </div>
      </section>
    </section>
  </section>
</main>
@endsection
