<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('delivery_zones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('restaurant_id');
            $table->enum('type', ['polygon', 'radius']);
            $table->json('coordinates')->nullable(); // For polygon: array of lat/lng, for radius: center point
            $table->float('radius_km')->nullable(); // Only for radius type
            $table->timestamps();
            $table->foreign('restaurant_id')->references('id')->on('restaurants')->onDelete('cascade');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('delivery_zones');
    }
};
