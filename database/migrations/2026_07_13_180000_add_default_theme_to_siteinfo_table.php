<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('siteinfo', function (Blueprint $table) {
            $table->string('default_theme', 20)->default('light')->after('text_direction');
        });
    }

    public function down(): void
    {
        Schema::table('siteinfo', function (Blueprint $table) {
            $table->dropColumn('default_theme');
        });
    }
};
