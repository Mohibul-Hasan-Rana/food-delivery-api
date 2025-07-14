<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Restaurant;
use App\Models\DeliveryZone;

class RestaurantSeeder extends Seeder
{
    public function run(): void
    {
        $restaurants = [
            [
                'name' => 'Dhaka Biriyani House',
                'email' => 'contact@dhakabiriyani.com',
                'phone' => '+8801711111111',
                'address' => 'Dhanmondi 27, Dhaka 1209',
                'latitude' => 23.7461,
                'longitude' => 90.3742,
                'cuisine_type' => 'Bengali/Indian',
                'is_active' => true
            ],
            [
                'name' => 'Gulshan Kabab Corner',
                'email' => 'info@gulshankabab.com',
                'phone' => '+8801722222222',
                'address' => 'Gulshan 1, Dhaka 1212',
                'latitude' => 23.7811,
                'longitude' => 90.4156,
                'cuisine_type' => 'BBQ/Grill',
                'is_active' => true
            ],
            [
                'name' => 'Uttara Pizza Palace',
                'email' => 'orders@uttarapizza.com',
                'phone' => '+8801733333333',
                'address' => 'Uttara Sector 7, Dhaka 1230',
                'latitude' => 23.8759,
                'longitude' => 90.3795,
                'cuisine_type' => 'Italian',
                'is_active' => true
            ],
            [
                'name' => 'Old Dhaka Kacchi',
                'email' => 'hello@olddhakabiriyani.com',
                'phone' => '+8801744444444',
                'address' => 'Lalbagh, Old Dhaka 1211',
                'latitude' => 23.7193,
                'longitude' => 90.3914,
                'cuisine_type' => 'Traditional Bengali',
                'is_active' => true
            ],
            [
                'name' => 'Banani Burger Bar',
                'email' => 'contact@bananiburger.com',
                'phone' => '+8801755555555',
                'address' => 'Banani 11, Dhaka 1213',
                'latitude' => 23.7937,
                'longitude' => 90.4066,
                'cuisine_type' => 'American/Fast Food',
                'is_active' => true
            ],
            [
                'name' => 'Mirpur Fish Fry',
                'email' => 'info@mirpurfishfry.com',
                'phone' => '+8801766666666',
                'address' => 'Mirpur 10, Dhaka 1216',
                'latitude' => 23.8067,
                'longitude' => 90.3685,
                'cuisine_type' => 'Seafood',
                'is_active' => true
            ],
            [
                'name' => 'Wari Chinese House',
                'email' => 'orders@warichinese.com',
                'phone' => '+8801777777777',
                'address' => 'Wari, Dhaka 1203',
                'latitude' => 23.7298,
                'longitude' => 90.4200,
                'cuisine_type' => 'Chinese',
                'is_active' => true
            ],
            [
                'name' => 'Motijheel Cafe',
                'email' => 'hello@motijheelcafe.com',
                'phone' => '+8801788888888',
                'address' => 'Motijheel Commercial Area, Dhaka 1000',
                'latitude' => 23.7330,
                'longitude' => 90.4172,
                'cuisine_type' => 'Cafe/Continental',
                'is_active' => true
            ]
        ];

        foreach ($restaurants as $restaurantData) {
            $restaurant = Restaurant::create($restaurantData);
            
            // Create delivery zones for each restaurant
            DeliveryZone::create([
                'restaurant_id' => $restaurant->id,
                'name' => $restaurant->name . ' - Main Zone',
                'type' => 'radius',
                'center_lat' => $restaurant->latitude,
                'center_lng' => $restaurant->longitude,
                'radius' => 5.0,
                'is_active' => true
            ]);
        }
    }
};
