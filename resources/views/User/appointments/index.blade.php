@extends('Frontend.layouts.master')

@section('title', 'Appointments | Land Site')
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
      <div class="dashboard-topbar">
        <div>
          <p>Appointments</p>
          <h2>Scheduled Tours</h2>
        </div>
      </div>

      <section class="dashboard-card">
        <div class="table-responsive">
          <table class="table align-middle">
            <thead>
              <tr>
                <th>Property</th>
                <th>Tour Type</th>
                <th>Schedule</th>
                <th>Status</th>
                <th>Note</th>
              </tr>
            </thead>
            <tbody>
              @forelse($appointments as $appointment)
                <tr>
                  <td>{{ $appointment->property?->title ?? 'Property not available' }}</td>
                  <td>{{ ucwords(str_replace('_', ' ', $appointment->tour_type)) }}</td>
                  <td>{{ $appointment->scheduled_at?->format('d M Y h:i A') ?? '-' }}</td>
                  <td><span class="badge text-bg-{{ $appointment->status === 'confirmed' ? 'success' : ($appointment->status === 'cancelled' ? 'danger' : 'warning') }}">{{ ucfirst($appointment->status) }}</span></td>
                  <td>{{ $appointment->note ?: '-' }}</td>
                </tr>
              @empty
                <tr><td colspan="5" class="text-center text-secondary">No appointments found.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
        @if(method_exists($appointments, 'hasPages') && $appointments->hasPages())
          <div class="mt-4">{{ $appointments->links() }}</div>
        @endif
      </section>
    </section>
  </section>
</main>
@endsection
