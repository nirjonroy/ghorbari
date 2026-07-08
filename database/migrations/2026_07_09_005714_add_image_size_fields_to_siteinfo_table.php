<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('siteinfo', function (Blueprint $table) {
            $table->unsignedInteger('logo_width')->nullable()->after('logo');
            $table->unsignedInteger('logo_height')->nullable()->after('logo_width');
            $table->unsignedInteger('favicon_width')->nullable()->after('favicon');
            $table->unsignedInteger('favicon_height')->nullable()->after('favicon_width');
            $table->unsignedInteger('slider_width')->nullable()->after('homepage_section_title');
            $table->unsignedInteger('slider_height')->nullable()->after('slider_width');
        });
    }

    public function down()
    {
        Schema::table('siteinfo', function (Blueprint $table) {
            $table->dropColumn([
                'logo_width',
                'logo_height',
                'favicon_width',
                'favicon_height',
                'slider_width',
                'slider_height',
            ]);
        });
    }
};
