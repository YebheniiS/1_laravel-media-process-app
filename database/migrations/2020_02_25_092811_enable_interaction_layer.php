<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EnableInteractionLayer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('nodes', function (Blueprint $table) {
            $table->integer('enable_interaction_layer')->default(1);
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
        Schema::table('nodes', function (Blueprint $table) {
            $table->dropColumn('enable_interaction_layer');
        });
    }
}
