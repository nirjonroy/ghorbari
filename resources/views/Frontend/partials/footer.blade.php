@php
  $footerLogo = filled(data_get($frontendSiteInfo, 'logo'))
      ? asset(data_get($frontendSiteInfo, 'logo'))
      : asset('frontend/assets/images/logo.png');
  $footerNote = trim(strip_tags((string) data_get($frontendSiteInfo, 'footer_contact_note')));
  $siteName = data_get($frontendSiteInfo, 'site_name') ?: data_get($frontendSiteInfo, 'sidebar_lg_header') ?: 'Land Site';
  $copyright = data_get($frontendSiteInfo, 'copyright') ?: 'Designed and developed by BlackTech Corp.';
  $topbarEmail = data_get($frontendSiteInfo, 'topbar_email') ?: data_get($frontendSiteInfo, 'contact_email');
  $topbarPhone = data_get($frontendSiteInfo, 'topbar_phone');
  $socialLinks = collect([
      'facebook' => ['url' => data_get($frontendSiteInfo, 'facebook_url'), 'icon' => 'bi-facebook', 'label' => 'Facebook'],
      'instagram' => ['url' => data_get($frontendSiteInfo, 'instagram_url'), 'icon' => 'bi-instagram', 'label' => 'Instagram'],
      'linkedin' => ['url' => data_get($frontendSiteInfo, 'linkedin_url'), 'icon' => 'bi-linkedin', 'label' => 'LinkedIn'],
      'youtube' => ['url' => data_get($frontendSiteInfo, 'youtube_url'), 'icon' => 'bi-youtube', 'label' => 'YouTube'],
      'twitter' => ['url' => data_get($frontendSiteInfo, 'twitter_url'), 'icon' => 'bi-twitter-x', 'label' => 'Twitter/X'],
  ])->filter(fn ($link) => filled($link['url']));
@endphp
<footer class="footer">
    <div class="container">
      <div class="footer-grid">
        <div class="footer-brand">
          <img src="{{ $footerLogo }}" alt="{{ data_get($frontendSiteInfo, 'sidebar_lg_header', 'Land Site') }} logo">
          <p>{{ $footerNote ?: $siteName.' helps buyers, sellers, and renters compare trusted property options across Bangladesh.' }}</p>
        </div>
        <div>
          <h2>Join Us</h2>
          <a href="{{ route('frontend.agents.index') }}">Become An Agent</a>
          <a href="#">List Your Property</a>
          <a href="{{ route('frontend.agents.index') }}">Partner With Us</a>
        </div>
        <div>
          <h2>Company</h2>
          <a href="{{ route('frontend.about.index') }}">About Us</a>
          <a href="{{ route('frontend.agents.index') }}">Agents</a>
          <a href="{{ route('frontend.blog.index') }}">Blog</a>
        </div>
        <div>
          <h2>Find Us</h2>
          <a href="{{ route('frontend.contact.index') }}">Contact</a>
          @if($topbarEmail)
            <a href="mailto:{{ $topbarEmail }}">{{ $topbarEmail }}</a>
          @endif
          @if($topbarPhone)
            <a href="tel:{{ $topbarPhone }}">{{ $topbarPhone }}</a>
          @endif
          <a href="#">Help Center</a>
          @if($socialLinks->isNotEmpty())
            <div class="footer-social">
              @foreach($socialLinks as $socialLink)
                <a href="{{ $socialLink['url'] }}" aria-label="{{ $socialLink['label'] }}" target="_blank" rel="noopener noreferrer">
                  <i class="bi {{ $socialLink['icon'] }}"></i>
                </a>
              @endforeach
            </div>
          @endif
        </div>
      </div>
      <div class="footer-bottom">
        <p>&copy; {{ now()->year }} {{ $siteName }}. {{ $copyright }}</p>
        <div>
          <a href="#">Privacy Policy</a>
          <a href="#">Terms Of Use</a>
        </div>
      </div>
    </div>
  </footer>

