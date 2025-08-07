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
        // Drop tables in reverse order to respect foreign key constraints
        Schema::dropIfExists('order_details');
        Schema::dropIfExists('cartitem');
        Schema::dropIfExists('add_product');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration only drops tables, so down() is empty
        // The actual table creation will be handled by the other migrations
    }
};
