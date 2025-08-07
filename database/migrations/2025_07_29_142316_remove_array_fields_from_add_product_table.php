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
            // Remove individual array fields
            $columnsToDrop = [
                'body_color',
                'color_temp',
                'beam_angle',
                'cut_out',
                'reflector_color'
            ];

            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('add_product', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('add_product', function (Blueprint $table) {
            // Restore individual array fields
            if (!Schema::hasColumn('add_product', 'body_color')) {
                $table->json('body_color')->nullable();
            }
            if (!Schema::hasColumn('add_product', 'color_temp')) {
                $table->json('color_temp')->nullable();
            }
            if (!Schema::hasColumn('add_product', 'beam_angle')) {
                $table->json('beam_angle')->nullable();
            }
            if (!Schema::hasColumn('add_product', 'cut_out')) {
                $table->json('cut_out')->nullable();
            }
            if (!Schema::hasColumn('add_product', 'reflector_color')) {
                $table->json('reflector_color')->nullable();
            }
        });
    }
};
