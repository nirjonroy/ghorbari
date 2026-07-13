@php
  $defaultThemeValue = data_get($frontendSiteInfo, 'default_theme', 'light');
  $defaultTheme = in_array($defaultThemeValue, ['light', 'dark'], true)
      ? $defaultThemeValue
      : 'light';
@endphp
<!doctype html>
<html lang="en" data-default-theme="{{ $defaultTheme }}" data-theme="{{ $defaultTheme }}">
<head>
  <title>@yield('title', 'Land Site')</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
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



