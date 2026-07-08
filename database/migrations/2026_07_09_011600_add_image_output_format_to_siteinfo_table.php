<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('siteinfo', function (Blueprint $table) {
            $table->string('image_output_format', 10)->default('webp')->after('favicon_height');
        });
    }

    public function down(): void
    {
        Schema::table('siteinfo', function (Blueprint $table) {
            $table->dropColumn('image_output_format');
        });
    }
};
