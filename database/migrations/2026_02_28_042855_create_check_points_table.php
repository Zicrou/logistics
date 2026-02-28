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
        Schema::create('check_points', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid(column: 'shipment_id')->references('id')->on('shipments')->onDelete('cascade');
            $table->string('type', 30);
            $table->string('location', 100);
            $table->string('status', 30);
            $table->timestamp('passed_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('check_points');
    }
};
