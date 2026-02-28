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
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid(column: 'shipment_id')->references('id')->on('shipments')->onDelete('cascade');
            $table->integer('amount')->nullable(false);
            $table->string('currency', 10)->nullable(false);
            $table->string('method', 30)->nullable(false);
            $table->string('status', 20)->nullable(false);
            $table->timestamp('paid_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
