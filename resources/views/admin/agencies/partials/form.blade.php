@csrf
@include('Admin.partials.rich-text-editor')

<div class="card">
  <div class="card-header">
    <h3 class="card-title">{{ $title }}</h3>
  </div>
  <div class="card-body">
    <div class="row g-3">
      <div class="col-md-6">
        <label for="name" class="form-label">Name</label>
        <input id="name" type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $agency->name) }}" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-6">
        <label for="slug" class="form-label">Slug</label>
        <input id="slug" type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $agency->slug) }}" placeholder="auto generated if blank">
        @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-6">
        <label for="email" class="form-label">Email</label>
        <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $agency->email) }}">
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-6">
        <label for="phone" class="form-label">Phone</label>
        <input id="phone" type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $agency->phone) }}">
        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-6">
        <label for="website" class="form-label">Website</label>
        <input id="website" type="url" name="website" class="form-control @error('website') is-invalid @enderror" value="{{ old('website', $agency->website) }}">
        @error('website')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-6">
        <label for="status" class="form-label">Status</label>
        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
          @foreach(['active' => 'Active', 'inactive' => 'Inactive'] as $value => $label)
            <option value="{{ $value }}" @selected(old('status', $agency->status ?? 'active') === $value)>{{ $label }}</option>
          @endforeach
        </select>
        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-8">
        <label for="logo" class="form-label">Logo</label>
        <input id="logo" type="file" name="logo" class="form-control @error('logo') is-invalid @enderror" accept="image/*">
        @error('logo')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-4">
        @if($agency->logo)
          <label class="form-label d-block">Current Logo</label>
          <img src="{{ asset($agency->logo) }}" alt="{{ $agency->name }}" style="max-height: 70px; max-width: 160px; object-fit: contain;">
        @endif
      </div>
      <div class="col-12">
        <label for="description" class="form-label">Description</label>
        <textarea id="description" name="description" rows="5" class="form-control rich-text-editor @error('description') is-invalid @enderror">{{ old('description', $agency->description) }}</textarea>
        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
    </div>
  </div>

  @include('Admin.partials.seo-fields', ['model' => $agency])

  <div class="card-footer d-flex justify-content-end gap-2">
    <a href="{{ route('admin.agencies.index') }}" class="btn btn-secondary">Cancel</a>
    <button type="submit" class="btn btn-primary">Save Agency</button>
  </div>
</div>
