<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('siteinfo')) {
            return;
        }

        Schema::table('siteinfo', function (Blueprint $table) {
            if (! Schema::hasColumn('siteinfo', 'facebook_url')) {
                $table->string('facebook_url')->nullable()->after('footer_contact_note');
            }

            if (! Schema::hasColumn('siteinfo', 'instagram_url')) {
                $table->string('instagram_url')->nullable()->after('facebook_url');
            }

            if (! Schema::hasColumn('siteinfo', 'linkedin_url')) {
                $table->string('linkedin_url')->nullable()->after('instagram_url');
            }

            if (! Schema::hasColumn('siteinfo', 'youtube_url')) {
                $table->string('youtube_url')->nullable()->after('linkedin_url');
            }

            if (! Schema::hasColumn('siteinfo', 'twitter_url')) {
                $table->string('twitter_url')->nullable()->after('youtube_url');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('siteinfo')) {
            return;
        }

        Schema::table('siteinfo', function (Blueprint $table) {
            foreach (['twitter_url', 'youtube_url', 'linkedin_url', 'instagram_url', 'facebook_url'] as $column) {
                if (Schema::hasColumn('siteinfo', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
