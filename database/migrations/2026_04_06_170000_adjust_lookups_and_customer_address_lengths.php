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
        Schema::create('payment_statuses_tmp', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->unsignedTinyInteger('id')->primary();
            $table->string('name', 50)->unique();
        });

        Schema::create('service_types_tmp', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->unsignedTinyInteger('id')->primary();
            $table->string('name', 50)->unique();
        });

        DB::table('payment_statuses_tmp')->insert([
            ['id' => 1, 'name' => 'paid'],
            ['id' => 2, 'name' => 'pending'],
            ['id' => 3, 'name' => 'unpaid'],
        ]);

        DB::table('service_types_tmp')->insert([
            ['id' => 1, 'name' => 'walk_in'],
            ['id' => 2, 'name' => 'delivery'],
            ['id' => 3, 'name' => 'pickup'],
        ]);

        Schema::table('refills', function (Blueprint $table) {
            $table->unsignedTinyInteger('payment_status_id_tmp')->nullable()->after('unit_price');
            $table->unsignedTinyInteger('service_type_id_tmp')->nullable()->after('payment_status_id_tmp');
        });

        DB::statement("UPDATE refills r
            LEFT JOIN payment_statuses ps ON ps.id = r.payment_status_id
            SET r.payment_status_id_tmp = CASE
                WHEN LOWER(COALESCE(ps.code, ps.name)) = 'paid' THEN 1
                WHEN LOWER(COALESCE(ps.code, ps.name)) IN ('partial', 'pending') THEN 2
                WHEN LOWER(COALESCE(ps.code, ps.name)) = 'unpaid' THEN 3
                ELSE 1
            END");

        DB::statement("UPDATE refills r
            LEFT JOIN service_types st ON st.id = r.service_type_id
            SET r.service_type_id_tmp = CASE
                WHEN LOWER(COALESCE(st.code, st.name)) = 'walk_in' THEN 1
                WHEN LOWER(COALESCE(st.code, st.name)) = 'delivery' THEN 2
                WHEN LOWER(COALESCE(st.code, st.name)) = 'pickup' THEN 3
                ELSE 1
            END");

        DB::statement('UPDATE refills SET payment_status_id_tmp = 1 WHERE payment_status_id_tmp IS NULL');
        DB::statement('UPDATE refills SET service_type_id_tmp = 1 WHERE service_type_id_tmp IS NULL');

        Schema::table('refills', function (Blueprint $table) {
            $table->dropForeign(['payment_status_id']);
            $table->dropForeign(['service_type_id']);
        });

        Schema::table('refills', function (Blueprint $table) {
            $table->dropColumn(['payment_status_id', 'service_type_id']);
        });

        DB::statement('ALTER TABLE refills CHANGE payment_status_id_tmp payment_status_id TINYINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE refills CHANGE service_type_id_tmp service_type_id TINYINT UNSIGNED NOT NULL');

        Schema::table('refills', function (Blueprint $table) {
            $table->foreign('payment_status_id')->references('id')->on('payment_statuses_tmp')->onDelete('restrict');
            $table->foreign('service_type_id')->references('id')->on('service_types_tmp')->onDelete('restrict');
        });

        Schema::dropIfExists('payment_statuses');
        Schema::dropIfExists('service_types');

        DB::statement('RENAME TABLE payment_statuses_tmp TO payment_statuses, service_types_tmp TO service_types');

        DB::statement("ALTER TABLE customers
            MODIFY city VARCHAR(100) COLLATE utf8mb4_unicode_ci NULL,
            MODIFY province VARCHAR(100) COLLATE utf8mb4_unicode_ci NULL,
            MODIFY zip_code VARCHAR(10) COLLATE utf8mb4_unicode_ci NULL");
    }

    public function down(): void
    {
        Schema::create('payment_statuses_uuid', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->uuid('id')->primary();
            $table->string('code')->unique();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('service_types_uuid', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->uuid('id')->primary();
            $table->string('code')->unique();
            $table->string('name');
            $table->timestamps();
        });

        $now = now();

        $paymentMap = [
            'paid' => (string) Str::uuid(),
            'partial' => (string) Str::uuid(),
            'unpaid' => (string) Str::uuid(),
        ];

        $serviceMap = [
            'walk_in' => (string) Str::uuid(),
            'delivery' => (string) Str::uuid(),
            'pickup' => (string) Str::uuid(),
        ];

        DB::table('payment_statuses_uuid')->insert([
            ['id' => $paymentMap['paid'], 'code' => 'paid', 'name' => 'Paid', 'created_at' => $now, 'updated_at' => $now],
            ['id' => $paymentMap['partial'], 'code' => 'partial', 'name' => 'Partial', 'created_at' => $now, 'updated_at' => $now],
            ['id' => $paymentMap['unpaid'], 'code' => 'unpaid', 'name' => 'Unpaid', 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('service_types_uuid')->insert([
            ['id' => $serviceMap['walk_in'], 'code' => 'walk_in', 'name' => 'Walk In', 'created_at' => $now, 'updated_at' => $now],
            ['id' => $serviceMap['delivery'], 'code' => 'delivery', 'name' => 'Delivery', 'created_at' => $now, 'updated_at' => $now],
            ['id' => $serviceMap['pickup'], 'code' => 'pickup', 'name' => 'Pickup', 'created_at' => $now, 'updated_at' => $now],
        ]);

        Schema::table('refills', function (Blueprint $table) {
            $table->uuid('payment_status_id_tmp')->nullable()->after('unit_price');
            $table->uuid('service_type_id_tmp')->nullable()->after('payment_status_id_tmp');
        });

        DB::statement("UPDATE refills
            SET payment_status_id_tmp = CASE payment_status_id
                WHEN 1 THEN '{$paymentMap['paid']}'
                WHEN 2 THEN '{$paymentMap['partial']}'
                WHEN 3 THEN '{$paymentMap['unpaid']}'
                ELSE '{$paymentMap['paid']}'
            END");

        DB::statement("UPDATE refills
            SET service_type_id_tmp = CASE service_type_id
                WHEN 1 THEN '{$serviceMap['walk_in']}'
                WHEN 2 THEN '{$serviceMap['delivery']}'
                WHEN 3 THEN '{$serviceMap['pickup']}'
                ELSE '{$serviceMap['walk_in']}'
            END");

        Schema::table('refills', function (Blueprint $table) {
            $table->dropForeign(['payment_status_id']);
            $table->dropForeign(['service_type_id']);
            $table->dropColumn(['payment_status_id', 'service_type_id']);
        });

        DB::statement('ALTER TABLE refills CHANGE payment_status_id_tmp payment_status_id CHAR(36) COLLATE utf8mb4_unicode_ci NULL');
        DB::statement('ALTER TABLE refills CHANGE service_type_id_tmp service_type_id CHAR(36) COLLATE utf8mb4_unicode_ci NULL');

        Schema::table('refills', function (Blueprint $table) {
            $table->foreign('payment_status_id')->references('id')->on('payment_statuses_uuid')->onDelete('restrict');
            $table->foreign('service_type_id')->references('id')->on('service_types_uuid')->onDelete('restrict');
        });

        Schema::dropIfExists('payment_statuses');
        Schema::dropIfExists('service_types');

        DB::statement('RENAME TABLE payment_statuses_uuid TO payment_statuses, service_types_uuid TO service_types');

        DB::statement("ALTER TABLE customers
            MODIFY city VARCHAR(255) COLLATE utf8mb4_unicode_ci NULL,
            MODIFY province VARCHAR(255) COLLATE utf8mb4_unicode_ci NULL,
            MODIFY zip_code VARCHAR(20) COLLATE utf8mb4_unicode_ci NULL");
    }
};
