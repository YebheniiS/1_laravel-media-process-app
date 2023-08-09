<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NodeDelayTimer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('nodes', function (Blueprint $table){
            $table->integer('completeActionTimer')->after('completeActionDelay')->nullable();
            $table->integer('completeActionSound')->after('completeActionDelay')->nullable();
            $table->string('completeAnimation')->after('completeActionDelay')->nullable();
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
        Schema::table('nodes',function (Blueprint $table){
            $table->dropColumn('completeActionTimer');
            $table->dropColumn('completeActionSound');
            $table->dropColumn('completeAnimation');
        });
    }
}
