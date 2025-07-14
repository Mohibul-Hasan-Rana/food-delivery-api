<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'restaurant_id' => $this->restaurant_id,
            'customer_id' => $this->customer_id,
            'items' => $this->items,
            'delivery_address' => $this->delivery_address,
            'delivery_lat' => $this->delivery_lat,
            'delivery_lng' => $this->delivery_lng,
            'total_amount' => $this->total_amount,
            'status' => $this->status,
            'created_at' => $this->created_at
        ];
    }
}
