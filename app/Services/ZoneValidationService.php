<?php

namespace App\services;

use App\Models\DeliveryZone;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ZoneValidationService
{
   public function isPointInZone(float $lat, float $lng, DeliveryZone $zone): bool
    {
        return $zone->type === 'polygon' 
            ? $this->isPointInPolygon($lat, $lng, $zone->coordinates)
            : $this->isPointInRadius($lat, $lng, $zone->center_lat, $zone->center_lng, $zone->radius);
    }

    private function isPointInPolygon(float $lat, float $lng, array $polygon): bool
    {
        $vertices = count($polygon);
        $inside = false;

        for ($i = 0, $j = $vertices - 1; $i < $vertices; $j = $i++) {
            $xi = $polygon[$i]['lat'];
            $yi = $polygon[$i]['lng'];
            $xj = $polygon[$j]['lat'];
            $yj = $polygon[$j]['lng'];

            if ((($yi > $lng) != ($yj > $lng)) && ($lat < ($xj - $xi) * ($lng - $yi) / ($yj - $yi) + $xi)) {
                $inside = !$inside;
            }
        }

        return $inside;
    }

    private function isPointInRadius(float $lat1, float $lng1, float $lat2, float $lng2, float $radius): bool
    {
        $distance = $this->calculateDistance($lat1, $lng1, $lat2, $lng2);
        return $distance <= $radius;
    }

    private function calculateDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371; // km
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) * sin($dLat / 2) + 
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * 
             sin($dLng / 2) * sin($dLng / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
