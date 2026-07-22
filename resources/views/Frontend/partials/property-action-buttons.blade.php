@props([
  'property',
  'labels' => false,
  'shareClass' => '',
  'favoriteClass' => '',
  'only' => null,
])

@php
  $favoritePropertyIds = [];

  if (auth()->check()) {
      $favoritePropertyIds = request()->attributes->get('favorite_property_ids');

      if ($favoritePropertyIds === null) {
          $favoritePropertyIds = \App\Models\Favorite::query()
              ->where('user_id', auth()->id())
              ->pluck('property_id')
              ->all();

          request()->attributes->set('favorite_property_ids', $favoritePropertyIds);
      }
  }

  $isFavorited = in_array($property->id, $favoritePropertyIds, true);
@endphp

@if($only !== 'favorite')
  <button
    type="button"
    class="{{ $shareClass }} js-share-property"
    data-share-title="{{ $property->title }}"
    data-share-url="{{ $property->detailUrl() }}"
    aria-label="Share {{ $property->title }}"
  >
    <i class="bi bi-share"></i>@if($labels) <span>Share</span>@endif
  </button>
@endif
@if($only !== 'share')
  <button
    type="button"
    class="{{ $favoriteClass }} js-favorite-property {{ $isFavorited ? 'is-favorite' : '' }}"
    data-favorite-url="{{ route('frontend.favorites.toggle', $property) }}"
    data-login-url="{{ route('login') }}"
    aria-label="{{ $isFavorited ? 'Remove from favorites' : 'Save '.$property->title }}"
    aria-pressed="{{ $isFavorited ? 'true' : 'false' }}"
  >
    <i class="bi {{ $isFavorited ? 'bi-heart-fill' : 'bi-heart' }}"></i>@if($labels) <span>{{ $isFavorited ? 'Saved' : 'Save' }}</span>@endif
  </button>
@endif
