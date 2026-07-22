@extends('Frontend.layouts.master')

@section('title', (($aboutData['about']->title ?? null) ?: 'About Us') . ' | Land Site')
@section('body_class', 'frontend-page frontend-inner-page about-page')

@php
  $about = $aboutData['about'] ?? null;
  $title = $about?->title ?: 'About Land Site';
  $subtitle = $about?->subtitle ?: 'A smarter way to buy, sell, and rent property across Bangladesh.';
  $shortDescription = $about?->short_description ?: 'Land Site helps property seekers, owners, agents, and developers compare trusted listings with useful local context.';
  $longDescription = $about?->long_description ?: 'We are building a practical real estate platform for Bangladesh with verified property data, location-led search, agent support, and tools that help people make better housing decisions.';
  $aboutImage = $about?->image ? asset($about->image) : asset('frontend/assets/images/about_img_1.png');
@endphp

@section('content')
<main data-api-url="{{ $aboutData['api_url'] }}">
  <section class="frontend-page-hero">
    <div class="container">
      <a href="{{ route('frontend.home') }}" class="blog-back-link"><i class="bi bi-arrow-left"></i> Back To Home</a>
      <span class="eyebrow">About Us</span>
      <h1>{{ $title }}</h1>
      <p>{{ $subtitle }}</p>
    </div>
  </section>

  <section class="about-story-section">
    <div class="container">
      <div class="row g-5 align-items-center">
        <div class="col-lg-6">
          <img src="{{ $aboutImage }}" alt="{{ $about?->image_alt_text ?: $title }}" class="about-story-image">
        </div>
        <div class="col-lg-6">
          <span class="eyebrow">Who We Are</span>
          <h2>{{ $shortDescription }}</h2>
          <div class="about-rich-text">{!! $longDescription !!}</div>
          <div class="about-stat-grid">
            @foreach($aboutData['stats'] as $stat)
              <div>
                <strong>{{ $stat['value'] }}</strong>
                <span>{{ $stat['label'] }}</span>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="about-feature-section">
    <div class="container">
      <div class="row g-4">
        <div class="col-md-6">
          <article class="about-feature-card">
            <i class="bi bi-compass"></i>
            <h2>{{ $about?->mission_title ?: 'Our Mission' }}</h2>
            <p>{{ $about?->mission_description ?: 'Make property decisions easier with clearer information, better search paths, and verified local support.' }}</p>
          </article>
        </div>
        <div class="col-md-6">
          <article class="about-feature-card">
            <i class="bi bi-buildings"></i>
            <h2>{{ $about?->vision_title ?: 'Our Vision' }}</h2>
            <p>{{ $about?->vision_description ?: 'Create a trusted Bangladesh property marketplace where buyers, renters, owners, and agents work with confidence.' }}</p>
          </article>
        </div>
      </div>
    </div>
  </section>
</main>
@endsection
