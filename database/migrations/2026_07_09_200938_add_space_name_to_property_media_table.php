<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('property_media', 'space_name')) {
            return;
        }

        Schema::table('property_media', function (Blueprint $table) {
            $table->string('space_name')->nullable()->after('media_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (! Schema::hasColumn('property_media', 'space_name')) {
            return;
        }

        Schema::table('property_media', function (Blueprint $table) {
            $table->dropColumn('space_name');
        });
    }
};
