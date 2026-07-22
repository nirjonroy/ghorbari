<div class="row g-4 mt-1">
  <div class="col-lg-4">
    <section class="dashboard-card mini-panel" id="documents">
      <i class="bi bi-file-earmark-check"></i>
      <h2>Documents</h2>
      <p>Upload NID, passport, ownership proof, and home elevation documents.</p>
      <a href="{{ route('user.profile.edit') }}">Manage Documents</a>
    </section>
  </div>
  <div class="col-lg-4">
    <section class="dashboard-card mini-panel" id="payments">
      <i class="bi bi-wallet2"></i>
      <h2>Payments</h2>
      <p>Track booking fees, service charges, and payment records.</p>
      <a href="#payments">View Payments</a>
    </section>
  </div>
  <div class="col-lg-4">
    <section class="dashboard-card mini-panel">
      <i class="bi bi-bell"></i>
      <h2>Search Alerts</h2>
      <p>Get updates when new properties match your budget.</p>
      <a href="{{ route('frontend.home') }}">Edit Alerts</a>
    </section>
  </div>
</div>

<div class="row g-4 mt-1">
  <div class="col-lg-6">
    <section class="dashboard-card mini-panel" id="profile-details">
      <i class="bi bi-person-lines-fill"></i>
      <h2>Profile Details</h2>
      <p>Update your name, phone number, email, preferred areas, and owner profile information.</p>
      <a href="{{ route('user.profile.edit') }}">Edit Profile Details</a>
    </section>
  </div>
  <div class="col-lg-6">
    <section class="dashboard-card mini-panel" id="home-info">
      <i class="bi bi-house-gear"></i>
      <h2>Home Info</h2>
      <p>Manage home name, type, area, post office, upazila, district, and division.</p>
      <a href="{{ route('user.profile.edit') }}">Update Home Info</a>
    </section>
  </div>
  <div class="col-lg-6">
    <section class="dashboard-card mini-panel" id="change-password">
      <i class="bi bi-key"></i>
      <h2>Change Password</h2>
      <p>Keep your account secure by updating password and recovery information regularly.</p>
      <a href="{{ route('profile.edit') }}">Change Password</a>
    </section>
  </div>
  <div class="col-lg-6">
    <section class="dashboard-card mini-panel" id="account-security">
      <i class="bi bi-shield-lock"></i>
      <h2>Account Security</h2>
      <p>Review account information, emergency contact, and verification status.</p>
      <a href="{{ route('user.profile.edit') }}">Manage Security</a>
    </section>
  </div>
</div>
