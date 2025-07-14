<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'restaurant_id',
        'customer_id',
        'items',
        'delivery_address',
        'delivery_lat',
        'delivery_lng',
        'total_amount',
        'status'
    ];

    protected $casts = [
        'items' => 'array',
        'delivery_lat' => 'decimal:8',
        'delivery_lng' => 'decimal:8',
        'total_amount' => 'decimal:2'
    ];

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function deliveryAssignments(): HasMany
    {
        return $this->hasMany(DeliveryAssignment::class);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }
}
