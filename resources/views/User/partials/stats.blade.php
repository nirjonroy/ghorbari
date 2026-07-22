<div class="dashboard-stats" id="dashboard">
  <article data-tone="blue">
    <span><i class="bi bi-houses"></i></span>
    <p>My Properties</p>
    <h3>{{ $stats['properties'] }}</h3>
  </article>
  <article data-tone="green">
    <span><i class="bi bi-check2-square"></i></span>
    <p>Published Properties</p>
    <h3>{{ $stats['published'] }}</h3>
  </article>
  <article data-tone="amber">
    <span><i class="bi bi-chat-dots"></i></span>
    <p>Active Inquiries</p>
    <h3>{{ $stats['inquiries'] }}</h3>
  </article>
  <article data-tone="red">
    <span><i class="bi bi-hourglass-split"></i></span>
    <p>Pending Verification</p>
    <h3>{{ $stats['pending'] }}</h3>
  </article>
</div>
