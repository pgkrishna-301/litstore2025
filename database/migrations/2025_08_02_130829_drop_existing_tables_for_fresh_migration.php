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
        // Drop existing tables if they exist
        Schema::dropIfExists('task_managements');
        Schema::dropIfExists('order_details');
        Schema::dropIfExists('cartitem');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is for dropping tables, so down() doesn't need to do anything
        // The fresh table creation migrations will handle the reverse
    }
};
