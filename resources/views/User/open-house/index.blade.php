@extends('Frontend.layouts.master')

@section('title', 'Open House Management | Land Site')
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
          <p>Open House</p>
          <h2>Open House Management</h2>
        </div>
      </div>

      <section class="dashboard-card mb-4">
        <h2>Open House Properties</h2>
        <div class="table-responsive mt-3">
          <table class="table align-middle">
            <thead>
              <tr>
                <th>Property</th>
                <th>Location</th>
                <th>Status</th>
                <th>Published</th>
              </tr>
            </thead>
            <tbody>
              @forelse($openHouseProperties as $property)
                <tr>
                  <td>{{ $property->title }}</td>
                  <td>{{ collect([$property->area?->name, $property->city?->name, $property->district?->name])->filter()->join(', ') ?: '-' }}</td>
                  <td><span class="badge text-bg-{{ $property->verification_status === 'approved' ? 'success' : 'warning' }}">{{ ucfirst($property->verification_status) }}</span></td>
                  <td>{{ $property->is_published ? 'Yes' : 'No' }}</td>
                </tr>
              @empty
                <tr><td colspan="4" class="text-center text-secondary">No open house properties found. Admin can enable Open House after approval.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
        @if(method_exists($openHouseProperties, 'hasPages') && $openHouseProperties->hasPages())
          <div class="mt-4">{{ $openHouseProperties->links() }}</div>
        @endif
      </section>

      <section class="dashboard-card">
        <h2>Schedules</h2>
        <div class="table-responsive mt-3">
          <table class="table align-middle">
            <thead>
              <tr>
                <th>Property</th>
                <th>Starts</th>
                <th>Ends</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              @forelse($schedules as $schedule)
                <tr>
                  <td>{{ $schedule->property?->title ?? 'Property not available' }}</td>
                  <td>{{ $schedule->starts_at?->format('d M Y h:i A') }}</td>
                  <td>{{ $schedule->ends_at?->format('d M Y h:i A') ?? '-' }}</td>
                  <td><span class="badge text-bg-{{ $schedule->status === 'scheduled' ? 'success' : 'secondary' }}">{{ ucfirst($schedule->status) }}</span></td>
                </tr>
              @empty
                <tr><td colspan="4" class="text-center text-secondary">No open house schedules found.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </section>
    </section>
  </section>
</main>
@endsection
