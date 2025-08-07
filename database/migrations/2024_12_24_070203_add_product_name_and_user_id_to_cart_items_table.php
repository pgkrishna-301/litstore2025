<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProductNameAndUserIdToCartItemsTable extends Migration
{
    public function up()
    {
        Schema::table('cartitem', function (Blueprint $table) {
            // Add product_name and user_id columns (no foreign key)
            if (!Schema::hasColumn('cartitem', 'product_name')) {
                $table->string('product_name');
            }
            if (!Schema::hasColumn('cartitem', 'user_id')) {
                $table->unsignedBigInteger('user_id');
            }
        });
    }

    public function down()
    {
        Schema::table('cartitem', function (Blueprint $table) {
            // Drop product_name and user_id columns if rolling back the migration
            if (Schema::hasColumn('cartitem', 'product_name')) {
                $table->dropColumn('product_name');
            }
            if (Schema::hasColumn('cartitem', 'user_id')) {
                $table->dropColumn('user_id');
            }
        });
    }
}
