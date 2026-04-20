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
        Schema::table('refills', function (Blueprint $table) {
            $table->decimal('paid_amount', 10, 2)->nullable()->after('unit_price')->comment('Amount already paid for partial payments');
            $table->decimal('partial_amount', 10, 2)->nullable()->after('paid_amount')->comment('Amount still owed for partial payments');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('refills', function (Blueprint $table) {
            $table->dropColumn(['paid_amount', 'partial_amount']);
        });
    }
};
