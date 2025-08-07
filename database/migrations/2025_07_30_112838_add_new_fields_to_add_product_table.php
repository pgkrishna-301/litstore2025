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
            // Add new array fields
            if (!Schema::hasColumn('add_product', 'body_color')) {
                $table->json('body_color')->nullable()->after('ip_rating');
            }
            if (!Schema::hasColumn('add_product', 'color_temp')) {
                $table->json('color_temp')->nullable()->after('body_color');
            }
            if (!Schema::hasColumn('add_product', 'beam_angle')) {
                $table->json('beam_angle')->nullable()->after('color_temp');
            }
            if (!Schema::hasColumn('add_product', 'cut_out')) {
                $table->json('cut_out')->nullable()->after('beam_angle');
            }
            if (!Schema::hasColumn('add_product', 'description')) {
                $table->text('description')->nullable()->after('cut_out');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('add_product', function (Blueprint $table) {
            // Drop new columns
            $columnsToDrop = [
                'body_color',
                'color_temp',
                'beam_angle',
                'cut_out',
                'description'
            ];

            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('add_product', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
