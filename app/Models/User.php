<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'account_type',
        'email',
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
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_of_birth' => 'date',
        'home_elevation_image_paths' => 'array',
    ];
}
