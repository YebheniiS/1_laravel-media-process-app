<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Makestuffnullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('form_elements', function(Blueprint $table) {
            $table->integer('borderRadius')->default(0)->change();
            $table->string('backgroundColour')->default('')->nullable()->change();
        });

        Schema::table('text_elements', function(Blueprint $table) {
            $table->integer('borderRadius')->default(0)->change();
            $table->string('backgroundColour')->default('')->nullable()->change();
        });

        Schema::table('modals', function(Blueprint $table) {
            $table->string('backgroundColour')->default('')->nullable()->change();
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
