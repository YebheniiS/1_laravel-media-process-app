<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCollectionToBunny extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('bunny_cdn_videos', function (Blueprint $table) {
            $table->string('collection_id')->default("");
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
        Schema::table('bunny_cdn_videos', function (Blueprint $table) {
            $table->dropColumn('collection_id');
        });
    }
}
