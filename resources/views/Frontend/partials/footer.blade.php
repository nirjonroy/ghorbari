@php
  $footerLogo = filled(data_get($frontendSiteInfo, 'logo'))
      ? asset(data_get($frontendSiteInfo, 'logo'))
      : asset('frontend/assets/images/logo.png');
@endphp
<footer class="footer">
    <div class="container">
      <div class="footer-grid">
        <div class="footer-brand">
          <img src="{{ $footerLogo }}" alt="{{ data_get($frontendSiteInfo, 'sidebar_lg_header', 'Land Site') }} logo">
          <p>Land Site helps buyers, sellers, and renters compare trusted property options across Bangladesh.</p>
        </div>
        <div>
          <h2>Join Us</h2>
          <a href="{{ route('frontend.agents.index') }}">Become An Agent</a>
          <a href="#">List Your Property</a>
          <a href="{{ route('frontend.agents.index') }}">Partner With Us</a>
        </div>
        <div>
          <h2>Company</h2>
          <a href="#">About Us</a>
          <a href="{{ route('frontend.agents.index') }}">Agents</a>
          <a href="{{ route('frontend.blog.index') }}">Blog</a>
        </div>
        <div>
          <h2>Find Us</h2>
          <a href="#">Contact</a>
          <a href="#">Help Center</a>
          <div class="footer-social">
            <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
            <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
            <a href="#" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
          </div>
        </div>
      </div>
      <div class="footer-bottom">
        <p>&copy; 2026 Land Site. Designed and developed by BlackTech Corp.</p>
        <div>
          <a href="#">Privacy Policy</a>
          <a href="#">Terms Of Use</a>
        </div>
      </div>
    </div>
  </footer>

