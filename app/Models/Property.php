<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_user_id',
        'agent_profile_id',
        'agency_id',
        'property_type_id',
        'property_category',
        'address_id',
        'district_id',
        'city_id',
        'area_id',
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
        'is_open_house',
        'is_published',
        'published_at',
        'expires_at',
        'seo_title',
        'seo_description',
        'meta_title',
        'meta_description',
        'meta_image',
        'author',
        'publisher',
        'copyright',
        'site_name',
        'keywords',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'area_size' => 'decimal:2',
        'land_size' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_early_access' => 'boolean',
        'is_open_house' => 'boolean',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function type()
    {
        return $this->belongsTo(PropertyType::class, 'property_type_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
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

    public function detailSlug(): string
    {
        return collect([
            $this->slug ?: Str::slug($this->title),
            optional($this->area)->slug,
            optional($this->city)->slug,
            optional($this->district)->slug,
            $this->id,
        ])->filter()->unique()->join('-');
    }

    public function detailUrl(): string
    {
        return route('frontend.property.show', ['property' => $this->detailSlug()]);
    }
}
