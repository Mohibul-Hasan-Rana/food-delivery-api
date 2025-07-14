<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryZonesTable extends Migration {
    public function up(): void
    {
        Schema::create('delivery_zones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id');
            $table->string('name');
            $table->enum('type', ['polygon', 'radius']);
            $table->json('coordinates')->nullable(); // For polygon zones
            $table->decimal('center_lat', 10, 8)->nullable(); // For radius zones
            $table->decimal('center_lng', 11, 8)->nullable(); // For radius zones
            $table->decimal('radius', 8, 2)->nullable(); // For radius zones (in km)
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('delivery_zones');
    }
};
