<div class="row g-4 mb-4">
  <div class="col-lg-4">
    <section class="dashboard-card action-card" id="subscription">
      <i class="bi bi-gem"></i>
      <h2>Subscription</h2>
      <p>Your property workspace is active. Subscription details can be connected here.</p>
      <a class="btn btn-outline-dark" href="#payments">Manage Plan</a>
    </section>
  </div>
  <div class="col-lg-4">
    <section class="dashboard-card action-card" id="add-property">
      <i class="bi bi-plus-circle"></i>
      <h2>Add New Property</h2>
      <p>Create a new listing with price, images, location, and availability.</p>
      <a class="btn btn-success" href="#">Add Property</a>
    </section>
  </div>
  <div class="col-lg-4">
    <section class="dashboard-card action-card" id="verification">
      <i class="bi bi-patch-check"></i>
      <h2>Verification</h2>
      <p>{{ $user->nid_number ? 'NID information is saved.' : 'Add NID and ownership papers for faster approvals.' }}</p>
      <a class="btn btn-outline-dark" href="#documents">Upload Documents</a>
    </section>
  </div>
</div>
