@extends('Frontend.layouts.master')

@section('title', 'User Dashboard | Land Site')
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
      @include('User.partials.topbar')
      @include('User.partials.stats')
      @include('User.partials.properties')
      @include('User.partials.quick-actions')
      @include('User.partials.activity')
      @include('User.partials.account-panels')
    </section>
  </section>
</main>
@endsection
