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
        // Drop foreign key constraints first
        Schema::table('cartitem', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });

        // Drop existing add_product table if it exists
        Schema::dropIfExists('add_product');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is for dropping table, so down() doesn't need to do anything
        // The fresh table creation migration will handle the reverse
    }
};
