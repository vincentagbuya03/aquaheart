<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->unsignedInteger('loyalty_points')->default(0)->after('address');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->unsignedInteger('stock_quantity')->default(0)->after('price');
            $table->unsignedInteger('reorder_level')->default(10)->after('stock_quantity');
            $table->boolean('is_active')->default(true)->after('description');
        });

        Schema::table('refills', function (Blueprint $table) {
            $table->string('receipt_number')->nullable()->after('product_id');
            $table->unsignedInteger('quantity')->default(1)->after('receipt_number');
            $table->decimal('unit_price', 10, 2)->default(0)->after('quantity');
            $table->string('payment_status')->default('paid')->after('amount');
            $table->string('service_type')->default('walk_in')->after('payment_status');
            $table->text('notes')->nullable()->after('service_type');
        });
    }

    public function down(): void
    {
        Schema::table('refills', function (Blueprint $table) {
            $table->dropColumn([
                'receipt_number',
                'quantity',
                'unit_price',
                'payment_status',
                'service_type',
                'notes',
            ]);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'stock_quantity',
                'reorder_level',
                'is_active',
            ]);
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('loyalty_points');
        });
    }
};
