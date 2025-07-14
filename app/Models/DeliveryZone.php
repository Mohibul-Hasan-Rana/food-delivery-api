<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryZone extends Model
{
    protected $fillable = [
        'restaurant_id',
        'name',
        'type',
        'coordinates',
        'center_lat',
        'center_lng',
        'radius',
        'is_active'
    ];

    protected $casts = [
        'coordinates' => 'array',
        'center_lat' => 'decimal:8',
        'center_lng' => 'decimal:8',
        'radius' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
