@extends('Frontend.layouts.master')

@section('title', 'Edit User Profile | Land Site')
@section('body_class', 'frontend-page user-dashboard-page')

@section('content')
@php
    $user = $dashboardData['user'];
    $stats = $dashboardData['stats'];
    $profileCompletion = $dashboardData['profile_completion'];
    $avatar = $user->profile_photo_path ? asset($user->profile_photo_path) : asset('frontend/assets/images/avatar_1.jpg');
    $input = fn ($name, $default = null) => old($name, $default ?? $user->{$name});
@endphp

<main class="dashboard-page">
  <section class="dashboard-shell">
    @include('User.partials.sidebar')

    <section class="dashboard-main">
      <div class="dashboard-topbar">
        <div>
          <p>User Profile - Edit</p>
          <h2>Update Account Details</h2>
        </div>
        <div class="dashboard-actions">
          <a class="btn btn-outline-dark" href="{{ route('dashboard') }}"><i class="bi bi-arrow-left"></i> Back To Profile</a>
        </div>
      </div>

      @if($errors->any())
        <div class="alert alert-danger">
          <strong>Please fix the highlighted fields.</strong>
        </div>
      @endif

      <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <section class="dashboard-card mb-4">
          <h2>Basic Information</h2>
          <div class="row g-3 mt-1">
            <div class="col-md-6">
              <label class="form-label">Name</label>
              <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ $input('name') }}" required>
              @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ $input('email') }}" required>
              @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
              <label class="form-label">Account Type</label>
              <select name="account_type" class="form-select">
                @foreach(['buyer' => 'Buyer', 'owner' => 'Owner', 'agent' => 'Agent', 'tenant' => 'Tenant'] as $value => $label)
                  <option value="{{ $value }}" @selected($input('account_type') === $value)>{{ $label }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Phone</label>
              <input type="text" name="phone" class="form-control" value="{{ $input('phone') }}">
            </div>
            <div class="col-md-4">
              <label class="form-label">Alternative Phone</label>
              <input type="text" name="alternative_phone" class="form-control" value="{{ $input('alternative_phone') }}">
            </div>
            <div class="col-md-4">
              <label class="form-label">Date of Birth</label>
              <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', optional($user->date_of_birth)->format('Y-m-d')) }}">
            </div>
            <div class="col-md-4">
              <label class="form-label">Gender</label>
              <select name="gender" class="form-select">
                <option value="">Select</option>
                @foreach(['male' => 'Male', 'female' => 'Female', 'other' => 'Other'] as $value => $label)
                  <option value="{{ $value }}" @selected($input('gender') === $value)>{{ $label }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Profession</label>
              <input type="text" name="profession" class="form-control" value="{{ $input('profession') }}">
            </div>
            <div class="col-md-12">
              <label class="form-label">Bio</label>
              <textarea name="bio" class="form-control" rows="4">{{ $input('bio') }}</textarea>
            </div>
          </div>
        </section>

        <section class="dashboard-card mb-4">
          <h2>Home and Address</h2>
          <div class="row g-3 mt-1">
            @foreach([
                'home_name' => 'Home Name',
                'home_type' => 'Home Type',
                'area_name' => 'Area Name',
                'post_office' => 'Post Office',
                'postal_code' => 'Postal Code',
                'upazila' => 'Upazila',
                'district' => 'District',
                'division' => 'Division',
                'country' => 'Country',
            ] as $name => $label)
              <div class="col-md-4">
                <label class="form-label">{{ $label }}</label>
                <input type="text" name="{{ $name }}" class="form-control" value="{{ $input($name) }}">
              </div>
            @endforeach
            <div class="col-md-6">
              <label class="form-label">Present Address</label>
              <textarea name="present_address" class="form-control" rows="3">{{ $input('present_address') }}</textarea>
            </div>
            <div class="col-md-6">
              <label class="form-label">Permanent Address</label>
              <textarea name="permanent_address" class="form-control" rows="3">{{ $input('permanent_address') }}</textarea>
            </div>
          </div>
        </section>

        <section class="dashboard-card mb-4">
          <h2>Verification Documents</h2>
          <div class="row g-3 mt-1">
            <div class="col-md-4">
              <label class="form-label">NID Number</label>
              <input type="text" name="nid_number" class="form-control" value="{{ $input('nid_number') }}">
            </div>
            <div class="col-md-4">
              <label class="form-label">Passport Number</label>
              <input type="text" name="passport_number" class="form-control" value="{{ $input('passport_number') }}">
            </div>
            <div class="col-md-4">
              <label class="form-label">Ownership Document Type</label>
              <input type="text" name="ownership_document_type" class="form-control" value="{{ $input('ownership_document_type') }}">
            </div>
            <div class="col-md-6">
              <label class="form-label">Emergency Contact Name</label>
              <input type="text" name="emergency_contact_name" class="form-control" value="{{ $input('emergency_contact_name') }}">
            </div>
            <div class="col-md-6">
              <label class="form-label">Emergency Contact Phone</label>
              <input type="text" name="emergency_contact_phone" class="form-control" value="{{ $input('emergency_contact_phone') }}">
            </div>
          </div>
        </section>

        <section class="dashboard-card mb-4">
          <h2>Uploads</h2>
          <div class="row g-3 mt-1">
            <div class="col-md-6">
              <label class="form-label">Profile Photo</label>
              <input type="file" name="profile_photo" class="form-control @error('profile_photo') is-invalid @enderror" accept="image/*">
              @error('profile_photo') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
              <label class="form-label">NID Front Image</label>
              <input type="file" name="nid_front_image" class="form-control @error('nid_front_image') is-invalid @enderror" accept="image/*">
              @error('nid_front_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
              <label class="form-label">NID Back Image</label>
              <input type="file" name="nid_back_image" class="form-control @error('nid_back_image') is-invalid @enderror" accept="image/*">
              @error('nid_back_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
              <label class="form-label">Passport Image</label>
              <input type="file" name="passport_image" class="form-control @error('passport_image') is-invalid @enderror" accept="image/*">
              @error('passport_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
              <label class="form-label">Ownership Proof</label>
              <input type="file" name="ownership_proof" class="form-control @error('ownership_proof') is-invalid @enderror" accept="image/*,.pdf">
              @error('ownership_proof') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
              <label class="form-label">Home Elevation Images</label>
              <input type="file" name="home_elevation_images[]" class="form-control @error('home_elevation_images.*') is-invalid @enderror" accept="image/*" multiple>
              @error('home_elevation_images.*') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>
        </section>

        <div class="d-flex justify-content-end gap-2">
          <a href="{{ route('dashboard') }}" class="btn btn-outline-dark">Cancel</a>
          <button type="submit" class="btn btn-danger px-4">Save Profile</button>
        </div>
      </form>
    </section>
  </section>
</main>
@endsection
