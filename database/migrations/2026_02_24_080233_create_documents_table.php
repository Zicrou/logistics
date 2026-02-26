<?php

use App\Models\Shipment;
use App\Models\User;
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
        Schema::create('documents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type', 30)->nullable();
            $table->string('file_url');
            $table->boolean('verified')->default(false);
            $table->foreignUuid(column: 'shipment_id')->references('id')->on('shipments')->onDelete('cascade');

            // $table->foreignUuid(Shipment::class)->constrained()->cascadeOnDelete();
            $table->foreignUuid('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
