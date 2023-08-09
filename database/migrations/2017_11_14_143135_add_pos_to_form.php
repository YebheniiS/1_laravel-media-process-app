<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPosToForm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('form_elements', function($table) {
            $table->decimal('posX')->default(0);
            $table->decimal('posY')->default(0);
            $table->integer('width')->default(100);
            $table->integer('height')->default(50);
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
            $table->dropColumn('posX');
            $table->dropColumn('posY');
            $table->dropColumn('width');
            $table->dropColumn('height');
        });
    }
}
