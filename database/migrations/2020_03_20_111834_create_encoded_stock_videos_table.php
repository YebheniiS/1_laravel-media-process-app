<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEncodedStockVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('encoded_stock_videos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('stock_video_id')->default(0);
            $table->string('encoded_url')->nullable();
            $table->string('thumbnail_url')->nullable();
            $table->string('status')->default('created');
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
        Schema::dropIfExists('encoded_stock_videos');
    }
}
