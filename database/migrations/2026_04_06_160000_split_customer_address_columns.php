<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('street')->nullable()->after('name');
            $table->string('city')->nullable()->after('street');
            $table->string('province')->nullable()->after('city');
            $table->string('zip_code', 20)->nullable()->after('province');
        });

        // Preserve existing unstructured addresses in street for manual cleanup.
        DB::table('customers')
            ->whereNotNull('address')
            ->update(['street' => DB::raw('address')]);

        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('address');
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('address')->nullable()->after('phone');
        });

        DB::table('customers')->update([
            'address' => DB::raw("COALESCE(street, '')"),
        ]);

        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['street', 'city', 'province', 'zip_code']);
        });
    }
};
