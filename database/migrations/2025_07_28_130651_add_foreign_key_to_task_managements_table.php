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
        Schema::table('task_managements', function (Blueprint $table) {
            // Drop any existing foreign key constraints first
            $table->dropForeign(['user_id']);
            
            // Change the user_id column to unsignedBigInteger
            $table->unsignedBigInteger('user_id')->change();
            
            // Add foreign key constraint
            $table->foreign('user_id')->references('id')->on('user')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_managements', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['user_id']);
            
            // Change back to string if needed
            $table->string('user_id')->change();
        });
    }
};
