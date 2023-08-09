<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTextElementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('text_elements', function (Blueprint $table) {
            $table->increments('id');
            $table->float('posX')->default(0);
            $table->float('posY')->default(0);
            $table->integer('width')->default(100);
            $table->integer('height')->default(50);
            $table->string('html')->default('text');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('text_elements');
    }
}
