<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Bunncdn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create("bunny_cdn_videos", function (Blueprint $table){
            $table->increments('id');
            $table->string('bunny_cdn_video_id');
            $table->unsignedInteger('media_id')->nullable();

            $table->timestamps();
        });

        Schema::dropIfExists("qencode_tasks");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists("bunny_cdn_videos");

        Schema::create('qencode_tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token');
            $table->string('status')->default('created');
            $table->integer('media_id')->default(0);
            $table->timestamps();
        });
    }
}
