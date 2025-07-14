<?php

namespace App\services;

use App\Models\Order;
use App\Models\Restaurant;
use App\Exceptions\DeliveryZoneException;
use App\Services\ZoneValidationService;
use App\Services\DeliveryAssignmentService;

class OrderService
{
    private $zoneValidator;
    private $assignmentService;

    public function __construct(
        ZoneValidationService $zoneValidator,
        DeliveryAssignmentService $assignmentService
    ) {
        $this->zoneValidator = $zoneValidator;
        $this->assignmentService = $assignmentService;
    }

    public function validateAndPlaceOrder(array $orderData): Order
    {
        $restaurant = Restaurant::findOrFail($orderData['restaurant_id']);
        
        if (!$this->isDeliveryAddressValid($restaurant, $orderData['delivery_lat'], $orderData['delivery_lng'])) {
            throw new DeliveryZoneException('Delivery address is outside the restaurant\'s delivery zone.');
        }

        $order = Order::create($orderData);
        
        $this->assignNearestDeliveryPerson($order);
        
        return $order;
    }

    private function isDeliveryAddressValid(Restaurant $restaurant, float $lat, float $lng): bool
    {
        $deliveryZones = $restaurant->deliveryZones()->active()->get();
        
        foreach ($deliveryZones as $zone) {
            if ($this->zoneValidator->isPointInZone($lat, $lng, $zone)) {
                return true;
            }
        }
        
        return false;
    }

    private function assignNearestDeliveryPerson(Order $order): void
    {
        $deliveryMan = $this->assignmentService->findNearestDeliveryPerson(
            $order->delivery_lat,
            $order->delivery_lng
        );

        if ($deliveryMan) {
            $this->assignmentService->assignDelivery($order, $deliveryMan);
        }
    }
}
