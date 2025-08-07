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
            // Drop the existing foreign key constraint
            $table->dropForeign(['customer_id']);
            
            // Add the new foreign key constraint referencing users table
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cartitem', function (Blueprint $table) {
            // Drop the users foreign key constraint
            $table->dropForeign(['customer_id']);
            
            // Restore the original architects foreign key constraint
            $table->foreign('customer_id')->references('id')->on('architects')->onDelete('cascade');
        });
    }
};
