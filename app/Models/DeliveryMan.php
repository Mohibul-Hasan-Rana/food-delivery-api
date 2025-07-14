<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class DeliveryMan extends Model
{

    protected $table = 'delivery_mens';
    
    protected $fillable = [
        'name',
        'email',
        'phone',
        'latitude',
        'longitude',
        'location_updated_at',
        'is_available'
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'location_updated_at' => 'datetime',
        'is_available' => 'boolean'
    ];

    public function assignments(): HasMany
    {
        return $this->hasMany(DeliveryAssignment::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }
}
