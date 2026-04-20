<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('refills', function (Blueprint $table) {
            $table->uuid('payment_status_id')->nullable()->after('unit_price');
            $table->uuid('service_type_id')->nullable()->after('payment_status_id');
        });

        DB::statement("UPDATE refills r
            LEFT JOIN payment_statuses ps ON ps.code = r.payment_status
            SET r.payment_status_id = ps.id");

        DB::statement("UPDATE refills r
            LEFT JOIN service_types st ON st.code = r.service_type
            SET r.service_type_id = st.id");

        Schema::table('refills', function (Blueprint $table) {
            $table->foreign('payment_status_id')->references('id')->on('payment_statuses')->onDelete('restrict');
            $table->foreign('service_type_id')->references('id')->on('service_types')->onDelete('restrict');

            $table->dropColumn(['amount', 'payment_status', 'service_type', 'refill_date']);
        });
    }

    public function down(): void
    {
        Schema::table('refills', function (Blueprint $table) {
            $table->string('payment_status')->default('paid')->after('unit_price');
            $table->string('service_type')->default('walk_in')->after('payment_status');
            $table->decimal('amount', 10, 2)->default(0)->after('created_at');
            $table->date('refill_date')->nullable()->after('notes');
        });

        DB::statement("UPDATE refills r
            LEFT JOIN payment_statuses ps ON ps.id = r.payment_status_id
            SET r.payment_status = COALESCE(ps.code, 'paid')");

        DB::statement("UPDATE refills r
            LEFT JOIN service_types st ON st.id = r.service_type_id
            SET r.service_type = COALESCE(st.code, 'walk_in')");

        DB::statement('UPDATE refills SET amount = quantity * unit_price');
        DB::statement('UPDATE refills SET refill_date = DATE(created_at) WHERE created_at IS NOT NULL');

        Schema::table('refills', function (Blueprint $table) {
            $table->dropForeign(['payment_status_id']);
            $table->dropForeign(['service_type_id']);
            $table->dropColumn(['payment_status_id', 'service_type_id']);
        });
    }
};
