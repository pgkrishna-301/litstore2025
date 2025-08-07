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
            // Add foreign key constraint for customer_id if column exists
            if (Schema::hasColumn('cartitem', 'customer_id')) {
                // First ensure the column is the correct type
                $table->unsignedBigInteger('customer_id')->nullable()->change();
                $table->foreign('customer_id')->references('id')->on('architects')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cartitem', function (Blueprint $table) {
            // Drop foreign key constraint if it exists
            if (Schema::hasColumn('cartitem', 'customer_id')) {
                $table->dropForeign(['customer_id']);
                // Change back to string if needed
                $table->string('customer_id')->nullable()->change();
            }
        });
    }
};
