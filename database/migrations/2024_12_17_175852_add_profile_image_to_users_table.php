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
        Schema::table('user', function (Blueprint $table) {
            if (!Schema::hasColumn('user', 'profile_image')) {
                $table->string('profile_image')->nullable()->after('api_token');
            }
        });
    }

    public function down()
    {
        Schema::table('user', function (Blueprint $table) {
            if (Schema::hasColumn('user', 'profile_image')) {
                $table->dropColumn('profile_image');
            }
        });
    }
};
