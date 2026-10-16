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
    Schema::create('bucket_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('pending_bucket_id')->constrained()->cascadeOnDelete();
        $table->morphs('payable'); // Crea payable_type (string) y payable_id (bigint unsigned) con índice
        $table->decimal('item_amount', 10, 2);
        $table->enum('status', ['in_cart', 'standby'])->default('in_cart');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bucket_items');
    }
};
