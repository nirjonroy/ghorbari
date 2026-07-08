<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('siteinfo', function (Blueprint $table) {
            $table->id();
            $table->text('google_location')->nullable();
            $table->text('footer_google_location')->nullable();
            $table->text('footer_contact_note')->nullable();
            $table->boolean('maintenance_mode')->default(false);
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->string('contact_email')->nullable();
            $table->boolean('enable_user_register')->default(true);
            $table->boolean('phone_number_required')->default(false);
            $table->boolean('enable_subscription_notify')->default(false);
            $table->boolean('enable_save_contact_message')->default(true);
            $table->string('text_direction', 10)->default('ltr');
            $table->string('timezone')->default('UTC');
            $table->string('sidebar_lg_header')->nullable();
            $table->string('sidebar_sm_header')->nullable();
            $table->string('topbar_phone')->nullable();
            $table->string('topbar_email')->nullable();
            $table->string('currency_name')->nullable();
            $table->string('currency_icon')->nullable();
            $table->decimal('currency_rate', 12, 4)->default(1);
            $table->string('default_phone_code')->nullable();
            $table->string('frontend_url')->nullable();
            $table->string('homepage_section_title')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('siteinfo');
    }
};
