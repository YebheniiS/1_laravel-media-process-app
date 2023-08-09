<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOpacityIntoImageElements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('image_elements', function (Blueprint $table) {
            $table->float('opacity')->default(1)->after('zIndex');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('image_elements', function (Blueprint $table) {
            $table->dropColumn('opacity');
        });
    }
}
