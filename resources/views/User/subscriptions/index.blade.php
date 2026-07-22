@extends('Frontend.layouts.master')

@section('title', 'Subscription | Land Site')
@section('body_class', 'frontend-page user-dashboard-page')

@section('content')
@php
    $user = $dashboardData['user'];
    $stats = $dashboardData['stats'];
    $profileCompletion = $dashboardData['profile_completion'];
    $avatar = $user->profile_photo_path ? asset($user->profile_photo_path) : asset('frontend/assets/images/avatar_1.jpg');
@endphp

<main class="dashboard-page">
  <section class="dashboard-shell">
    @include('User.partials.sidebar')

    <section class="dashboard-main">
      @if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
      @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

      <div class="dashboard-topbar">
        <div>
          <p>Subscription</p>
          <h2>Choose Your Property Plan</h2>
        </div>
        <div class="dashboard-actions">
          <a class="btn btn-outline-dark" href="{{ route('user.dashboard') }}"><i class="bi bi-grid-1x2"></i> Dashboard</a>
        </div>
      </div>

      @if($activeSubscription)
        <section class="dashboard-card mb-4">
          <div class="row g-3 align-items-center">
            <div class="col-lg-8">
              <span class="badge text-bg-success mb-2">Active Plan</span>
              <h2>{{ $activeSubscription->package_name }}</h2>
              <p class="mb-0">
                Valid until {{ optional($activeSubscription->ends_at)->format('d M Y') ?: 'no expiry' }}.
                Property limit: {{ $activeSubscription->property_limit ?? 'Unlimited' }},
                Featured limit: {{ $activeSubscription->featured_property_limit ?? 'Unlimited' }}.
              </p>
            </div>
            <div class="col-lg-4 text-lg-end">
              <strong class="fs-4">{{ $activeSubscription->currency }} {{ number_format((float) $activeSubscription->price, 2) }}</strong>
            </div>
          </div>
        </section>
      @endif

      <div class="row g-4">
        @forelse($packages as $package)
          <div class="col-md-6 col-xl-4">
            <section class="dashboard-card action-card h-100">
              <i class="bi {{ $package->is_featured ? 'bi-gem' : 'bi-credit-card-2-front' }}"></i>
              @if($package->is_featured)<span class="badge text-bg-danger mb-2">Popular</span>@endif
              <h2>{{ $package->name }}</h2>
              <p>{{ $package->description ?: 'Subscription package for your property workspace.' }}</p>
              <div class="mb-3">
                <strong class="fs-3">{{ $package->currency }} {{ number_format((float) $package->price, 2) }}</strong>
                <span class="text-secondary">/ {{ $package->duration_days }} days</span>
              </div>
              <ul class="list-unstyled text-secondary">
                <li><i class="bi bi-check-circle text-success me-1"></i> {{ $package->property_limit ?? 'Unlimited' }} properties</li>
                <li><i class="bi bi-check-circle text-success me-1"></i> {{ $package->featured_property_limit ?? 'Unlimited' }} featured properties</li>
                <li><i class="bi bi-check-circle text-success me-1"></i> SSLCommerz secure payment</li>
              </ul>
              <form method="POST" action="{{ route('user.subscriptions.checkout', ['package' => $package]) }}">
                @csrf
                <button type="submit" class="btn btn-danger w-100">Pay With SSLCommerz</button>
              </form>
            </section>
          </div>
        @empty
          <div class="col-12">
            <section class="dashboard-card">
              <h2>No subscription packages found</h2>
              <p class="mb-0">Packages will appear here after an admin creates active subscription packages.</p>
            </section>
          </div>
        @endforelse
      </div>

      <section class="dashboard-card mt-4">
        <h2>Payment History</h2>
        <div class="table-responsive mt-3">
          <table class="table align-middle">
            <thead>
              <tr>
                <th>Transaction</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
              @forelse($payments as $payment)
                <tr>
                  <td>{{ $payment->transaction_id }}</td>
                  <td>{{ $payment->currency }} {{ number_format((float) $payment->amount, 2) }}</td>
                  <td><span class="badge text-bg-{{ $payment->status === 'paid' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'secondary') }}">{{ ucfirst($payment->status) }}</span></td>
                  <td>{{ $payment->created_at->format('d M Y h:i A') }}</td>
                </tr>
              @empty
                <tr><td colspan="4" class="text-secondary">No payments found.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </section>
    </section>
  </section>
</main>
@endsection
