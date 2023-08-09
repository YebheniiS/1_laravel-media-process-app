<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddZIndexIntoElementGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('element_groups', function (Blueprint $table) {
            $table->integer('zIndex')->after('timeOut')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('element_groups', function (Blueprint $table) {
            $table->dropColumn('zIndex');
        });
    }
}
