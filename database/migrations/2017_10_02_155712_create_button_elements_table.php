<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateButtonElementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('button_elements', function (Blueprint $table) {
            $table->increments('id');
            $table->text('html')->nullable();
            $table->decimal('posX')->default(0);
            $table->decimal('posY')->default(0);
            $table->integer('width')->default(100);
            $table->integer('height')->default(50);
            $table->string('background')->default('red');
            $table->integer('borderRadius')->default(5);
            $table->integer('borderWidth')->default(1);
            $table->string('borderColor')->default('black');
            $table->string('borderType')->default('solid');


            // TODO: this is repeated by image element
            $table->string('clickAction')->nullable();
            $table->string('clickActionArg')->nullable();

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
        Schema::dropIfExists('button_elements');
    }
}
