<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('siteinfo', function (Blueprint $table) {
            $table->unsignedInteger('agency_logo_width')->nullable()->after('blog_page_image_height');
            $table->unsignedInteger('agency_logo_height')->nullable()->after('agency_logo_width');
        });
    }

    public function down(): void
    {
        Schema::table('siteinfo', function (Blueprint $table) {
            $table->dropColumn(['agency_logo_width', 'agency_logo_height']);
        });
    }
};
