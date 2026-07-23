<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_name',
        'slug',
        'url_path',
        'template_type',
        'subtitle',
        'short_description',
        'content',
        'background_image',
        'seo_title',
        'seo_description',
        'meta_title',
        'meta_description',
        'meta_image',
        'author',
        'publisher',
        'copyright',
        'site_name',
        'keywords',
        'robots',
        'status',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('status', 'published')
            ->where(function (Builder $inner) {
                $inner->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    public function getPublicUrlAttribute(): string
    {
        return url('/'.trim($this->url_path, '/'));
    }
}
