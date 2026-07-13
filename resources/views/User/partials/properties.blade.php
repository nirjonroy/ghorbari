<div class="row g-4">
  <div class="col-xl-8">
    <section class="dashboard-card" id="my-property">
      <div class="dashboard-card-head">
        <div>
          <h2>My Property</h2>
          <p>Properties you listed from this account.</p>
        </div>
        <a href="#my-property">View All</a>
      </div>
      <div class="saved-property-list">
        @forelse($dashboardData['recent_properties'] as $property)
          @php
              $media = $property->media->firstWhere('is_primary', true) ?: $property->media->first();
              $image = $media ? asset($media->file_path) : asset('frontend/assets/images/property_img_1.jpg');
              $price = 'BDT '.number_format((float) $property->price);
              $price .= $property->rent_period ? ' / '.$property->rent_period : '';
          @endphp
          <article class="saved-property">
            <img src="{{ $image }}" alt="{{ $property->title }}">
            <div>
              <h3>{{ $property->title }}</h3>
              <p>{{ optional($property->type)->name ?? ucwords($property->listing_type) }}</p>
              <span>{{ $price }}</span>
            </div>
            <a href="#" aria-label="View property"><i class="bi bi-arrow-right"></i></a>
          </article>
        @empty
          <article class="saved-property">
            <img src="{{ asset('frontend/assets/images/property_img_1.jpg') }}" alt="No property listed">
            <div>
              <h3>No property listed yet</h3>
              <p>Add your first property to start managing it here.</p>
              <span>Ready to publish</span>
            </div>
            <a href="#add-property" aria-label="Add property"><i class="bi bi-plus"></i></a>
          </article>
        @endforelse
      </div>
    </section>
  </div>

  <div class="col-xl-4">
    <section class="dashboard-card" id="settings">
      <div class="profile-completion">
        <div class="completion-ring">{{ $profileCompletion }}%</div>
        <h2>Profile Completion</h2>
        <p>Add phone, NID, address, and profile photo to improve account verification.</p>
        <a class="btn btn-dark w-100" href="{{ route('profile.edit') }}">Update Profile</a>
      </div>
    </section>
  </div>
</div>
