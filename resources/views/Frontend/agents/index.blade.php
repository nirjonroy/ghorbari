@extends('Frontend.layouts.master')

@section('title', 'Real Estate Agents | Land Site')
@section('body_class', 'frontend-page agents-page')

@section('content')
@php
    $agents = $agentsData['agents'];
    $agentList = method_exists($agents, 'getCollection') ? $agents->getCollection() : collect($agents);
    $featuredAgents = $agentList->take(3);
    $fallbackImages = [
        'https://www.blacktechcorp.com/uploads/team-members/nirjon-roy-20260115054821.jpg',
        'https://www.blacktechcorp.com/uploads/team-members/rakib-alom-20260115055615.jpg',
        'https://www.blacktechcorp.com/uploads/team-members/anupam-mahmud-ozone-20260216124252.jpg',
        'https://www.blacktechcorp.com/uploads/team-members/sara-shahrin-20260115060122.jpg',
    ];
    $agentImage = fn ($agent, $index = 0) => $agent->user?->profile_photo_path
        ? asset($agent->user->profile_photo_path)
        : $fallbackImages[$index % count($fallbackImages)];
    $agentTags = function ($agent) {
        return collect(explode(',', (string) $agent->service_area))
            ->map(fn ($tag) => trim($tag))
            ->filter()
            ->take(3)
            ->whenEmpty(fn ($tags) => $tags->push($agent->agency?->name ?: 'Bangladesh')->push('Property Advisor')->push('Verified'));
    };
@endphp

