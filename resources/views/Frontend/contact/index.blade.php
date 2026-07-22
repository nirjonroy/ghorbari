@extends('Frontend.layouts.master')

@section('title', 'Contact | Land Site')
@section('body_class', 'frontend-page frontend-inner-page contact-page')

@php
  $siteInfo = $contactData['site_info'] ?? null;
  $map = $contactData['map'] ?? null;
@endphp

@section('content')
<main data-api-url="{{ $contactData['api_url'] }}">
  <section class="frontend-page-hero">
    <div class="container">
      <a href="{{ route('frontend.home') }}" class="blog-back-link"><i class="bi bi-arrow-left"></i> Back To Home</a>
      <span class="eyebrow">Contact</span>
      <h1>Talk To Land Site</h1>
      <p>{{ $siteInfo?->footer_contact_note ?: 'Send your property question, partnership request, or support message. Our team will get back to you.' }}</p>
    </div>
  </section>

  <section class="contact-section">
    <div class="container">
      <div class="row g-4">
        @foreach($contactData['contact_cards'] as $card)
          <div class="col-lg-4">
            <article class="contact-info-card">
              <i class="bi {{ $card['icon'] }}"></i>
              <span>{{ $card['label'] }}</span>
              @if($card['href'])
                <a href="{{ $card['href'] }}">{{ $card['value'] }}</a>
              @else
                <strong>{{ $card['value'] }}</strong>
              @endif
            </article>
          </div>
        @endforeach
      </div>

      <div class="row g-4 mt-2">
        <div class="col-lg-7">
          <form class="contact-form-panel" action="{{ route('frontend.contact.store') }}" method="POST">
            @csrf
            <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-4">
              <div>
                <span class="eyebrow">Send Message</span>
                <h2>How Can We Help?</h2>
              </div>
              @if(session('status'))
                <div class="contact-alert">{{ session('status') }}</div>
              @endif
            </div>

            <div class="row g-3">
              <div class="col-md-6">
                <label for="contactName">Name</label>
                <input id="contactName" name="name" value="{{ old('name', auth()->user()?->name) }}" class="form-control @error('name') is-invalid @enderror" type="text" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-6">
                <label for="contactEmail">Email</label>
                <input id="contactEmail" name="email" value="{{ old('email', auth()->user()?->email) }}" class="form-control @error('email') is-invalid @enderror" type="email">
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-6">
                <label for="contactPhone">Phone</label>
                <input id="contactPhone" name="phone" value="{{ old('phone', auth()->user()?->phone) }}" class="form-control @error('phone') is-invalid @enderror" type="text">
                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-6">
                <label for="contactSubject">Subject</label>
                <input id="contactSubject" name="subject" value="{{ old('subject') }}" class="form-control @error('subject') is-invalid @enderror" type="text">
                @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-12">
                <label for="contactMessage">Message</label>
                <textarea id="contactMessage" name="message" rows="6" class="form-control @error('message') is-invalid @enderror" required>{{ old('message') }}</textarea>
                @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
            </div>

            <button class="btn btn-danger mt-4 px-4" type="submit"><i class="bi bi-send"></i> Send Message</button>
          </form>
        </div>
        <div class="col-lg-5">
          <aside class="contact-map-panel">
            <h2>Find Us</h2>
            <p>{{ $siteInfo?->footer_contact_note ?: 'Use the contact details or send a message directly from this page.' }}</p>
            @if(filled($map) && str_contains(strtolower($map), '<iframe'))
              <div class="contact-map-embed">{!! $map !!}</div>
            @elseif(filled($map))
              <a href="{{ $map }}" target="_blank" rel="noopener" class="btn btn-dark">Open Location</a>
            @else
              <div class="contact-map-placeholder">
                <i class="bi bi-map"></i>
                <span>Map location has not been added yet.</span>
              </div>
            @endif
          </aside>
        </div>
      </div>
    </div>
  </section>
</main>
@endsection
