<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_one',
        'title_two',
        'image',
        'link',
        'status',
        'serial',
        'slider_location',
        'product_slug',
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
        'status' => 'boolean',
        'serial' => 'integer',
    ];
}
