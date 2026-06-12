<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Eski "statictiks" table nomini "statistics" ga o'zgartiramiz
     */
    public function up(): void
    {
        Schema::rename('statictiks', 'statistics');
    }

    /**
     * Reverse the migrations.
     * Agar kerak bo'lsa, eski nomga qaytaramiz
     */
    public function down(): void
    {
        Schema::rename('statistics', 'statictiks');
    }
};