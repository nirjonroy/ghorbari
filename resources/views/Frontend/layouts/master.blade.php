<!doctype html>
<html lang="en">
<head>
  <title>@yield('title', 'Land Site')</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  @include('Frontend.partials.styles')
  @stack('styles')
</head>
<body>
  @include('Frontend.partials.header')
  @yield('content')
  @include('Frontend.partials.footer')
  @include('Frontend.partials.auth-modal')
  @include('Frontend.partials.scripts')
  @stack('scripts')
</body>
</html>

