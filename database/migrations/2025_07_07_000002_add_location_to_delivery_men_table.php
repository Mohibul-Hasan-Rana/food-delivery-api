<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {       

        Schema::create('delivery_mens', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->enum('status', ['available', 'busy', 'offline'])->default('offline');
            $table->timestamp('location_updated_at')->nullable();
            $table->boolean('is_available')->default(true);
            $table->timestamps();

            $table->index(['latitude', 'longitude']);
            $table->index(['status']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('delivery_men');
    }
};
