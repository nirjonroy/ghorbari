<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('siteinfo') || ! Schema::hasColumn('siteinfo', 'main_format')) {
            return;
        }

        Schema::table('siteinfo', function (Blueprint $table) {
            $table->dropColumn('main_format');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('siteinfo') || Schema::hasColumn('siteinfo', 'main_format')) {
            return;
        }

        Schema::table('siteinfo', function (Blueprint $table) {
            $table->string('main_format', 10)->default('webp')->after('image_output_format');
        });
    }
};
