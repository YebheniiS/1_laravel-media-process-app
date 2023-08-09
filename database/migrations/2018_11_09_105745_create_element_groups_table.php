<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElementGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('element_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->float('timeIn')->default(0);
            $table->float('timeOut')->default(60);
            $table->unsignedInteger('node_id');
            $table->foreign('node_id')
                    ->references('id')
                    ->on('nodes')
                    ->onDelete('cascade');
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
        Schema::dropIfExists('element_groups');
    }
}
