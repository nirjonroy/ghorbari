@php
  $title = $metaTitle;
  $desc = $metaDescription ?: 'Find homes, apartments, land, agents, and real estate information across Bangladesh.';
  $author = $metaAuthor;
  $publisher = $metaPublisher;
  $copyright = $metaCopyright;
  $keywords = $metaKeywords;
  $siteName = $metaSiteName ?: 'Land Site';
  $url = trim($__env->yieldContent('canonical_url')) ?: url()->current();
  $robotsValue = trim($__env->yieldContent('robots')) ?: data_get($frontendSiteInfo, 'robots', 'index_follow');
  $indexable = $robotsValue !== 'noindex_nofollow';
  $updatedIso = trim($__env->yieldContent('updated_time')) ?: now()->toIso8601String();
  $twitter = trim($__env->yieldContent('twitter_site')) ?: data_get($frontendSiteInfo, 'twitter_site', '@landsite');
  $rawImage = $metaImage ?: data_get($frontendSiteInfo, 'logo') ?: 'frontend/assets/images/logo.png';
  $ogImage = \Illuminate\Support\Str::startsWith($rawImage, ['http://', 'https://'])
      ? $rawImage
      : asset($rawImage);
@endphp

@hasSection('seos')
  @yield('seos')
@else
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <meta name="title" content="{{ $title }}">
  <meta name="description" content="{{ $desc }}">
  @if ($author)
    <meta name="author" content="{{ $author }}">
  @endif
  @if ($publisher)
    <meta name="publisher" content="{{ $publisher }}">
  @endif
  @if ($copyright)
    <meta name="copyright" content="{{ $copyright }}">
  @endif
  @if ($keywords)
    <meta name="keywords" content="{{ $keywords }}">
  @endif
  <link rel="canonical" href="{{ $url }}">
  <meta name="robots" content="{{ $indexable ? 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1' : 'noindex, nofollow' }}">

  <meta property="og:type" content="website">
  <meta property="og:site_name" content="{{ $siteName }}">
  <meta property="og:title" content="{{ $title }}">
  <meta property="og:description" content="{{ $desc }}">
  <meta property="og:url" content="{{ $url }}">
  <meta property="og:image" content="{{ $ogImage }}">
  <meta property="og:image:secure_url" content="{{ $ogImage }}">
  <meta property="og:image:alt" content="{{ $siteName }}">
  <meta property="og:updated_time" content="{{ $updatedIso }}">
  <meta property="og:locale" content="en_US">
  @if ($publisher)
    <meta property="article:publisher" content="{{ $publisher }}">
  @endif
  @if ($author)
    <meta property="article:author" content="{{ $author }}">
  @endif

  <meta name="twitter:card" content="summary">
  <meta name="twitter:site" content="{{ $twitter }}">
  <meta name="twitter:title" content="{{ $title }}">
  <meta name="twitter:description" content="{{ $desc }}">
  <meta name="twitter:url" content="{{ $url }}">
  <meta name="twitter:image" content="{{ $ogImage }}">

  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
@endif
