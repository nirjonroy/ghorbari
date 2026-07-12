<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('siteinfo', function (Blueprint $table) {
            $table->unsignedInteger('about_image_width')->nullable()->after('slider_height');
            $table->unsignedInteger('about_image_height')->nullable()->after('about_image_width');
            $table->unsignedInteger('property_image_width')->nullable()->after('about_image_height');
            $table->unsignedInteger('property_image_height')->nullable()->after('property_image_width');
            $table->unsignedInteger('blog_post_image_width')->nullable()->after('property_image_height');
            $table->unsignedInteger('blog_post_image_height')->nullable()->after('blog_post_image_width');
            $table->unsignedInteger('blog_page_image_width')->nullable()->after('blog_post_image_height');
            $table->unsignedInteger('blog_page_image_height')->nullable()->after('blog_page_image_width');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('siteinfo', function (Blueprint $table) {
            $table->dropColumn([
                'about_image_width',
                'about_image_height',
                'property_image_width',
                'property_image_height',
                'blog_post_image_width',
                'blog_post_image_height',
                'blog_page_image_width',
                'blog_page_image_height',
            ]);
        });
    }
};
