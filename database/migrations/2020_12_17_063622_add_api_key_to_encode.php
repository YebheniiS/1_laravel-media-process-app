<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApiKeyToEncode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('qencode_tasks', function (Blueprint $table) {
            $table->string('api_key')->nullable();
            $table->string('profile_key')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('qencode_tasks', function (Blueprint $table) {
            $table->dropColumn('api_key');
            $table->dropColumn('profile_key');
        });
    }
}
