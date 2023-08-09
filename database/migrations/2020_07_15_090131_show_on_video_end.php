<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ShowOnVideoEnd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('interactions', function (Blueprint $table) {
            $table->integer('show_at_video_end')->default(0);
        });
        Schema::table('element_groups', function (Blueprint $table) {
            $table->integer('show_at_video_end')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('interactions', function (Blueprint $table) {
            $table->dropColumn('show_at_video_end');
        });
        Schema::table('element_groups', function (Blueprint $table) {
            $table->dropColumn('show_at_video_end');
        });
    }
}
