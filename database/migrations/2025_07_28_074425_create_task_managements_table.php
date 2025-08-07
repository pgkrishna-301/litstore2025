<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('task_managements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->string('assignment_to');
            $table->string('assignment_by');
            $table->string('due_date'); // Use string if not using date type
            $table->string('start_date')->nullable();
            $table->string('importance_scale')->nullable();
            $table->string('status');
            $table->string('reminder_set');
            $table->unsignedBigInteger('user_id');
            $table->timestamps(); // adds created_at and updated_at
            
            // Add foreign key constraint
            $table->foreign('user_id')->references('id')->on('user')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_managements');
    }
};
