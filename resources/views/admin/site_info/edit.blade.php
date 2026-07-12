@extends('Admin.layouts.master')

@section('title', 'Edit Site Info')

@section('content')
      <!--begin::App Main-->
      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Edit Site Info</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('admin.site-info.index') }}">Site Info</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
              </div>
            </div>
          </div>
        </div>

        <div class="app-content">
          <div class="container-fluid">
            <form method="POST" action="{{ route('admin.site-info.update') }}" enctype="multipart/form-data">
              @csrf
              @method('PUT')

              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">General Settings</h3>
                </div>
                <div class="card-body">
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label for="contact_email" class="form-label">Contact Email</label>
                      <input id="contact_email" type="email" name="contact_email" class="form-control @error('contact_email') is-invalid @enderror" value="{{ old('contact_email', $siteInfo->contact_email ?? '') }}">
                      @error('contact_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                      <label for="timezone" class="form-label">Timezone</label>
                      <input id="timezone" type="text" name="timezone" class="form-control @error('timezone') is-invalid @enderror" value="{{ old('timezone', $siteInfo->timezone ?? config('app.timezone', 'UTC')) }}" required>
                      @error('timezone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                      <label for="text_direction" class="form-label">Text Direction</label>
                      <select id="text_direction" name="text_direction" class="form-select @error('text_direction') is-invalid @enderror" required>
                        <option value="ltr" @selected(old('text_direction', $siteInfo->text_direction ?? 'ltr') === 'ltr')>LTR</option>
                        <option value="rtl" @selected(old('text_direction', $siteInfo->text_direction ?? 'ltr') === 'rtl')>RTL</option>
                      </select>
                      @error('text_direction')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                      <label for="frontend_url" class="form-label">Frontend URL</label>
                      <input id="frontend_url" type="url" name="frontend_url" class="form-control @error('frontend_url') is-invalid @enderror" value="{{ old('frontend_url', $siteInfo->frontend_url ?? '') }}">
                      @error('frontend_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                      <label for="logo" class="form-label">Logo</label>
                      <input id="logo" type="file" name="logo" class="form-control @error('logo') is-invalid @enderror" accept="image/*" data-preview="logo-preview">
                      @error('logo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                      <div class="form-text">Accepted: JPG, PNG, WEBP. Saved using the image settings at the bottom of this page.</div>
                      <div class="mt-2">
                        <img
                          id="logo-preview"
                          src="{{ ! empty($siteInfo?->logo) ? asset($siteInfo->logo) : '' }}"
                          alt="Logo preview"
                          class="img-thumbnail {{ empty($siteInfo?->logo) ? 'd-none' : '' }}"
                          style="max-height: 90px"
                        >
                      </div>
                    </div>

                    <div class="col-md-6">
                      <label for="favicon" class="form-label">Favicon</label>
                      <input id="favicon" type="file" name="favicon" class="form-control @error('favicon') is-invalid @enderror" accept="image/*" data-preview="favicon-preview">
                      @error('favicon')<div class="invalid-feedback">{{ $message }}</div>@enderror
                      <div class="form-text">Accepted: JPG, PNG, WEBP. Saved using the image settings at the bottom of this page.</div>
                      <div class="mt-2">
                        <img
                          id="favicon-preview"
                          src="{{ ! empty($siteInfo?->favicon) ? asset($siteInfo->favicon) : '' }}"
                          alt="Favicon preview"
                          class="img-thumbnail {{ empty($siteInfo?->favicon) ? 'd-none' : '' }}"
                          style="max-height: 64px"
                        >
                      </div>
                    </div>

                  </div>
                </div>
              </div>

              <div class="card mt-3">
                <div class="card-header">
                  <h3 class="card-title">Header, Footer, and Currency</h3>
                </div>
                <div class="card-body">
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label for="sidebar_lg_header" class="form-label">Sidebar Large Header</label>
                      <input id="sidebar_lg_header" type="text" name="sidebar_lg_header" class="form-control" value="{{ old('sidebar_lg_header', $siteInfo->sidebar_lg_header ?? '') }}">
                    </div>

                    <div class="col-md-6">
                      <label for="sidebar_sm_header" class="form-label">Sidebar Small Header</label>
                      <input id="sidebar_sm_header" type="text" name="sidebar_sm_header" class="form-control" value="{{ old('sidebar_sm_header', $siteInfo->sidebar_sm_header ?? '') }}">
                    </div>

                    <div class="col-md-6">
                      <label for="topbar_phone" class="form-label">Topbar Phone</label>
                      <input id="topbar_phone" type="text" name="topbar_phone" class="form-control" value="{{ old('topbar_phone', $siteInfo->topbar_phone ?? '') }}">
                    </div>

                    <div class="col-md-6">
                      <label for="topbar_email" class="form-label">Topbar Email</label>
                      <input id="topbar_email" type="email" name="topbar_email" class="form-control @error('topbar_email') is-invalid @enderror" value="{{ old('topbar_email', $siteInfo->topbar_email ?? '') }}">
                      @error('topbar_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                      <label for="currency_name" class="form-label">Currency Name</label>
                      <input id="currency_name" type="text" name="currency_name" class="form-control" value="{{ old('currency_name', $siteInfo->currency_name ?? '') }}">
                    </div>

                    <div class="col-md-4">
                      <label for="currency_icon" class="form-label">Currency Icon</label>
                      <input id="currency_icon" type="text" name="currency_icon" class="form-control" value="{{ old('currency_icon', $siteInfo->currency_icon ?? '') }}">
                    </div>

                    <div class="col-md-4">
                      <label for="currency_rate" class="form-label">Currency Rate</label>
                      <input id="currency_rate" type="number" step="0.0001" min="0" name="currency_rate" class="form-control @error('currency_rate') is-invalid @enderror" value="{{ old('currency_rate', $siteInfo->currency_rate ?? 1) }}" required>
                      @error('currency_rate')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                      <label for="default_phone_code" class="form-label">Default Phone Code</label>
                      <input id="default_phone_code" type="text" name="default_phone_code" class="form-control" value="{{ old('default_phone_code', $siteInfo->default_phone_code ?? '') }}">
                    </div>

                    <div class="col-md-6">
                      <label for="homepage_section_title" class="form-label">Homepage Section Title</label>
                      <input id="homepage_section_title" type="text" name="homepage_section_title" class="form-control" value="{{ old('homepage_section_title', $siteInfo->homepage_section_title ?? '') }}">
                    </div>

                  </div>
                </div>
              </div>

              <div class="card mt-3">
                <div class="card-header">
                  <h3 class="card-title">Location and Notes</h3>
                </div>
                <div class="card-body">
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label for="google_location" class="form-label">Google Location</label>
                      <textarea id="google_location" name="google_location" class="form-control" rows="4">{{ old('google_location', $siteInfo->google_location ?? '') }}</textarea>
                    </div>

                    <div class="col-md-6">
                      <label for="footer_google_location" class="form-label">Footer Google Location</label>
                      <textarea id="footer_google_location" name="footer_google_location" class="form-control" rows="4">{{ old('footer_google_location', $siteInfo->footer_google_location ?? '') }}</textarea>
                    </div>

                    <div class="col-12">
                      <label for="footer_contact_note" class="form-label">Footer Contact Note</label>
                      <textarea id="footer_contact_note" name="footer_contact_note" class="form-control" rows="4">{{ old('footer_contact_note', $siteInfo->footer_contact_note ?? '') }}</textarea>
                    </div>
                  </div>
                </div>
              </div>

              <div class="card mt-3">
                <div class="card-header">
                  <h3 class="card-title">Feature Flags</h3>
                </div>
                <div class="card-body">
                  @foreach ([
                    'maintenance_mode' => 'Maintenance Mode',
                    'enable_user_register' => 'Enable User Register',
                    'phone_number_required' => 'Phone Number Required',
                    'enable_subscription_notify' => 'Enable Subscription Notify',
                    'enable_save_contact_message' => 'Enable Save Contact Message',
                  ] as $field => $label)
                    <div class="form-check form-switch mb-2">
                      <input type="checkbox" class="form-check-input" id="{{ $field }}" name="{{ $field }}" value="1" @checked(old($field, $siteInfo->{$field} ?? in_array($field, ['enable_user_register', 'enable_save_contact_message'], true)))>
                      <label class="form-check-label" for="{{ $field }}">{{ $label }}</label>
                    </div>
                  @endforeach
                </div>
              </div>

              <div class="card mt-3">
                <div class="card-header">
                  <h3 class="card-title">Image Settings</h3>
                </div>
                <div class="card-body">
                  <div class="row g-3">
                    <div class="col-md-4">
                      <label for="image_output_format" class="form-label">Converted Image Format</label>
                      <select id="image_output_format" name="image_output_format" class="form-select @error('image_output_format') is-invalid @enderror" required>
                        @foreach (['webp' => 'WebP', 'jpg' => 'JPG', 'png' => 'PNG'] as $format => $label)
                          <option value="{{ $format }}" @selected(old('image_output_format', $siteInfo->image_output_format ?? 'webp') === $format)>{{ $label }}</option>
                        @endforeach
                      </select>
                      @error('image_output_format')<div class="invalid-feedback">{{ $message }}</div>@enderror
                      <div class="form-text">This format will be used when new logo, favicon, slider, or other feature images are uploaded.</div>
                    </div>

                    <div class="col-12">
                      <hr class="my-2">
                    </div>

                    @foreach ([
                      ['title' => 'Logo Image', 'width' => 'logo_width', 'height' => 'logo_height', 'max' => 5000],
                      ['title' => 'Favicon Image', 'width' => 'favicon_width', 'height' => 'favicon_height', 'max' => 512],
                      ['title' => 'Slider Image', 'width' => 'slider_width', 'height' => 'slider_height', 'max' => 5000],
                      ['title' => 'About Image', 'width' => 'about_image_width', 'height' => 'about_image_height', 'max' => 5000],
                      ['title' => 'Property Image', 'width' => 'property_image_width', 'height' => 'property_image_height', 'max' => 5000],
                      ['title' => 'Blog Post Image', 'width' => 'blog_post_image_width', 'height' => 'blog_post_image_height', 'max' => 5000],
                      ['title' => 'Blog Page Hero Image', 'width' => 'blog_page_image_width', 'height' => 'blog_page_image_height', 'max' => 5000],
                    ] as $imageSetting)
                      <div class="col-md-4">
                        <h6 class="mb-2">{{ $imageSetting['title'] }}</h6>
                        <div class="row g-2">
                          <div class="col-md-6">
                            <label for="{{ $imageSetting['width'] }}" class="form-label">Width</label>
                            <input id="{{ $imageSetting['width'] }}" type="number" min="1" max="{{ $imageSetting['max'] }}" name="{{ $imageSetting['width'] }}" class="form-control @error($imageSetting['width']) is-invalid @enderror" value="{{ old($imageSetting['width'], $siteInfo->{$imageSetting['width']} ?? '') }}" placeholder="Auto">
                            @error($imageSetting['width'])<div class="invalid-feedback">{{ $message }}</div>@enderror
                          </div>
                          <div class="col-md-6">
                            <label for="{{ $imageSetting['height'] }}" class="form-label">Height</label>
                            <input id="{{ $imageSetting['height'] }}" type="number" min="1" max="{{ $imageSetting['max'] }}" name="{{ $imageSetting['height'] }}" class="form-control @error($imageSetting['height']) is-invalid @enderror" value="{{ old($imageSetting['height'], $siteInfo->{$imageSetting['height']} ?? '') }}" placeholder="Auto">
                            @error($imageSetting['height'])<div class="invalid-feedback">{{ $message }}</div>@enderror
                          </div>
                        </div>
                      </div>
                    @endforeach
                  </div>
                </div>
              </div>

              <div class="d-flex justify-content-end gap-2 my-3">
                <a href="{{ route('admin.site-info.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Site Info</button>
              </div>
            </form>
          </div>
        </div>
      </main>
      <!--end::App Main-->
@endsection

@push('scripts')
<script>
  document.querySelectorAll('input[type="file"][data-preview]').forEach((input) => {
    input.addEventListener('change', () => {
      const file = input.files && input.files[0];
      const preview = document.getElementById(input.dataset.preview);

      if (!file || !preview) {
        return;
      }

      preview.src = URL.createObjectURL(file);
      preview.classList.remove('d-none');
      preview.onload = () => URL.revokeObjectURL(preview.src);
    });
  });
</script>
@endpush
