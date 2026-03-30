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
            // Drop old columns if they exist
            if (Schema::hasColumn('refills', 'quantity')) {
                $table->dropColumn('quantity');
            }
            if (Schema::hasColumn('refills', 'refilled_at')) {
                $table->dropColumn('refilled_at');
            }
            
            // Add new columns
            $table->decimal('amount', 10, 2)->default(0);
            $table->date('refill_date')->default(now());
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('refills', function (Blueprint $table) {
            if (Schema::hasColumn('refills', 'amount')) {
                $table->dropColumn('amount');
            }
            if (Schema::hasColumn('refills', 'refill_date')) {
                $table->dropColumn('refill_date');
            }
            
            $table->integer('quantity')->default(1);
            $table->timestamp('refilled_at')->nullable();
        });
    }
};
