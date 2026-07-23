<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $tables = [
        'abouts',
        'agencies',
        'agent_profiles',
        'amenities',
        'blog_categories',
        'blog_pages',
        'blog_posts',
        'custom_pages',
        'divisions',
        'districts',
        'cities',
        'areas',
        'properties',
        'property_types',
        'siteinfo',
        'sliders',
        'subscription_packages',
    ];

    public function up(): void
    {
        foreach ($this->tables as $tableName) {
            if (! Schema::hasTable($tableName) || Schema::hasColumn($tableName, 'robots')) {
                continue;
            }

            Schema::table($tableName, function (Blueprint $table) {
                $table->string('robots', 50)->default('index_follow');
            });
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $tableName) {
            if (! Schema::hasTable($tableName) || ! Schema::hasColumn($tableName, 'robots')) {
                continue;
            }

            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn('robots');
            });
        }
    }
};
