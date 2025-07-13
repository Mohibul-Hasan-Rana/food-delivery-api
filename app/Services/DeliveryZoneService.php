<?php
namespace App\Services;

use App\Models\DeliveryZone;

class DeliveryZoneService
{
    /**
     * Check if a point is inside the delivery zone (polygon or radius)
     */
    public function contains(DeliveryZone $zone, float $lat, float $lng): bool
    {
        if ($zone->type === 'polygon') {
            return $this->pointInPolygon([$lat, $lng], $zone->coordinates);
        } elseif ($zone->type === 'radius') {
            $center = $zone->coordinates; // ['lat' => ..., 'lng' => ...]
            $distance = $this->haversine($lat, $lng, $center['lat'], $center['lng']);
            return $distance <= $zone->radius_km;
        }
        return false;
    }

    /**
     * Haversine formula to calculate distance between two lat/lng points in KM
     */
    public function haversine($lat1, $lng1, $lat2, $lng2): float
    {
        $earthRadius = 6371; // km
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat/2) * sin($dLat/2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLng/2) * sin($dLng/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        return $earthRadius * $c;
    }

    /**
     * Point in polygon algorithm (ray casting)
     * @param array $point [lat, lng]
     * @param array $polygon [[lat, lng], ...]
     */
    public function pointInPolygon(array $point, array $polygon): bool
    {
        $x = $point[1];
        $y = $point[0];
        $inside = false;
        $n = count($polygon);
        for ($i = 0, $j = $n - 1; $i < $n; $j = $i++) {
            $xi = $polygon[$i][1]; $yi = $polygon[$i][0];
            $xj = $polygon[$j][1]; $yj = $polygon[$j][0];
            $intersect = (($yi > $y) != ($yj > $y)) &&
                ($x < ($xj - $xi) * ($y - $yi) / ($yj - $yi + 1e-10) + $xi);
            if ($intersect) $inside = !$inside;
        }
        return $inside;
    }
}
