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
        Schema::table('add_product', function (Blueprint $table) {
            if (!Schema::hasColumn('add_product', 'product_details')) {
                $table->json('product_details')->nullable()->after('net_quantity');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('add_product', function (Blueprint $table) {
            if (Schema::hasColumn('add_product', 'product_details')) {
                $table->dropColumn('product_details');
            }
        });
    }
};
