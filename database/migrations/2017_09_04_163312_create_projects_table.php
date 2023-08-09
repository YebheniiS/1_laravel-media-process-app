<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('is_template')->default(false);
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image_url')->default('');
            $table->integer('start_node_id')->default(0)->unsigned();
            $table->integer('base_width')->default(720);
            $table->integer('base_height')->default(405);
            $table->integer('embed_width')->default(720);
            $table->integer('embed_height')->default(405);
            $table->boolean('allow_fullscreen')->default(true);
            $table->boolean('show_controls')->default(true);
            $table->boolean('autoplay')->default(false);
            $table->dateTime('published_at')->nullable();
            $table->string('storage_path')->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('projects');
    }
}
