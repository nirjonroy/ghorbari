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
        'properties',
        'property_types',
        'siteinfo',
        'sliders',
        'subscription_packages',
    ];

    private array $columns = [
        'seo_title' => 'string',
        'seo_description' => 'text',
        'meta_title' => 'string',
        'meta_description' => 'text',
        'meta_image' => 'string',
        'author' => 'string',
        'publisher' => 'string',
        'copyright' => 'string',
        'site_name' => 'string',
        'keywords' => 'text',
    ];

    public function up(): void
    {
        foreach ($this->tables as $tableName) {
            if (! Schema::hasTable($tableName)) {
                continue;
            }

            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                foreach ($this->columns as $column => $type) {
                    if (Schema::hasColumn($tableName, $column)) {
                        continue;
                    }

                    match ($type) {
                        'text' => $table->text($column)->nullable(),
                        default => $table->string($column)->nullable(),
                    };
                }
            });
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $tableName) {
            if (! Schema::hasTable($tableName)) {
                continue;
            }

            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                $existingColumns = array_filter(
                    array_keys($this->columns),
                    fn (string $column) => Schema::hasColumn($tableName, $column)
                );

                if ($existingColumns) {
                    $table->dropColumn($existingColumns);
                }
            });
        }
    }
};
