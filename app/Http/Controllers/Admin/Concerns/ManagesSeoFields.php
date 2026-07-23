<?php

namespace App\Http\Controllers\Admin\Concerns;

use App\Models\SiteInfo;
use App\Services\ImageUploadService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

trait ManagesSeoFields
{
    protected function seoValidationRules(): array
    {
        return [
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'meta_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'author' => ['nullable', 'string', 'max:255'],
            'publisher' => ['nullable', 'string', 'max:255'],
            'copyright' => ['nullable', 'string', 'max:255'],
            'site_name' => ['nullable', 'string', 'max:255'],
            'keywords' => ['nullable', 'string'],
            'robots' => ['required', 'in:index_follow,noindex_nofollow'],
        ];
    }

    protected function storeSeoImage(Request $request, ?Model $model = null, string $directory = 'uploads/seo'): ?string
    {
        if (! $request->hasFile('meta_image')) {
            return null;
        }

        $siteInfo = SiteInfo::query()->first();

        return (new ImageUploadService())->storeConverted(
            $request->file('meta_image'),
            $directory,
            $siteInfo?->blog_page_image_width,
            $siteInfo?->blog_page_image_height,
            $model?->meta_image,
            $siteInfo?->image_output_format ?? 'webp'
        );
    }

    protected function applySeoImage(Request $request, array $data, ?Model $model = null, string $directory = 'uploads/seo'): array
    {
        unset($data['meta_image']);

        $path = $this->storeSeoImage($request, $model, $directory);

        if ($path) {
            $data['meta_image'] = $path;
        }

        return $data;
    }
}
