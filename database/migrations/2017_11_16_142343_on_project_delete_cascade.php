<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OnProjectDeleteCascade extends Migration
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
            $table->dropForeign('nodes_project_id_foreign');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
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
            $table->dropForeign('nodes_project_id_foreign');
            $table->foreign('project_id')->references('id')->on('projects');
        });
    }
}
