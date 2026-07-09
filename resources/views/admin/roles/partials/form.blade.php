@csrf

<div class="card">
  <div class="card-header">
    <h3 class="card-title">{{ $title }}</h3>
  </div>
  <div class="card-body">
    <div class="row g-3">
      <div class="col-md-6">
        <label for="name" class="form-label">Role Name</label>
        <input id="name" type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $role->name) }}" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-12">
        <label class="form-label">Permissions</label>
        <div class="row g-2">
          @foreach ($permissions as $permission)
            <div class="col-md-4">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="permission-{{ $permission->id }}" name="permissions[]" value="{{ $permission->name }}" @checked(in_array($permission->name, old('permissions', $rolePermissions), true))>
                <label class="form-check-label" for="permission-{{ $permission->id }}">{{ $permission->name }}</label>
              </div>
            </div>
          @endforeach
        </div>
        @error('permissions')<div class="text-danger small">{{ $message }}</div>@enderror
      </div>
    </div>
  </div>
  <div class="card-footer d-flex justify-content-end gap-2">
    <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Cancel</a>
    <button type="submit" class="btn btn-primary">Save Role</button>
  </div>
</div>
