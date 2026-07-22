@extends('Frontend.layouts.master')

@section('title', 'Saved Searches | Land Site')
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
          <p>Saved Search</p>
          <h2>Your Search Alerts</h2>
        </div>
      </div>

      <section class="dashboard-card">
        <div class="table-responsive">
          <table class="table align-middle">
            <thead>
              <tr>
                <th>Name</th>
                <th>Filters</th>
                <th>Notify</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @forelse($savedSearches as $savedSearch)
                <tr>
                  <td>{{ $savedSearch->name }}</td>
                  <td>{{ collect($savedSearch->filters ?? [])->filter()->map(fn ($value, $key) => ucwords(str_replace('_', ' ', $key)).': '.$value)->join(', ') ?: '-' }}</td>
                  <td>{{ $savedSearch->notify ? 'Enabled' : 'Disabled' }}</td>
                  <td><span class="badge text-bg-{{ $savedSearch->status === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($savedSearch->status) }}</span></td>
                  <td>
                    @if($savedSearch->url)
                      <a class="btn btn-sm btn-outline-dark" href="{{ $savedSearch->url }}">Open</a>
                    @else
                      -
                    @endif
                  </td>
                </tr>
              @empty
                <tr><td colspan="5" class="text-center text-secondary">No saved searches found.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
        @if(method_exists($savedSearches, 'hasPages') && $savedSearches->hasPages())
          <div class="mt-4">{{ $savedSearches->links() }}</div>
        @endif
      </section>
    </section>
  </section>
</main>
@endsection
