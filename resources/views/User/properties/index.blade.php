@extends('Frontend.layouts.master')

@section('title', $statusTitle.' | Land Site')
@section('body_class', 'frontend-page user-dashboard-page')

@section('content')
@php
    $user = $dashboardData['user'];
    $stats = $dashboardData['stats'];
    $profileCompletion = $dashboardData['profile_completion'];
    $avatar = $user->profile_photo_path ? asset($user->profile_photo_path) : asset('frontend/assets/images/avatar_1.jpg');
    $imageFor = function ($property) {
        $media = $property->media->firstWhere('is_primary', true) ?: $property->media->first();
        return $media ? asset($media->file_path) : asset('frontend/assets/images/property_img_1.jpg');
    };
    $priceFor = function ($property) {
        $price = 'BDT '.number_format((float) $property->price);
        return $property->listing_type === 'rent' ? $price.' / '.($property->rent_period ?: 'month') : $price;
    };
@endphp

<main class="dashboard-page">
  <section class="dashboard-shell">
    @include('User.partials.sidebar')

    <section class="dashboard-main">
      @if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
      @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

      <div class="dashboard-topbar">
        <div>
          <p>Properties</p>
          <h2>{{ $statusTitle }}</h2>
        </div>
        <div class="dashboard-actions">
          <a class="btn btn-danger" href="{{ route('user.properties.create') }}"><i class="bi bi-plus-circle"></i> Add Property</a>
        </div>
      </div>

      <div class="d-flex flex-wrap gap-2 mb-4">
        <a class="btn btn-sm {{ $status === 'all' ? 'btn-dark' : 'btn-outline-dark' }}" href="{{ route('user.properties.index') }}">All</a>
        <a class="btn btn-sm {{ $status === 'active' ? 'btn-dark' : 'btn-outline-dark' }}" href="{{ route('user.properties.active') }}">Active</a>
        <a class="btn btn-sm {{ $status === 'pending' ? 'btn-dark' : 'btn-outline-dark' }}" href="{{ route('user.properties.pending') }}">Pending</a>
        <a class="btn btn-sm {{ $status === 'rejected' ? 'btn-dark' : 'btn-outline-dark' }}" href="{{ route('user.properties.rejected') }}">Rejected</a>
        <a class="btn btn-sm {{ $status === 'expired' ? 'btn-dark' : 'btn-outline-dark' }}" href="{{ route('user.properties.expired') }}">Expired</a>
      </div>

      <section class="dashboard-card">
        <div class="dashboard-card-head">
          <div>
            <h2>{{ $statusTitle }}</h2>
            <p>Manage properties submitted from your account.</p>
          </div>
        </div>

        <div class="saved-property-list">
          @forelse($properties as $property)
            <article class="saved-property">
              <img src="{{ $imageFor($property) }}" alt="{{ $property->title }}">
              <div>
                <h3>{{ $property->title }}</h3>
                <p>
                  {{ optional($property->type)->name ?: 'Property' }}
                  @if($property->area || $property->city || $property->district)
                    - {{ collect([optional($property->area)->name, optional($property->city)->name, optional($property->district)->name])->filter()->join(', ') }}
                  @endif
                </p>
                <span>{{ $priceFor($property) }}</span>
              </div>
              <div class="text-end">
                <span class="badge text-bg-{{ $property->verification_status === 'approved' ? 'success' : ($property->verification_status === 'rejected' ? 'danger' : 'warning') }}">
                  {{ ucfirst($property->verification_status) }}
                </span>
                <div class="small text-secondary mt-1">{{ $property->is_published ? 'Published' : 'Unpublished' }}</div>
              </div>
            </article>
          @empty
            <article class="saved-property">
              <img src="{{ asset('frontend/assets/images/property_img_1.jpg') }}" alt="No property">
              <div>
                <h3>No properties found</h3>
                <p>Add a property or choose another status filter.</p>
                <span>{{ $statusTitle }}</span>
              </div>
              <a href="{{ route('user.properties.create') }}" aria-label="Add property"><i class="bi bi-plus"></i></a>
            </article>
          @endforelse
        </div>

        @if($properties->hasPages())
          <div class="mt-4">{{ $properties->links() }}</div>
        @endif
      </section>
    </section>
  </section>
</main>
@endsection
