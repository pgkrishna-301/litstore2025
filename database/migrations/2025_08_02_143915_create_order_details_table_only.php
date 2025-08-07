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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->nullable();
            $table->string('user_id')->nullable();
            $table->json('products')->nullable();
            $table->decimal('total_price', 10, 2)->nullable();
            $table->integer('status')->nullable();
            $table->string('cash')->nullable();
            $table->string('credit')->nullable();
            $table->string('received')->nullable();
            $table->string('pending')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->date('delivery_date')->nullable();
            $table->timestamps();
            
            // Add foreign key constraint for customer_id
            $table->foreign('customer_id')->references('id')->on('architects')->onDelete('cascade');
            
            // Add indexes for better performance
            $table->index('order_id');
            $table->index('user_id');
            $table->index('customer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
