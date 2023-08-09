<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveNodeMediaForeignKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('nodes', function (Blueprint $table) {
            $table->dropForeign('nodes_media_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('nodes', function (Blueprint $table) {
            $table->dropForeign('nodes_media_id_foreign');
            $table->foreign('media_id')->references('id')->on('media');
        });
    }
}
