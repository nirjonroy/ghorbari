<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AgentProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'agency_id',
        'designation',
        'license_no',
        'bio',
        'experience_years',
        'service_area',
        'rating',
        'status',
    ];

    protected $casts = [
        'experience_years' => 'integer',
        'rating' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class);
    }

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }
}
