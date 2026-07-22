@extends('Frontend.layouts.master')

@section('title', 'Activity Logs | Land Site')
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
          <p>Activity Logs</p>
          <h2>Recent Account Activity</h2>
        </div>
      </div>

      <section class="dashboard-card">
        <div class="table-responsive">
          <table class="table align-middle">
            <thead>
              <tr>
                <th>Activity</th>
                <th>Details</th>
                <th>Time</th>
              </tr>
            </thead>
            <tbody>
              @forelse($activities as $activity)
                <tr>
                  <td><i class="bi {{ $activity['icon'] }} me-2"></i>{{ $activity['title'] }}</td>
                  <td>{{ $activity['description'] }}</td>
                  <td>{{ $activity['time']->format('d M Y h:i A') }}</td>
                </tr>
              @empty
                <tr><td colspan="3" class="text-center text-secondary">No activity found.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </section>
    </section>
  </section>
</main>
@endsection
