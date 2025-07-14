<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeliveryZoneRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'restaurant_id' => 'required|exists:restaurants,id',
            'name' => 'required|string|max:255',
            'type' => 'required|in:polygon,radius',
            'coordinates' => 'required_if:type,polygon|array',
            'coordinates.*.lat' => 'required_if:type,polygon|numeric',
            'coordinates.*.lng' => 'required_if:type,polygon|numeric',
            'center_lat' => 'required_if:type,radius|numeric',
            'center_lng' => 'required_if:type,radius|numeric',
            'radius' => 'required_if:type,radius|numeric|min:0.1',
            'is_active' => 'boolean'
        ];
    }
}
