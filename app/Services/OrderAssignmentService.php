<?php
namespace App\Services;

use App\Models\Order;
use App\Models\DeliveryMan;
use App\Models\DeliveryZone;
use App\Services\DeliveryZoneService;

class OrderAssignmentService
{
    protected $zoneService;

    public function __construct(DeliveryZoneService $zoneService)
    {
        $this->zoneService = $zoneService;
    }

    /**
     * Validate if the order address is within the delivery zone
     */
    public function validateAddressInZone(DeliveryZone $zone, float $lat, float $lng): bool
    {
        return $this->zoneService->contains($zone, $lat, $lng);
    }

    /**
     * Find the nearest available delivery man within a given radius (km)
     */
    public function findNearestDeliveryMan(float $lat, float $lng, float $radiusKm = 5): ?DeliveryMan
    {
        $candidates = DeliveryMan::where('is_available', true)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();
        $nearest = null;
        $minDistance = $radiusKm;
        foreach ($candidates as $man) {
            $distance = $this->zoneService->haversine($lat, $lng, $man->latitude, $man->longitude);
            if ($distance <= $minDistance) {
                $minDistance = $distance;
                $nearest = $man;
            }
        }
        return $nearest;
    }

    /**
     * Assign delivery man to order and notify
     */
    public function assignDeliveryMan(Order $order, DeliveryMan $man)
    {
        $order->delivery_man_id = $man->id;
        $order->save();
        // Notify delivery man
        $man->notify(new \App\Notifications\OrderAssigned($order));
    }
}
