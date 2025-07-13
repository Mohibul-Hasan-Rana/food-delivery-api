<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Order;
use App\Models\DeliveryZone;
use App\Models\DeliveryMan;
use App\Models\Restaurant;

class OrderPlacementTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_is_rejected_if_address_outside_zone()
    {
        $restaurant = Restaurant::factory()->create();
        DeliveryZone::create([
            'restaurant_id' => $restaurant->id,
            'type' => 'radius',
            'coordinates' => ['lat' => 10, 'lng' => 10],
            'radius_km' => 1,
        ]);
        $response = $this->post('/orders', [
            'restaurant_id' => $restaurant->id,
            'delivery_latitude' => 20,
            'delivery_longitude' => 20,
        ]);
        $response->assertStatus(422)->assertJson(['error' => 'Address is outside delivery zone']);
    }

    public function test_order_is_rejected_if_no_delivery_man_available()
    {
        $restaurant = Restaurant::factory()->create();
        DeliveryZone::create([
            'restaurant_id' => $restaurant->id,
            'type' => 'radius',
            'coordinates' => ['lat' => 10, 'lng' => 10],
            'radius_km' => 5,
        ]);
        $response = $this->post('/orders', [
            'restaurant_id' => $restaurant->id,
            'delivery_latitude' => 10,
            'delivery_longitude' => 10,
        ]);
        $response->assertStatus(422)->assertJson(['error' => 'No delivery man available nearby']);
    }

    public function test_order_is_placed_and_assigned_to_nearest_delivery_man()
    {
        $restaurant = Restaurant::factory()->create();
        $zone = DeliveryZone::create([
            'restaurant_id' => $restaurant->id,
            'type' => 'radius',
            'coordinates' => ['lat' => 10, 'lng' => 10],
            'radius_km' => 5,
        ]);
        $man = DeliveryMan::create([
            'latitude' => 10.01,
            'longitude' => 10.01,
            'is_available' => true,
        ]);
        $response = $this->post('/orders', [
            'restaurant_id' => $restaurant->id,
            'delivery_latitude' => 10.01,
            'delivery_longitude' => 10.01,
        ]);
        $response->assertStatus(201)->assertJsonStructure(['order' => ['id', 'delivery_zone_id', 'delivery_man_id']]);
        $this->assertDatabaseHas('orders', [
            'delivery_zone_id' => $zone->id,
            'delivery_man_id' => $man->id,
        ]);
    }
}
