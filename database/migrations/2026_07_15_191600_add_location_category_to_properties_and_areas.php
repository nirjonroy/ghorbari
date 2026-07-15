<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('areas', function (Blueprint $table) {
            $table->foreignId('city_id')->nullable()->after('district_id')->constrained()->nullOnDelete();
        });

        Schema::table('properties', function (Blueprint $table) {
            $table->foreignId('district_id')->nullable()->after('address_id')->constrained()->nullOnDelete();
            $table->foreignId('city_id')->nullable()->after('district_id')->constrained()->nullOnDelete();
            $table->foreignId('area_id')->nullable()->after('city_id')->constrained('areas')->nullOnDelete();
            $table->string('property_category', 100)->default('residential')->after('property_type_id');
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropConstrainedForeignId('area_id');
            $table->dropConstrainedForeignId('city_id');
            $table->dropConstrainedForeignId('district_id');
            $table->dropColumn('property_category');
        });

        Schema::table('areas', function (Blueprint $table) {
            $table->dropConstrainedForeignId('city_id');
        });
    }
};
