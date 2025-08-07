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
        Schema::table('cartitem', function (Blueprint $table) {
            // Add foreign key constraint for product_id
            $table->foreign('product_id')->references('id')->on('add_product')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cartitem', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['product_id']);
        });
    }
};
