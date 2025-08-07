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
            // Drop banner_image column if it exists
            if (Schema::hasColumn('cartitem', 'banner_image')) {
                $table->dropColumn('banner_image');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cartitem', function (Blueprint $table) {
            // Restore banner_image column
            if (!Schema::hasColumn('cartitem', 'banner_image')) {
                $table->string('banner_image')->nullable();
            }
        });
    }
};
