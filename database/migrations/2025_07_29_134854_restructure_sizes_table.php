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
        Schema::table('sizes', function (Blueprint $table) {
            // Drop old columns
            $columnsToDrop = ['sizes', 'color_name', 'location'];
            
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('sizes', $column)) {
                    $table->dropColumn($column);
                }
            }

            // Add new columns
            if (!Schema::hasColumn('sizes', 'body_color')) {
                $table->json('body_color')->nullable();
            }
            if (!Schema::hasColumn('sizes', 'color_temp')) {
                $table->json('color_temp')->nullable();
            }
            if (!Schema::hasColumn('sizes', 'beam_angle')) {
                $table->json('beam_angle')->nullable();
            }
            if (!Schema::hasColumn('sizes', 'cut_out')) {
                $table->json('cut_out')->nullable();
            }
            if (!Schema::hasColumn('sizes', 'reflector_color')) {
                $table->json('reflector_color')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sizes', function (Blueprint $table) {
            // Drop new columns
            $columnsToDrop = ['body_color', 'color_temp', 'beam_angle', 'cut_out', 'reflector_color'];
            
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('sizes', $column)) {
                    $table->dropColumn($column);
                }
            }

            // Restore old columns
            if (!Schema::hasColumn('sizes', 'sizes')) {
                $table->json('sizes')->nullable();
            }
            if (!Schema::hasColumn('sizes', 'color_name')) {
                $table->json('color_name')->nullable();
            }
            if (!Schema::hasColumn('sizes', 'location')) {
                $table->json('location')->nullable();
            }
        });
    }
};
