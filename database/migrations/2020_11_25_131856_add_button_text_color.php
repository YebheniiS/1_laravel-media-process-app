<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddButtonTextColor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('form_elements', function (Blueprint $table) {
            //
            $table->string('button_text_color')->default("#fff");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('form_elements', function (Blueprint $table) {
            //
            $table->dropColumn('button_text_color');
        });
    }
}
