<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyPriceHistory extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'property_price_history';

    protected $fillable = [
        'property_id',
        'old_price',
        'new_price',
        'changed_by',
        'changed_at',
    ];

    protected $casts = [
        'old_price' => 'decimal:2',
        'new_price' => 'decimal:2',
        'changed_at' => 'datetime',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function changedBy()
    {
        return $this->belongsTo(Admin::class, 'changed_by');
    }
}
