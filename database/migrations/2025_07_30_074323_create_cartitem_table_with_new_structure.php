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
        if (!Schema::hasTable('cartitem')) {
            Schema::create('cartitem', function (Blueprint $table) {
                $table->id();
                $table->string('banner_image')->nullable();
                $table->integer('qty')->nullable();
                $table->decimal('price', 10, 2)->nullable();
                $table->decimal('discount', 8, 2)->nullable();
                $table->string('product_name')->nullable();
                $table->string('hsn_code')->nullable();
                $table->string('body_color')->nullable();
                $table->string('color_temp')->nullable();
                $table->string('beam_angle')->nullable();
                $table->string('cut_out')->nullable();
                $table->string('reflector_color')->nullable();
                $table->text('description')->nullable();
                $table->json('location')->nullable();
                $table->string('user_id')->nullable();
                $table->unsignedBigInteger('customer_id')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cartitem');
    }
};
