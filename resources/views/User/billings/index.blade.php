@extends('Frontend.layouts.master')

@section('title', 'Billings | Land Site')
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
      @if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
      @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

      <div class="dashboard-topbar">
        <div>
          <p>Billings</p>
          <h2>Subscription Billing</h2>
        </div>
        <div class="dashboard-actions">
          <a class="btn btn-danger" href="{{ route('user.billings.add-payment') }}"><i class="bi bi-wallet2"></i> Add Payment</a>
        </div>
      </div>

      <section class="dashboard-card mb-4">
        <h2>Current Plan</h2>
        @if($activeSubscription)
          <div class="row g-3 mt-1">
            <div class="col-md-3"><strong>Package</strong><br>{{ $activeSubscription->package_name }}</div>
            <div class="col-md-3"><strong>Status</strong><br><span class="badge text-bg-success">{{ ucfirst($activeSubscription->status) }}</span></div>
            <div class="col-md-3"><strong>Price</strong><br>{{ $activeSubscription->currency }} {{ number_format((float) $activeSubscription->price, 2) }}</div>
            <div class="col-md-3"><strong>Ends At</strong><br>{{ $activeSubscription->ends_at?->format('d M Y') ?? 'No expiry' }}</div>
          </div>
        @else
          <p class="text-secondary mb-0">No active subscription found.</p>
        @endif
      </section>

      <section class="dashboard-card mb-4">
        <div class="dashboard-card-head">
          <div>
            <h2>Payment History</h2>
            <p>All subscription payment attempts and completed transactions.</p>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table align-middle">
            <thead>
              <tr>
                <th>Transaction</th>
                <th>Package</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Paid At</th>
              </tr>
            </thead>
            <tbody>
              @forelse($payments as $payment)
                <tr>
                  <td>{{ $payment->transaction_id }}</td>
                  <td>{{ $payment->package?->name ?? $payment->subscription?->package_name ?? 'Subscription' }}</td>
                  <td>{{ $payment->currency }} {{ number_format((float) $payment->amount, 2) }}</td>
                  <td><span class="badge text-bg-{{ $payment->status === 'paid' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'secondary') }}">{{ ucfirst($payment->status) }}</span></td>
                  <td>{{ $payment->paid_at?->format('d M Y h:i A') ?? '-' }}</td>
                </tr>
              @empty
                <tr><td colspan="5" class="text-center text-secondary">No payments found.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
        @if(method_exists($payments, 'links'))
          {{ $payments->links() }}
        @endif
      </section>

      <section class="dashboard-card">
        <h2>Subscription History</h2>
        <div class="table-responsive mt-3">
          <table class="table align-middle">
            <thead>
              <tr>
                <th>Package</th>
                <th>Limit</th>
                <th>Status</th>
                <th>Start</th>
                <th>End</th>
              </tr>
            </thead>
            <tbody>
              @forelse($subscriptions as $subscription)
                <tr>
                  <td>{{ $subscription->package_name }}</td>
                  <td>{{ $subscription->property_limit ?? 'Unlimited' }} properties</td>
                  <td><span class="badge text-bg-{{ $subscription->status === 'active' ? 'success' : ($subscription->status === 'pending' ? 'warning' : 'secondary') }}">{{ ucfirst($subscription->status) }}</span></td>
                  <td>{{ $subscription->starts_at?->format('d M Y') ?? '-' }}</td>
                  <td>{{ $subscription->ends_at?->format('d M Y') ?? '-' }}</td>
                </tr>
              @empty
                <tr><td colspan="5" class="text-center text-secondary">No subscription history found.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
        @if(method_exists($subscriptions, 'links'))
          {{ $subscriptions->links() }}
        @endif
      </section>
    </section>
  </section>
</main>
@endsection
