<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('delivery_zone_id')->nullable()->after('id');
            $table->unsignedBigInteger('delivery_man_id')->nullable()->after('delivery_zone_id');
            $table->foreign('delivery_zone_id')->references('id')->on('delivery_zones')->onDelete('set null');
            $table->foreign('delivery_man_id')->references('id')->on('delivery_men')->onDelete('set null');
        });
    }
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['delivery_zone_id']);
            $table->dropForeign(['delivery_man_id']);
            $table->dropColumn(['delivery_zone_id', 'delivery_man_id']);
        });
    }
};
