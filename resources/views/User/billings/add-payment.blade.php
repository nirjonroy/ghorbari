@extends('Frontend.layouts.master')

@section('title', 'Add Payment | Land Site')
@section('body_class', 'frontend-page user-dashboard-page')

@section('content')
@php
    $user = $dashboardData['user'];
    $profileCompletion = $dashboardData['profile_completion'];
    $avatar = $user->profile_photo_path ? asset($user->profile_photo_path) : asset('frontend/assets/images/avatar_1.jpg');
@endphp

<main class="dashboard-page">
  <section class="dashboard-shell">
    @include('User.partials.sidebar')

    <section class="dashboard-main">
      @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

      <div class="dashboard-topbar">
        <div>
          <p>Add Payment</p>
          <h2>Choose A Subscription Package</h2>
        </div>
        <div class="dashboard-actions">
          <a class="btn btn-outline-dark" href="{{ route('user.billings.index') }}"><i class="bi bi-arrow-left"></i> Back To Billings</a>
        </div>
      </div>

      @if($activeSubscription)
        <div class="alert alert-info">
          Your active plan is {{ $activeSubscription->package_name }} until {{ $activeSubscription->ends_at?->format('d M Y') ?? 'no expiry' }}.
        </div>
      @endif

      <div class="row g-4">
        @forelse($packages as $package)
          <div class="col-md-4">
            <section class="dashboard-card action-card h-100">
              <span><i class="bi bi-gem"></i></span>
              <h3>{{ $package->name }}</h3>
              <p>{{ $package->description ?: 'Subscription package for property listing access.' }}</p>
              <strong class="d-block mb-2">{{ $package->currency }} {{ number_format((float) $package->price, 2) }}</strong>
              <p class="small text-secondary mb-3">
                {{ $package->duration_days }} days,
                {{ $package->property_limit ?? 'unlimited' }} properties,
                {{ $package->featured_property_limit ?? 0 }} featured
              </p>
              <form method="POST" action="{{ route('user.subscriptions.checkout', ['package' => $package]) }}">
                @csrf
                <button type="submit" class="btn btn-danger w-100">Pay Now</button>
              </form>
            </section>
          </div>
        @empty
          <div class="col-12">
            <section class="dashboard-card">
              <p class="text-secondary mb-0">No active subscription packages are available.</p>
            </section>
          </div>
        @endforelse
      </div>
    </section>
  </section>
</main>
@endsection
