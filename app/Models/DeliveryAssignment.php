<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryAssignment extends Model
{
    protected $fillable = [
        'order_id',
        'delivery_man_id',
        'status',
        'assigned_at',
        'responded_at'
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'responded_at' => 'datetime'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function deliveryMan(): BelongsTo
    {
        return $this->belongsTo(DeliveryMan::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
