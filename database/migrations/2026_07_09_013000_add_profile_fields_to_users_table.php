<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('account_type')->nullable()->after('name');
            $table->string('phone')->nullable()->after('email');
            $table->string('alternative_phone')->nullable()->after('phone');
            $table->date('date_of_birth')->nullable()->after('alternative_phone');
            $table->string('gender')->nullable()->after('date_of_birth');
            $table->string('profession')->nullable()->after('gender');
            $table->string('home_name')->nullable()->after('profession');
            $table->string('home_type')->nullable()->after('home_name');
            $table->text('present_address')->nullable()->after('home_type');
            $table->text('permanent_address')->nullable()->after('present_address');
            $table->string('area_name')->nullable()->after('permanent_address');
            $table->string('post_office')->nullable()->after('area_name');
            $table->string('postal_code')->nullable()->after('post_office');
            $table->string('upazila')->nullable()->after('postal_code');
            $table->string('district')->nullable()->after('upazila');
            $table->string('division')->nullable()->after('district');
            $table->string('country')->nullable()->after('division');
            $table->text('bio')->nullable()->after('country');
            $table->string('profile_photo_path')->nullable()->after('bio');
            $table->string('nid_number')->nullable()->after('profile_photo_path');
            $table->string('nid_front_image_path')->nullable()->after('nid_number');
            $table->string('nid_back_image_path')->nullable()->after('nid_front_image_path');
            $table->string('passport_number')->nullable()->after('nid_back_image_path');
            $table->string('passport_image_path')->nullable()->after('passport_number');
            $table->string('ownership_document_type')->nullable()->after('passport_image_path');
            $table->string('ownership_proof_path')->nullable()->after('ownership_document_type');
            $table->text('home_elevation_image_paths')->nullable()->after('ownership_proof_path');
            $table->string('emergency_contact_name')->nullable()->after('home_elevation_image_paths');
            $table->string('emergency_contact_phone')->nullable()->after('emergency_contact_name');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'account_type',
                'phone',
                'alternative_phone',
                'date_of_birth',
                'gender',
                'profession',
                'home_name',
                'home_type',
                'present_address',
                'permanent_address',
                'area_name',
                'post_office',
                'postal_code',
                'upazila',
                'district',
                'division',
                'country',
                'bio',
                'profile_photo_path',
                'nid_number',
                'nid_front_image_path',
                'nid_back_image_path',
                'passport_number',
                'passport_image_path',
                'ownership_document_type',
                'ownership_proof_path',
                'home_elevation_image_paths',
                'emergency_contact_name',
                'emergency_contact_phone',
            ]);
        });
    }
};
