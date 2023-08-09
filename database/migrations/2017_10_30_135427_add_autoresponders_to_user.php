<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAutorespondersToUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function(Blueprint $table) {
            $table->json('integration_aweber')->nullable();
            $table->json('integration_sendlane')->nullable();
            $table->json('integration_activecampaign')->nullable();
            $table->json('integration_getresponse')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function(Blueprint $table) {
            $table->dropColumn(['integration_aweber', 'integration_sendlane', 'integration_activecampaign', 'integration_getresponse']);
        });
    }
}
