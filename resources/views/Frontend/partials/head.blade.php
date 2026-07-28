@php
  $urlMeta = null;

  try {
      if (class_exists(\Nirjon\LaravelSeo\Models\SeoMeta::class)
          && \Illuminate\Support\Facades\Schema::hasTable('nirjon_seo_metas')) {
          $currentPath = '/' . ltrim(request()->path(), '/');
          $currentPath = $currentPath === '//' ? '/' : (rtrim($currentPath, '/') ?: '/');
          $urlMeta = \Nirjon\LaravelSeo\Models\SeoMeta::query()
              ->where('seoable_type', 'url')
              ->where('is_active', true)
              ->where('url_path', $currentPath)
              ->latest('id')
              ->first();
      }
  } catch (\Throwable $exception) {
      $urlMeta = null;
  }

  $title = $metaTitle;
  $desc = $metaDescription ?: 'Find homes, apartments, land, agents, and real estate information across Bangladesh.';
  $author = $metaAuthor;
  $publisher = $metaPublisher;
  $copyright = $metaCopyright;
  $keywords = $metaKeywords;
  $siteName = $metaSiteName ?: 'Land Site';
  $url = trim($__env->yieldContent('canonical_url')) ?: url()->current();
  $robotsValue = trim($__env->yieldContent('robots')) ?: data_get($frontendSiteInfo, 'robots', 'index_follow');
  $robotsTag = $robotsValue !== 'noindex_nofollow'
      ? 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1'
      : 'noindex, nofollow';
  $updatedIso = trim($__env->yieldContent('updated_time')) ?: now()->toIso8601String();
  $twitter = trim($__env->yieldContent('twitter_site')) ?: data_get($frontendSiteInfo, 'twitter_site', '@landsite');
  $rawImage = $metaImage ?: data_get($frontendSiteInfo, 'logo') ?: 'frontend/assets/images/logo.png';
  $ogImage = \Illuminate\Support\Str::startsWith($rawImage, ['http://', 'https://'])
      ? $rawImage
      : asset($rawImage);

  if ($urlMeta) {
      $title = $urlMeta->title ?: $title;
      $desc = $urlMeta->description ?: $desc;
      $keywords = $urlMeta->keywords ?: $keywords;
      $url = $urlMeta->canonical_url ?: $url;
      $author = $urlMeta->author ?: $author;
      $publisher = $urlMeta->publisher ?: $publisher;
      $copyright = $urlMeta->copyright ?: $copyright;
      $siteName = $urlMeta->site_name ?: $siteName;
      $robotsTag = $urlMeta->robots_tag ?: $robotsTag;
      $ogImage = $urlMeta->og_image ?: $urlMeta->twitter_image ?: $ogImage;
  }
@endphp

@hasSection('seos')
  @yield('seos')
@else
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <x-seo::tags
    :title="$title"
    :description="$desc"
    :keywords="$keywords"
    :canonical="$url"
    :og-title="$title"
    :og-description="$desc"
    :image="$ogImage"
    :author="$author"
    :publisher="$publisher"
    :copyright="$copyright"
    :site-name="$siteName"
    :robots="$robotsTag"
    :twitter-title="$title"
    :twitter-description="$desc"
    :twitter-image="$ogImage"
  />

  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
@endif
