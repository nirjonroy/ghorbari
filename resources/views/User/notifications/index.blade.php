@extends('Frontend.layouts.master')

@section('title', 'Notifications | Land Site')
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
          <p>Notifications</p>
          <h2>Account Updates</h2>
        </div>
      </div>

      <section class="dashboard-card">
        <div class="table-responsive">
          <table class="table align-middle">
            <thead>
              <tr>
                <th>Title</th>
                <th>Message</th>
                <th>Type</th>
                <th>Status</th>
                <th>Created</th>
              </tr>
            </thead>
            <tbody>
              @forelse($notifications as $notification)
                <tr>
                  <td>{{ $notification->title }}</td>
                  <td>{{ $notification->message ?: '-' }}</td>
                  <td>{{ $notification->type ?: '-' }}</td>
                  <td><span class="badge text-bg-{{ $notification->read_at ? 'secondary' : 'danger' }}">{{ $notification->read_at ? 'Read' : 'Unread' }}</span></td>
                  <td>{{ $notification->created_at?->format('d M Y h:i A') }}</td>
                </tr>
              @empty
                <tr><td colspan="5" class="text-center text-secondary">No notifications found.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
        @if(method_exists($notifications, 'hasPages') && $notifications->hasPages())
          <div class="mt-4">{{ $notifications->links() }}</div>
        @endif
      </section>
    </section>
  </section>
</main>
@endsection
