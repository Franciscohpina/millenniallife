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
    Schema::create('adulting_tips', function (Blueprint $table) {
        $table->id();
        $table->string('title', 100);
        $table->text('description');
        $table->string('category', 50);
        $table->string('image_url')->nullable();
        $table->string('badge_text', 30)->nullable();
        $table->boolean('is_active')->default(true);
        $table->integer('order_priority')->default(0);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adulting_tips');
    }
};
