<div class="row g-4 mb-4 dashboard-action-grid">
  <div class="col-lg-4">
    <section class="dashboard-card action-card" id="subscription">
      <i class="bi bi-gem"></i>
      <span class="action-kicker">Plan</span>
      <h2>Subscription</h2>
      <p>{{ $dashboardData['active_subscription'] ? 'Your '.$dashboardData['active_subscription']->package_name.' plan is active.' : 'Choose a plan to unlock property workspace features.' }}</p>
      <a class="btn btn-outline-dark" href="{{ route('user.subscriptions.index') }}">Manage Plan</a>
    </section>
  </div>
  <div class="col-lg-4">
    <section class="dashboard-card action-card" id="add-property">
      <i class="bi bi-plus-circle"></i>
      <span class="action-kicker">Listing</span>
      <h2>Add New Property</h2>
      <p>Create a new listing with price, images, location, and availability.</p>
      <a class="btn btn-success" href="{{ route('user.properties.create') }}">Add Property</a>
    </section>
  </div>
  <div class="col-lg-4">
    <section class="dashboard-card action-card" id="verification">
      <i class="bi bi-patch-check"></i>
      <span class="action-kicker">Trust</span>
      <h2>Verification</h2>
      <p>{{ $user->nid_number ? 'NID information is saved.' : 'Add NID and ownership papers for faster approvals.' }}</p>
      <a class="btn btn-outline-dark" href="#documents">Upload Documents</a>
    </section>
  </div>
</div>
