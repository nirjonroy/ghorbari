<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_pages', function (Blueprint $table) {
            $table->id();
            $table->string('hero_title');
            $table->string('hero_background_path')->nullable();
            $table->string('hero_background_source')->nullable();
            $table->string('home_section_title')->nullable();
            $table->string('categories_title')->nullable();
            $table->string('recommendation_title')->nullable();
            $table->string('latest_posts_title')->nullable();
            $table->string('tags_title')->nullable();
            $table->string('read_button_text')->nullable();
            $table->string('article_tags_title')->nullable();
            $table->string('comments_section_title')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_pages');
    }
};
