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
        Schema::table('order_details', function (Blueprint $table) {
            // Drop columns that are no longer needed
            $columnsToDrop = [
                'discount',
                'discount_status',
                'discount_price',
                'customer_name',
                'architect_name',
                'email',
                'phone_number',
                'shipping_address'
            ];

            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('order_details', $column)) {
                    $table->dropColumn($column);
                }
            }

            // Add new columns
            if (!Schema::hasColumn('order_details', 'total_price')) {
                $table->decimal('total_price', 10, 2)->nullable()->after('products');
            }

            // Change user_id to string if it's not already
            if (Schema::hasColumn('order_details', 'user_id')) {
                $table->string('user_id')->nullable()->change();
            }

            // Change customer_id to unsignedBigInteger for foreign key
            if (Schema::hasColumn('order_details', 'customer_id')) {
                $table->unsignedBigInteger('customer_id')->nullable()->change();
            }
        });

        // Add foreign key constraint for customer_id
        Schema::table('order_details', function (Blueprint $table) {
            $table->foreign('customer_id')->references('id')->on('architects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_details', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['customer_id']);

            // Drop new columns
            if (Schema::hasColumn('order_details', 'total_price')) {
                $table->dropColumn('total_price');
            }

            // Restore dropped columns
            if (!Schema::hasColumn('order_details', 'discount')) {
                $table->string('discount')->nullable();
            }
            if (!Schema::hasColumn('order_details', 'discount_status')) {
                $table->integer('discount_status')->nullable();
            }
            if (!Schema::hasColumn('order_details', 'discount_price')) {
                $table->string('discount_price')->nullable();
            }
            if (!Schema::hasColumn('order_details', 'customer_name')) {
                $table->string('customer_name')->nullable();
            }
            if (!Schema::hasColumn('order_details', 'architect_name')) {
                $table->string('architect_name')->nullable();
            }
            if (!Schema::hasColumn('order_details', 'email')) {
                $table->string('email')->nullable();
            }
            if (!Schema::hasColumn('order_details', 'phone_number')) {
                $table->string('phone_number')->nullable();
            }
            if (!Schema::hasColumn('order_details', 'shipping_address')) {
                $table->text('shipping_address')->nullable();
            }

            // Change customer_id back to string
            if (Schema::hasColumn('order_details', 'customer_id')) {
                $table->string('customer_id')->nullable()->change();
            }
        });
    }
};
