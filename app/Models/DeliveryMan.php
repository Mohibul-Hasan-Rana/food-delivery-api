<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class DeliveryMan extends Model
{
    use Notifiable;

    protected $fillable = [
        'latitude',
        'longitude',
        'is_available',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
