<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateNodeTable20 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('nodes', function (Blueprint $table){
            // Remove the old columns we don't need
            $table->dropColumn(['has_chapters', 'chapters_file']);
            $table->integer('completeActionDelay')->after('completeActionArg')->nullable();
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
        Schema::table('nodes',function (Blueprint $table){
            $table->dropColumn('completeActionDelay');
            $table->integer('has_chapters')->default(0);
            $table->string('chapters_file')->default('');
        });
    }
}
