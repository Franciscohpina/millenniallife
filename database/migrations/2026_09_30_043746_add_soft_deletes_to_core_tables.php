<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('annual_procedures', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('medical_appointments', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('annual_procedures', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('medical_appointments', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};