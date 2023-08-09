<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHotspotElementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotspot_elements', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('posX')->default(0);
            $table->integer('posY')->default(0);
            $table->integer('width')->default(100);
            $table->integer('height')->default(50);

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
        Schema::dropIfExists('hotspot_elements');
    }
}