<main data-api-url="{{ route('api.frontend.agents.index') }}">
  <section class="agents-hero">
    <div class="container">
      <div class="row g-4 align-items-center">
        <div class="col-lg-7">
          <span class="eyebrow">Trusted Bangladesh Property Advisors</span>
          <h1>Work With An Expert Agent Who Knows Your Market</h1>
          <p>Compare neighborhoods, pricing, documents, tours, and negotiations with verified Land Site agents across Dhaka, Sylhet, Chattogram, Rajshahi, and more.</p>
          <form class="agents-search" method="GET" action="{{ route('frontend.agents.index') }}">
            <div>
              <label for="agentArea">Find an agent near</label>
              <input id="agentArea" name="q" class="form-control" type="search" value="{{ request('q') }}" placeholder="City, area, or postcode">
            </div>
            <div>
              <label for="agentService">Service</label>
              <select id="agentService" name="service" class="form-select">
                <option value="">Any service</option>
                @foreach(['Buy a home', 'Sell a property', 'Rent a flat', 'Investment advice'] as $service)
                  <option value="{{ $service }}" @selected(request('service') === $service)>{{ $service }}</option>
                @endforeach
              </select>
            </div>
            <button class="btn btn-danger" type="submit"><i class="bi bi-search"></i> Search</button>
          </form>
          <div class="agents-hero-stats">
            <div><strong>{{ number_format((float) $agentsData['stats']['average_rating'], 1) }}</strong><span>Average rating</span></div>
            <div><strong>{{ number_format((int) $agentsData['stats']['closed_deals']) }}</strong><span>Closed deals</span></div>
            <div><strong>{{ $agentsData['stats']['response_time'] }}</strong><span>Response time</span></div>
          </div>
        </div>
        <div class="col-lg-5">
          <div class="agent-spotlight-card">
            <div class="agent-spotlight-images">
              @forelse($featuredAgents as $agent)
                <img src="{{ $agentImage($agent, $loop->index) }}" alt="{{ $agent->user?->name ?? 'Agent' }}">
              @empty
                @foreach(array_slice($fallbackImages, 0, 3) as $image)
                  <img src="{{ $image }}" alt="Land Site agent">
                @endforeach
              @endforelse
            </div>
            <h2>Top Rated Local Team</h2>
            <p>Agents are matched by city, property type, budget range, and recent transaction experience.</p>
            <ul>
              <li><i class="bi bi-check2-circle"></i> Verified profile and client history</li>
              <li><i class="bi bi-check2-circle"></i> Area pricing and document guidance</li>
              <li><i class="bi bi-check2-circle"></i> Tour scheduling and offer support</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="agents-section">
    <div class="container">
      <div class="section-head">
        <span class="eyebrow">Featured Agents</span>
        <h2>Meet Experienced Property Experts</h2>
        <p>Shortlist an agent by city, specialty, and service quality.</p>
      </div>
      <div class="row g-4">
        @forelse($agentList as $agent)
          <div class="col-lg-4 col-md-6">
            <article class="agent-card">
              <div class="agent-card-top">
                <img src="{{ $agentImage($agent, $loop->index) }}" alt="{{ $agent->user?->name ?? 'Agent' }}">
                <span><i class="bi bi-star-fill"></i> {{ number_format((float) ($agent->rating ?: 4.8), 1) }}</span>
              </div>
              <h3>{{ $agent->user?->name ?? 'Land Site Agent' }}</h3>
              <p class="agent-role">{{ $agent->designation ?: 'Property Advisor' }}</p>
              <p class="agent-location"><i class="bi bi-geo-alt"></i> {{ $agent->service_area ?: 'Bangladesh' }}</p>
              <div class="agent-tags">
                @foreach($agentTags($agent) as $tag)
                  <span>{{ $tag }}</span>
                @endforeach
              </div>
              <div class="agent-metrics">
                <div><strong>{{ $agent->properties_count ?? 0 }}</strong><small>Listings</small></div>
                <div><strong>{{ $agent->experience_years ?? 0 }} yrs</strong><small>Experience</small></div>
                <div><strong>{{ $agent->license_no ? 'Yes' : 'New' }}</strong><small>License</small></div>
              </div>
              <a href="mailto:{{ $agent->user?->email }}" class="btn btn-dark w-100">Contact Agent</a>
            </article>
          </div>
        @empty
          <div class="col-12">
            <article class="agent-match-panel">
              <div>
                <span class="eyebrow">Agents</span>
                <h3>No Active Agents Found</h3>
                <p>Add active agent profiles from the admin panel and they will appear here automatically.</p>
              </div>
              <a href="{{ route('frontend.home') }}" class="btn btn-danger">Back Home</a>
            </article>
          </div>
        @endforelse

        <div class="col-lg-8">
          <article class="agent-match-panel">
            <div>
              <span class="eyebrow">Smart Matching</span>
              <h3>Not Sure Who To Contact?</h3>
              <p>Tell us your preferred city, budget, and property goal. Land Site will match you with an agent who has recent experience in that exact market.</p>
            </div>
            <button class="btn btn-danger" type="button" data-bs-toggle="modal" data-bs-target="#authModal">Get Matched</button>
          </article>
        </div>
      </div>

      @if(method_exists($agents, 'hasPages') && $agents->hasPages())
        <div class="mt-5">{{ $agents->links() }}</div>
      @endif
    </div>
  </section>

  <section class="agent-process-section">
    <div class="container">
      <div class="row g-4 align-items-center">
        <div class="col-lg-5">
          <span class="eyebrow">How It Works</span>
          <h2>From Shortlist To Closing, Your Agent Handles The Details</h2>
          <p>Our agents help you compare listings, verify documents, arrange visits, negotiate confidently, and avoid hidden costs.</p>
        </div>
        <div class="col-lg-7">
          <div class="agent-process-grid">
            <div><i class="bi bi-search"></i><h3>Discover</h3><p>Shortlist homes by budget, area, commute, and lifestyle.</p></div>
            <div><i class="bi bi-file-earmark-check"></i><h3>Verify</h3><p>Review ownership, mutation, service charges, and handover readiness.</p></div>
            <div><i class="bi bi-calendar-check"></i><h3>Tour</h3><p>Schedule visits and compare property condition with market price.</p></div>
            <div><i class="bi bi-hand-thumbs-up"></i><h3>Close</h3><p>Move from offer to paperwork with practical local guidance.</p></div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
@endsection
