<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPauseWhenShownToInteraction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('interactions', function(Blueprint $table) {
            $table->boolean('pause_when_shown');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('interactions', function(Blueprint $table) {
            $table->dropColumn('pause_when_shown');
        });
    }
}
