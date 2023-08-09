<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddElementName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 7 Different Element Tables
        Schema::table('button_elements', function(Blueprint $table) {
            $table->string('name')->default('');
        });

        Schema::table('custom_html_elements', function(Blueprint $table) {
            $table->string('name')->default('');
        });

        Schema::table('form_elements', function(Blueprint $table) {
            $table->string('name')->default('');
        });

        Schema::table('hotspot_elements', function(Blueprint $table) {
            $table->string('name')->default('');
        });

        Schema::table('image_elements', function(Blueprint $table) {
            $table->string('name')->default('');
        });

        Schema::table('text_elements', function(Blueprint $table) {
            $table->string('name')->default('');
        });

        Schema::table('trigger_elements', function(Blueprint $table) {
            $table->string('name')->default('');
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
