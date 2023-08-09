<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPauseWhenShownToElementGroup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('element_groups', function (Blueprint $table) {
            //
            $table->integer('pause_when_shown')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('element_groups', function (Blueprint $table) {
            //
            $table->dropColumn('pause_when_shown');
        });
    }
}
