<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CleanupProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('projects', function(Blueprint $table) {
        // Old columns we can delete
        $table->dropColumn('allow_fullscreen');
        $table->dropColumn('show_controls');
        $table->dropColumn('club_template');
        $table->dropForeign(['language_id']);
        $table->dropColumn('language_id');
        $table->dropColumn('local_template');
        $table->dropColumn('legacy');
        $table->dropColumn('evolution_template');

        // Add new columns
        $table->integer('template_is_dfy')->default(0);
        $table->integer('template_is_example')->default(0);
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('projects', function(Blueprint $table) {
        // Old columns we can delete
        $table->boolean('allow_fullscreen')->default(true);
        $table->boolean('show_controls')->default(true);
        $table->integer('show_controls')->default(0);
        $table->foreignId('language_id');
        $table->integer('local_template')->default(0);
        $table->integer('legacy')->default(1);
        $table->integer('evolution_template')->default(0);
        // New columns
        $table->dropColumn('template_is_dfy');
        $table->dropColumn('template_is_example');
      });
    }
}
