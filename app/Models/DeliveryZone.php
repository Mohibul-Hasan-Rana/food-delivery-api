<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryZone extends Model
{
    protected $fillable = [
        'restaurant_id',
        'type',
        'coordinates',
        'radius_km',
    ];

    protected $casts = [
        'coordinates' => 'array',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
