<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStockVideoIdInQencodeTasks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('qencode_tasks', function (Blueprint $table) {
            $table->unsignedInteger('stock_video_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('qencode_tasks', function (Blueprint $table) {
            $table->dropColumn('stock_video_id');
        });
    }
}
