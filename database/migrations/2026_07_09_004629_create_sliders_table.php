<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->string('title_one')->nullable();
            $table->string('title_two')->nullable();
            $table->string('image')->nullable();
            $table->string('link')->nullable();
            $table->boolean('status')->default(true);
            $table->unsignedInteger('serial')->default(0);
            $table->string('slider_location')->nullable();
            $table->string('product_slug')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sliders');
    }
};
