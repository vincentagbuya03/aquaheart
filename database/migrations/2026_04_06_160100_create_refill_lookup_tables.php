<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_statuses', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->uuid('id')->primary();
            $table->string('code')->unique();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('service_types', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->uuid('id')->primary();
            $table->string('code')->unique();
            $table->string('name');
            $table->timestamps();
        });

        $now = now();

        DB::table('payment_statuses')->insert([
            ['id' => (string) Str::uuid(), 'code' => 'paid', 'name' => 'Paid', 'created_at' => $now, 'updated_at' => $now],
            ['id' => (string) Str::uuid(), 'code' => 'unpaid', 'name' => 'Unpaid', 'created_at' => $now, 'updated_at' => $now],
            ['id' => (string) Str::uuid(), 'code' => 'partial', 'name' => 'Partial', 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('service_types')->insert([
            ['id' => (string) Str::uuid(), 'code' => 'walk_in', 'name' => 'Walk In', 'created_at' => $now, 'updated_at' => $now],
            ['id' => (string) Str::uuid(), 'code' => 'delivery', 'name' => 'Delivery', 'created_at' => $now, 'updated_at' => $now],
            ['id' => (string) Str::uuid(), 'code' => 'pickup', 'name' => 'Pickup', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('service_types');
        Schema::dropIfExists('payment_statuses');
    }
};
