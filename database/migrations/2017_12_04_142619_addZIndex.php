<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddZIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        // 7 Different Element Tables
        Schema::table('button_elements', function(Blueprint $table) {
            $table->integer('zIndex')->default(0);
        });

        Schema::table('custom_html_elements', function(Blueprint $table) {
            $table->integer('zIndex')->default(0);
        });

        Schema::table('form_elements', function(Blueprint $table) {
            $table->integer('zIndex')->default(0);
        });

        Schema::table('hotspot_elements', function(Blueprint $table) {
            $table->integer('zIndex')->default(0);
        });

        Schema::table('image_elements', function(Blueprint $table) {
            $table->integer('zIndex')->default(0);
        });

        Schema::table('text_elements', function(Blueprint $table) {
            $table->integer('zIndex')->default(0);
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
