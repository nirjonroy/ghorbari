<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $tables = [
        'divisions',
        'districts',
        'cities',
        'areas',
    ];

    private array $fields = [
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
                foreach ($this->fields as $field => $type) {
                    if (Schema::hasColumn($tableName, $field)) {
                        continue;
                    }

                    $table->{$type}($field)->nullable();
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
                foreach (array_keys($this->fields) as $field) {
                    if (Schema::hasColumn($tableName, $field)) {
                        $table->dropColumn($field);
                    }
                }
            });
        }
    }
};
