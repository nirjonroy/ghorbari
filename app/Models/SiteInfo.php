<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteInfo extends Model
{
    use HasFactory;

    protected $table = 'siteinfo';

    protected $fillable = [
        'google_location',
        'footer_google_location',
        'footer_contact_note',
        'maintenance_mode',
        'logo',
        'logo_width',
        'logo_height',
        'favicon',
        'favicon_width',
        'favicon_height',
        'image_output_format',
        'contact_email',
        'enable_user_register',
        'phone_number_required',
        'enable_subscription_notify',
        'enable_save_contact_message',
        'text_direction',
        'timezone',
        'sidebar_lg_header',
        'sidebar_sm_header',
        'topbar_phone',
        'topbar_email',
        'currency_name',
        'currency_icon',
        'currency_rate',
        'default_phone_code',
        'frontend_url',
        'homepage_section_title',
        'slider_width',
        'slider_height',
    ];

    protected $casts = [
        'maintenance_mode' => 'boolean',
        'enable_user_register' => 'boolean',
        'phone_number_required' => 'boolean',
        'enable_subscription_notify' => 'boolean',
        'enable_save_contact_message' => 'boolean',
        'currency_rate' => 'decimal:4',
        'logo_width' => 'integer',
        'logo_height' => 'integer',
        'favicon_width' => 'integer',
        'favicon_height' => 'integer',
        'slider_width' => 'integer',
        'slider_height' => 'integer',
    ];
}
