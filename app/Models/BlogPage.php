<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'hero_title',
        'hero_background_path',
        'hero_background_source',
        'home_section_title',
        'categories_title',
        'recommendation_title',
        'latest_posts_title',
        'tags_title',
        'read_button_text',
        'article_tags_title',
        'comments_section_title',
    ];
}
