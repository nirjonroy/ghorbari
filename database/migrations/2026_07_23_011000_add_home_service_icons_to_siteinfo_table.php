<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('siteinfo', function (Blueprint $table) {
            $table->string('buy_home_icon')->nullable()->after('favicon_height');
            $table->string('sell_home_icon')->nullable()->after('buy_home_icon');
            $table->string('rent_property_icon')->nullable()->after('sell_home_icon');
        });
    }

    public function down(): void
    {
        Schema::table('siteinfo', function (Blueprint $table) {
            $table->dropColumn([
                'buy_home_icon',
                'sell_home_icon',
                'rent_property_icon',
            ]);
        });
    }
};
