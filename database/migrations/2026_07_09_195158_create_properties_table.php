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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedBigInteger('agent_profile_id')->nullable()->index();
            $table->unsignedBigInteger('agency_id')->nullable()->index();
            $table->foreignId('property_type_id')->constrained('property_types')->restrictOnDelete();
            $table->unsignedBigInteger('address_id')->index();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('listing_type', 50);
            $table->string('property_status', 50)->default('available');
            $table->decimal('price', 15, 2);
            $table->string('rent_period', 50)->nullable();
            $table->decimal('area_size', 10, 2)->nullable();
            $table->decimal('land_size', 10, 2)->nullable();
            $table->integer('bedrooms')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->integer('balconies')->nullable();
            $table->integer('floor_no')->nullable();
            $table->integer('total_floors')->nullable();
            $table->integer('parking_spaces')->nullable();
            $table->string('furnishing_status', 50)->nullable();
            $table->longText('description')->nullable();
            $table->string('verification_status', 50)->default('pending');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('properties');
    }
};
