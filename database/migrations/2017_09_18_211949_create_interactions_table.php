<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\HotspotElement;
use App\ImageElement;

class CreateInteractionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interactions', function (Blueprint $table) {
            $table->increments('id');

            // These reference the table which will be joined i.e. Hotspot
            // Had to use string as annoyingly, enum seems to strip the '\'s
            $table->string('element_type')->default(HotspotElement::class);
            $table->integer('element_id')->unsigned();

            $table->float('timeIn')->default(0);
            $table->float('timeOut')->default(60);

            $table->integer('node_id')->unsigned();
            $table->foreign('node_id')->references('id')->on('nodes')->onDelete('cascade');
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
        Schema::dropIfExists('interactions');
    }
}
