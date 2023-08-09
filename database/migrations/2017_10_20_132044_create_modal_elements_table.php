<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModalElementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modal_elements', function (Blueprint $table) {
            $table->increments('id');
            $table->string('element_type')->default(\App\ButtonElement::class);
            $table->integer('element_id')->unsigned();
            $table->integer('modal_id')->unsigned();
            $table->foreign('modal_id')->references('id')->on('modals')->onDelete('cascade');
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
        Schema::dropIfExists('modal_elements');
    }
}
