@csrf
@include('Admin.partials.rich-text-editor')

<div class="card">
  <div class="card-header">
    <h3 class="card-title">{{ $title }}</h3>
  </div>
  <div class="card-body">
    <div class="row g-3">
      <div class="col-md-6">
        <label for="user_id" class="form-label">User</label>
        <select id="user_id" name="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
          <option value="">Select user</option>
          @foreach($users as $user)
            <option value="{{ $user->id }}" @selected((string) old('user_id', $agentProfile->user_id) === (string) $user->id)>
              {{ $user->name }} - {{ $user->email }}
            </option>
          @endforeach
        </select>
        @error('user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-6">
        <label for="agency_id" class="form-label">Agency</label>
        <select id="agency_id" name="agency_id" class="form-select @error('agency_id') is-invalid @enderror">
          <option value="">Independent agent</option>
          @foreach($agencies as $agency)
            <option value="{{ $agency->id }}" @selected((string) old('agency_id', $agentProfile->agency_id) === (string) $agency->id)>{{ $agency->name }}</option>
          @endforeach
        </select>
        @error('agency_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-6">
        <label for="designation" class="form-label">Designation</label>
        <input id="designation" type="text" name="designation" class="form-control @error('designation') is-invalid @enderror" value="{{ old('designation', $agentProfile->designation) }}">
        @error('designation')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-6">
        <label for="license_no" class="form-label">License No</label>
        <input id="license_no" type="text" name="license_no" class="form-control @error('license_no') is-invalid @enderror" value="{{ old('license_no', $agentProfile->license_no) }}">
        @error('license_no')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-4">
        <label for="experience_years" class="form-label">Experience Years</label>
        <input id="experience_years" type="number" min="0" max="100" name="experience_years" class="form-control @error('experience_years') is-invalid @enderror" value="{{ old('experience_years', $agentProfile->experience_years) }}">
        @error('experience_years')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-4">
        <label for="rating" class="form-label">Rating</label>
        <input id="rating" type="number" min="0" max="5" step="0.01" name="rating" class="form-control @error('rating') is-invalid @enderror" value="{{ old('rating', $agentProfile->rating) }}">
        @error('rating')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-4">
        <label for="status" class="form-label">Status</label>
        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
          @foreach(['active' => 'Active', 'inactive' => 'Inactive'] as $value => $label)
            <option value="{{ $value }}" @selected(old('status', $agentProfile->status ?? 'active') === $value)>{{ $label }}</option>
          @endforeach
        </select>
        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-12">
        <label for="service_area" class="form-label">Service Area</label>
        <input id="service_area" type="text" name="service_area" class="form-control @error('service_area') is-invalid @enderror" value="{{ old('service_area', $agentProfile->service_area) }}">
        @error('service_area')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-12">
        <label for="bio" class="form-label">Bio</label>
        <textarea id="bio" name="bio" rows="5" class="form-control rich-text-editor @error('bio') is-invalid @enderror">{{ old('bio', $agentProfile->bio) }}</textarea>
        @error('bio')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
    </div>
  </div>
  <div class="card-footer d-flex justify-content-end gap-2">
    <a href="{{ route('admin.agent-profiles.index') }}" class="btn btn-secondary">Cancel</a>
    <button type="submit" class="btn btn-primary">Save Agent</button>
  </div>
</div>
