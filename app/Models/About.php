<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'subtitle',
        'short_description',
        'long_description',
        'image',
        'image_alt_text',
        'mission_title',
        'mission_description',
        'vision_title',
        'vision_description',
        'display_order',
        'status',
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
    ];

    protected $casts = [
        'display_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
