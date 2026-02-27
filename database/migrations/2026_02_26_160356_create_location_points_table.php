<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('location_points', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid(column: 'shipment_id')->references('id')->on('shipments')->onDelete('cascade');
            $table->decimal('longitude', 15 , 8);
            $table->decimal('latitude', 15, 8);
            $table->decimal('speed', 8, 2)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('location_points');
    }
};
