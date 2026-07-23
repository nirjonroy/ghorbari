<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BlogPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'blog_category_id',
        'title',
        'slug',
        'author_name',
        'excerpt',
        'content',
        'quote',
        'featured_image_path',
        'featured_image_source',
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
        'tags',
        'comments',
        'published_at',
        'is_published',
        'show_on_home',
    ];

    protected $casts = [
        'tags' => 'array',
        'comments' => 'array',
        'published_at' => 'datetime',
        'is_published' => 'boolean',
        'show_on_home' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(BlogComment::class);
    }
}
