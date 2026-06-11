<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 20)->nullable()->unique()->after('email');
            $table->timestamp('phone_verified_at')->nullable()->after('phone');
            $table->string('oneid_pin', 14)->nullable()->unique()->after('phone_verified_at');
            $table->string('first_name')->nullable()->after('name');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('middle_name')->nullable()->after('last_name');
            $table->string('passport')->nullable()->after('middle_name');
            $table->date('birth_date')->nullable()->after('passport');
            $table->string('auth_provider')->default('phone')->after('birth_date');
            $table->boolean('is_active')->default(true)->after('auth_provider');
        });

    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'phone_verified_at',
                'oneid_pin',
                'first_name',
                'last_name',
                'middle_name',
                'passport',
                'birth_date',
                'auth_provider',
                'is_active',
            ]);
        });
    }
};
