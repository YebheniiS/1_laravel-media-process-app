<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeMoreStuffNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('hotspot_elements', function(Blueprint $table) {
            $table->string('action')->nullable()->change();
            $table->string('actionArg')->nullable()->change();
        });
        Schema::table('button_elements', function(Blueprint $table) {
            $table->string('action')->nullable()->change();
            $table->string('actionArg')->nullable()->change();
        });
        Schema::table('form_elements', function(Blueprint $table) {
            $table->string('action')->nullable()->change();
            $table->string('actionArg')->nullable()->change();
        });
        Schema::table('image_elements', function(Blueprint $table) {
            $table->string('action')->nullable()->change();
            $table->string('actionArg')->nullable()->change();
        });
        Schema::table('trigger_elements', function(Blueprint $table) {
            $table->string('action')->nullable()->change();
            $table->string('actionArg')->nullable()->change();
        });
        Schema::table('nodes', function(Blueprint $table) {
            $table->string('completeAction')->nullable()->change();
            $table->string('completeActionArg')->nullable()->change();
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
    }
}
