@extends('Frontend.layouts.master')

@section('title', 'Favorites | Land Site')
@section('body_class', 'frontend-page user-dashboard-page')

@section('content')
@php
    $user = $dashboardData['user'];
    $profileCompletion = $dashboardData['profile_completion'];
    $avatar = $user->profile_photo_path ? asset($user->profile_photo_path) : asset('frontend/assets/images/avatar_1.jpg');
    $imageFor = function ($property) {
        $media = $property?->media?->firstWhere('is_primary', true) ?: $property?->media?->first();
        return $media ? asset($media->file_path) : asset('frontend/assets/images/property_img_1.jpg');
    };
@endphp

<main class="dashboard-page">
  <section class="dashboard-shell">
    @include('User.partials.sidebar')
    <section class="dashboard-main">
      <div class="dashboard-topbar">
        <div>
          <p>Favorites</p>
          <h2>Saved Properties</h2>
        </div>
      </div>

      <section class="dashboard-card">
        <div class="saved-property-list">
          @forelse($favorites as $favorite)
            @php($property = $favorite->property)
            <article class="saved-property">
              <img src="{{ $imageFor($property) }}" alt="{{ $property?->title ?? 'Saved property' }}">
              <div>
                <h3>{{ $property?->title ?? 'Property not available' }}</h3>
                <p>{{ $property ? collect([$property->area?->name, $property->city?->name, $property->district?->name])->filter()->join(', ') : 'Removed listing' }}</p>
                <span>{{ $property ? 'BDT '.number_format((float) $property->price) : '-' }}</span>
              </div>
              @if($property)
                <a href="{{ $property->detailUrl() }}" aria-label="View property"><i class="bi bi-arrow-right"></i></a>
              @endif
            </article>
          @empty
            <article class="saved-property">
              <img src="{{ asset('frontend/assets/images/property_img_1.jpg') }}" alt="No favorites">
              <div>
                <h3>No favorite properties found</h3>
                <p>Saved properties will appear here.</p>
                <span>Favorites</span>
              </div>
            </article>
          @endforelse
        </div>
        @if(method_exists($favorites, 'hasPages') && $favorites->hasPages())
          <div class="mt-4">{{ $favorites->links() }}</div>
        @endif
      </section>
    </section>
  </section>
</main>
@endsection
