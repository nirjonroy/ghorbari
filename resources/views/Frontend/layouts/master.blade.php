@php
  $defaultThemeValue = strtolower((string) data_get($frontendSiteInfo, 'default_theme', 'light'));
  $defaultTheme = in_array($defaultThemeValue, ['light', 'dark'], true)
      ? $defaultThemeValue
      : 'light';
  $textDirectionValue = data_get($frontendSiteInfo, 'text_direction', 'ltr');
  $textDirection = in_array($textDirectionValue, ['ltr', 'rtl'], true) ? $textDirectionValue : 'ltr';
  $metaTitle = trim($__env->yieldContent('meta_title')) ?: data_get($frontendSiteInfo, 'meta_title') ?: data_get($frontendSiteInfo, 'seo_title') ?: $__env->yieldContent('title', 'Land Site');
  $metaDescription = trim($__env->yieldContent('meta_description')) ?: data_get($frontendSiteInfo, 'meta_description') ?: data_get($frontendSiteInfo, 'seo_description');
  $metaKeywords = trim($__env->yieldContent('keywords')) ?: data_get($frontendSiteInfo, 'keywords');
  $metaAuthor = trim($__env->yieldContent('author')) ?: data_get($frontendSiteInfo, 'author');
  $metaPublisher = trim($__env->yieldContent('publisher')) ?: data_get($frontendSiteInfo, 'publisher');
  $metaCopyright = trim($__env->yieldContent('copyright')) ?: data_get($frontendSiteInfo, 'copyright');
  $metaSiteName = trim($__env->yieldContent('site_name')) ?: data_get($frontendSiteInfo, 'site_name') ?: 'Land Site';
  $metaImage = trim($__env->yieldContent('meta_image')) ?: data_get($frontendSiteInfo, 'meta_image');
  $currencyLabel = data_get($frontendSiteInfo, 'currency_icon') ?: data_get($frontendSiteInfo, 'currency_name') ?: 'BDT';
@endphp
<!doctype html>
<html lang="en" dir="{{ $textDirection }}" data-default-theme="{{ $defaultTheme }}" data-theme="{{ $defaultTheme }}">
<head>
  @include('Frontend.partials.head')
  @if(filled(data_get($frontendSiteInfo, 'favicon')))
    <link rel="icon" href="{{ asset(data_get($frontendSiteInfo, 'favicon')) }}">
    <link rel="shortcut icon" href="{{ asset(data_get($frontendSiteInfo, 'favicon')) }}">
  @endif
  @include('Frontend.partials.styles')
  @stack('styles')
</head>
<body class="@yield('body_class', 'frontend-page')" data-currency-label="{{ $currencyLabel }}">
  @include('Frontend.partials.header')
  @yield('content')
  @include('Frontend.partials.footer')
  @include('Frontend.partials.auth-modal')
  @include('Frontend.partials.support-chat')
  @include('Frontend.partials.scripts')
  @stack('scripts')
</body>
</html>



