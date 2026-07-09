@csrf

<div class="card">
  <div class="card-header">
    <h3 class="card-title">{{ $title }}</h3>
  </div>
  <div class="card-body">
    <div class="row g-3">
      <div class="col-md-6">
        <label for="name" class="form-label">Name</label>
        <input id="name" type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $admin->name) }}" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-6">
        <label for="email" class="form-label">Email</label>
        <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $admin->email) }}" required>
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-6">
        <label for="password" class="form-label">Password</label>
        <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" @required(! $admin->exists)>
        @if ($admin->exists)
          <div class="form-text">Leave blank to keep current password.</div>
        @endif
        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-6">
        <label for="password_confirmation" class="form-label">Confirm Password</label>
        <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" @required(! $admin->exists)>
      </div>
      <div class="col-12">
        <label class="form-label">Roles</label>
        <div class="row g-2">
          @forelse ($roles as $role)
            <div class="col-md-4">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="role-{{ $role->id }}" name="roles[]" value="{{ $role->name }}" @checked(in_array($role->name, old('roles', $adminRoles), true))>
                <label class="form-check-label" for="role-{{ $role->id }}">{{ $role->name }}</label>
              </div>
            </div>
          @empty
            <div class="col-12 text-secondary">No roles available.</div>
          @endforelse
        </div>
        @error('roles')<div class="text-danger small">{{ $message }}</div>@enderror
        @error('roles.*')<div class="text-danger small">{{ $message }}</div>@enderror
      </div>
    </div>
  </div>
  <div class="card-footer d-flex justify-content-end gap-2">
    <a href="{{ route('admin.admin-roles.index') }}" class="btn btn-secondary">Cancel</a>
    <button type="submit" class="btn btn-primary">Save Admin</button>
  </div>
</div>
