<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('architects', function (Blueprint $table) {
            // First, add a new column with the correct type
            $table->unsignedBigInteger('select_architect_new')->nullable();
        });

        // Copy data safely (convert string to integer where possible)
        DB::statement('UPDATE architects SET select_architect_new = NULL WHERE select_architect IS NULL OR select_architect = ""');
        DB::statement('UPDATE architects SET select_architect_new = CAST(select_architect AS UNSIGNED) WHERE select_architect IS NOT NULL AND select_architect != "" AND select_architect REGEXP "^[0-9]+$"');

        Schema::table('architects', function (Blueprint $table) {
            // Drop the old column
            $table->dropColumn('select_architect');
        });

        Schema::table('architects', function (Blueprint $table) {
            // Rename the new column to the original name
            $table->renameColumn('select_architect_new', 'select_architect');
            
            // Add foreign key constraint
            $table->foreign('select_architect')->references('id')->on('add_profession')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('architects', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['select_architect']);
            
            // Change back to string
            $table->string('select_architect')->nullable()->change();
        });
    }
};
