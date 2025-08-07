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
            // Drop columns that are no longer needed
            $columnsToDrop = [
                'product_name',
                'hsn_code',
                'body_color',
                'color_temp',
                'beam_angle',
                'cut_out',
                'description'
            ];

            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('cartitem', $column)) {
                    $table->dropColumn($column);
                }
            }

            // Change product_id to unsignedBigInteger if it exists, or add it if it doesn't
            if (Schema::hasColumn('cartitem', 'product_id')) {
                $table->unsignedBigInteger('product_id')->change();
            } else {
                $table->unsignedBigInteger('product_id')->nullable()->after('user_id');
            }
        });

        // Add foreign key constraint for product_id
        Schema::table('cartitem', function (Blueprint $table) {
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
            
            // Change product_id back to int if needed
            if (Schema::hasColumn('cartitem', 'product_id')) {
                $table->integer('product_id')->change();
            }

            // Restore dropped columns
            if (!Schema::hasColumn('cartitem', 'product_name')) {
                $table->string('product_name')->nullable();
            }
            if (!Schema::hasColumn('cartitem', 'hsn_code')) {
                $table->string('hsn_code')->nullable();
            }
            if (!Schema::hasColumn('cartitem', 'body_color')) {
                $table->string('body_color')->nullable();
            }
            if (!Schema::hasColumn('cartitem', 'color_temp')) {
                $table->string('color_temp')->nullable();
            }
            if (!Schema::hasColumn('cartitem', 'beam_angle')) {
                $table->string('beam_angle')->nullable();
            }
            if (!Schema::hasColumn('cartitem', 'cut_out')) {
                $table->string('cut_out')->nullable();
            }
            if (!Schema::hasColumn('cartitem', 'description')) {
                $table->text('description')->nullable();
            }
        });
    }
};
