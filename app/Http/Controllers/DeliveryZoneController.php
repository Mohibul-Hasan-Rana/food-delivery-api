<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DeliveryZone;
use App\Http\Requests\DeliveryZoneRequest;
use App\Http\Resources\DeliveryZoneResource;
use Illuminate\Http\JsonResponse;

class DeliveryZoneController extends Controller
{
    public function store(DeliveryZoneRequest $request): JsonResponse
    {
        $zone = DeliveryZone::create($request->validated());
        
        return response()->json([
            'message' => 'Delivery zone created successfully',
            'data' => new DeliveryZoneResource($zone)
        ], 201);
    }

    public function update(DeliveryZoneRequest $request, DeliveryZone $zone): JsonResponse
    {
        $zone->update($request->validated());
        
        return response()->json([
            'message' => 'Delivery zone updated successfully',
            'data' => new DeliveryZoneResource($zone)
        ]);
    }

    public function destroy(DeliveryZone $zone): JsonResponse
    {
        $zone->delete();
        
        return response()->json([
            'message' => 'Delivery zone deleted successfully'
        ]);
    }
}
