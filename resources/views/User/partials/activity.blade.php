<div class="row g-4 mt-1">
  <div class="col-lg-6">
    <section class="dashboard-card" id="tours">
      <div class="dashboard-card-head">
        <div>
          <h2>Property Views</h2>
          <p>Total visitor views across your listed properties.</p>
        </div>
      </div>
      <div class="dashboard-timeline">
        <article>
          <span>{{ now()->format('M d') }}</span>
          <div>
            <h3>{{ number_format($stats['views']) }} Total Views</h3>
            <p>Views are counted from published property detail visits.</p>
          </div>
        </article>
      </div>
    </section>
  </div>

  <div class="col-lg-6">
    <section class="dashboard-card" id="inquiries">
      <div class="dashboard-card-head">
        <div>
          <h2>Recent Inquiries</h2>
          <p>Latest messages connected to your user account.</p>
        </div>
      </div>
      <div class="inquiry-list">
        @forelse($dashboardData['recent_messages'] as $message)
          <article>
            <i class="bi bi-chat-left-text"></i>
            <div>
              <h3>{{ $message->subject ?: 'New contact message' }}</h3>
              <p>{{ str($message->message)->limit(70) }}</p>
            </div>
            <span>{{ ucfirst($message->status) }}</span>
          </article>
        @empty
          <article>
            <i class="bi bi-check2-circle"></i>
            <div>
              <h3>No recent inquiries</h3>
              <p>New user messages will appear here.</p>
            </div>
            <span>Clear</span>
          </article>
        @endforelse
      </div>
    </section>
  </div>
</div>
