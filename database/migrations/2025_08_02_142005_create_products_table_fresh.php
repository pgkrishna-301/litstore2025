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
        Schema::create('add_product', function (Blueprint $table) {
            $table->id();
            $table->string('product_name')->nullable();
            $table->string('product_category')->nullable();
            $table->decimal('mrp', 10, 2)->nullable();
            $table->string('hns_code')->nullable();
            $table->string('dimensions')->nullable();
            $table->string('driver_output')->nullable();
            $table->string('ip_rating')->nullable();
            $table->json('body_color')->nullable();
            $table->json('color_temp')->nullable();
            $table->json('beam_angle')->nullable();
            $table->json('cut_out')->nullable();
            $table->text('description')->nullable();
            $table->json('product_details')->nullable();
            $table->string('banner_image')->nullable();
            $table->json('add_image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('add_product');
    }
};
