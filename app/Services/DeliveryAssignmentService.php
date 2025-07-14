<?php

namespace App\services;

use App\Models\DeliveryAssignment;
use App\Models\DeliveryMan;
use App\Models\Order;
use App\Events\DeliveryAssignmentCreated;
use App\Services\ZoneValidationService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DeliveryAssignmentService
{
    private ZoneValidationService $zoneValidator;

    public function __construct(ZoneValidationService $zoneValidator)
    {
        $this->zoneValidator = $zoneValidator;
    }

    public function findNearestDeliveryPerson(float $lat, float $lng, float $radius = 5): ?DeliveryMan
    {
        return DeliveryMan::available()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
            ->filter(function ($deliveryMan) use ($lat, $lng, $radius) {
                $distance = $this->zoneValidator->calculateDistance(
                    $lat, $lng, 
                    $deliveryMan->latitude, 
                    $deliveryMan->longitude
                );
                return $distance <= $radius;
            })
            ->sortBy(function ($deliveryMan) use ($lat, $lng) {
                return $this->zoneValidator->calculateDistance(
                    $lat, $lng, 
                    $deliveryMan->latitude, 
                    $deliveryMan->longitude
                );
            })
            ->first();
    }

    public function assignDelivery(Order $order, DeliveryMan $deliveryMan): DeliveryAssignment
    {
        $assignment = DeliveryAssignment::create([
            'order_id' => $order->id,
            'delivery_man_id' => $deliveryMan->id,
            'assigned_at' => now(),
        ]);       

        // Send notification to delivery man
        $deliveryMan->notify(new \App\Notifications\OrderAssigned($order));

        return $assignment;
    }

    public function respondToAssignment(DeliveryAssignment $assignment, string $status): bool
    {
        if (!in_array($status, ['accepted', 'rejected'])) {
            return false;
        }

        $assignment->update([
            'status' => $status,
            'responded_at' => now(),
        ]);

        return true;
    }
}
