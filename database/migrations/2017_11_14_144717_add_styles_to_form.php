<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStylesToForm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('form_elements', function($table) {
            $table->boolean('on_one_line')->default(false);
            $table->text('button_html')->nullable();
            $table->string('button_background')->default('red');
            $table->integer('button_borderRadius')->default(5);
            $table->integer('button_borderWidth')->default(1);
            $table->string('button_borderColor')->default('black');
            $table->string('button_borderType')->default('solid');

            $table->string('input_background')->default('white');
            $table->string('input_color')->default('black');
            $table->integer('input_borderRadius')->default(5);
            $table->integer('input_borderWidth')->default(1);
            $table->string('input_borderColor')->default('black');
            $table->string('input_borderType')->default('solid');
        });
        //
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('form_elements', function($table) {
            $table->dropColumn('on_one_line');
            $table->dropColumn('button_html');
            $table->dropColumn('button_background');
            $table->dropColumn('button_borderRadius');
            $table->dropColumn('button_borderWidth');
            $table->dropColumn('button_borderColor');
            $table->dropColumn('button_borderType');

            $table->dropColumn('input_background');
            $table->dropColumn('input_color');
            $table->dropColumn('input_borderRadius');
            $table->dropColumn('input_borderWidth');
            $table->dropColumn('input_borderColor');
            $table->dropColumn('input_borderType');
        });
    }
}
