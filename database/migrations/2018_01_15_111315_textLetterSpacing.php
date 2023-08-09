<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TextLetterSpacing extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('button_elements', function(Blueprint $table) {
            $table->integer('letterSpacing')->default(0);
        });
        Schema::table('text_elements', function(Blueprint $table) {
            $table->integer('letterSpacing')->default(0);
        });
        Schema::table('form_elements', function(Blueprint $table) {
            $table->integer('button_letterSpacing')->default(0);
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
