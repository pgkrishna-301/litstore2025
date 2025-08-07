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
        Schema::create('cartitem', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->integer('qty')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('discount', 8, 2)->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('user_id')->nullable();
            $table->string('reflector_color')->nullable();
            $table->string('location')->nullable();
            $table->timestamps();
            
            // Add foreign key constraints
            $table->foreign('product_id')->references('id')->on('add_product')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('architects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cartitem');
    }
};
