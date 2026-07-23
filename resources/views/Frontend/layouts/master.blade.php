@php
  $defaultThemeValue = data_get($frontendSiteInfo, 'default_theme', 'light');
  $defaultTheme = in_array($defaultThemeValue, ['light', 'dark'], true)
      ? $defaultThemeValue
      : 'light';
  $metaTitle = trim($__env->yieldContent('meta_title')) ?: data_get($frontendSiteInfo, 'meta_title') ?: data_get($frontendSiteInfo, 'seo_title') ?: $__env->yieldContent('title', 'Land Site');
  $metaDescription = trim($__env->yieldContent('meta_description')) ?: data_get($frontendSiteInfo, 'meta_description') ?: data_get($frontendSiteInfo, 'seo_description');
  $metaKeywords = trim($__env->yieldContent('keywords')) ?: data_get($frontendSiteInfo, 'keywords');
  $metaAuthor = trim($__env->yieldContent('author')) ?: data_get($frontendSiteInfo, 'author');
  $metaPublisher = trim($__env->yieldContent('publisher')) ?: data_get($frontendSiteInfo, 'publisher');
  $metaCopyright = trim($__env->yieldContent('copyright')) ?: data_get($frontendSiteInfo, 'copyright');
  $metaSiteName = trim($__env->yieldContent('site_name')) ?: data_get($frontendSiteInfo, 'site_name') ?: 'Land Site';
  $metaImage = trim($__env->yieldContent('meta_image')) ?: data_get($frontendSiteInfo, 'meta_image');
@endphp
<!doctype html>
<html lang="en" data-default-theme="{{ $defaultTheme }}" data-theme="{{ $defaultTheme }}">
<head>
  <title>{{ $metaTitle }}</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @if($metaDescription)
    <meta name="description" content="{{ $metaDescription }}">
  @endif
  @if($metaKeywords)
    <meta name="keywords" content="{{ $metaKeywords }}">
  @endif
  @if($metaAuthor)
    <meta name="author" content="{{ $metaAuthor }}">
  @endif
  @if($metaPublisher)
    <meta name="publisher" content="{{ $metaPublisher }}">
  @endif
  @if($metaCopyright)
    <meta name="copyright" content="{{ $metaCopyright }}">
  @endif
  <meta property="og:title" content="{{ $metaTitle }}">
  @if($metaDescription)
    <meta property="og:description" content="{{ $metaDescription }}">
  @endif
  <meta property="og:site_name" content="{{ $metaSiteName }}">
  @if($metaImage)
    <meta property="og:image" content="{{ asset($metaImage) }}">
  @endif
  @if(filled(data_get($frontendSiteInfo, 'favicon')))
    <link rel="icon" href="{{ asset(data_get($frontendSiteInfo, 'favicon')) }}">
    <link rel="shortcut icon" href="{{ asset(data_get($frontendSiteInfo, 'favicon')) }}">
  @endif
  @include('Frontend.partials.styles')
  @stack('styles')
</head>
<body class="@yield('body_class', 'frontend-page')">
  @include('Frontend.partials.header')
  @yield('content')
  @include('Frontend.partials.footer')
  @include('Frontend.partials.auth-modal')
  @include('Frontend.partials.scripts')
  @stack('scripts')
</body>
</html>



