<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agent_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('agency_id')->nullable()->constrained()->nullOnDelete();
            $table->string('designation', 150)->nullable();
            $table->string('license_no', 100)->nullable();
            $table->text('bio')->nullable();
            $table->unsignedInteger('experience_years')->nullable();
            $table->string('service_area')->nullable();
            $table->decimal('rating', 3, 2)->nullable();
            $table->string('status', 50)->default('active');
            $table->timestamps();

            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agent_profiles');
    }
};
