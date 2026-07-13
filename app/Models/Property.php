<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_user_id',
        'agent_profile_id',
        'agency_id',
        'property_type_id',
        'address_id',
        'title',
        'slug',
        'listing_type',
        'property_status',
        'price',
        'rent_period',
        'area_size',
        'land_size',
        'bedrooms',
        'bathrooms',
        'balconies',
        'floor_no',
        'total_floors',
        'parking_spaces',
        'furnishing_status',
        'description',
        'verification_status',
        'is_featured',
        'is_early_access',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'area_size' => 'decimal:2',
        'land_size' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_early_access' => 'boolean',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function type()
    {
        return $this->belongsTo(PropertyType::class, 'property_type_id');
    }

    public function agent()
    {
        return $this->belongsTo(AgentProfile::class, 'agent_profile_id');
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function media()
    {
        return $this->hasMany(PropertyMedia::class)->orderBy('sort_order');
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'property_amenities');
    }

    public function priceHistory()
    {
        return $this->hasMany(PropertyPriceHistory::class);
    }

    public function views()
    {
        return $this->hasMany(PropertyView::class);
    }
}
