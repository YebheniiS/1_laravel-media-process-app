<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CleanupUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function(Blueprint $table){

            // Old columns we can delete
            $table->dropColumn('role');
            $table->dropColumn('advanced_analytics');
            $table->dropColumn('subscription_user');
            $table->dropColumn('is_viddyoze');
            $table->dropColumn('should_compress_videos');
            $table->dropColumn('should_stream_videos');
            $table->dropColumn('is_local');
            $table->dropColumn('evolution');
            $table->dropColumn('masterclass');

            // Columns to rename so it's not all evolution specific
            $table->renameColumn('evolution_pro', 'is_pro');
            $table->renameColumn('evolution_club', 'is_agency_club');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function(Blueprint $table){

            // Old columns we can delete
            $table->string('role')->nullable();
            $table->integer('advanced_analytics')->nullable();
            $table->integer('subscription_user')->nullable();
            $table->integer('is_viddyoze')->nullable();
            $table->integer('should_compress_videos')->nullable();
            $table->integer('should_stream_videos')->nullable();
            $table->integer('is_local')->nullable();
            $table->integer('evolution')->nullable();
            $table->integer('masterclass')->nullable();

            // Columns to rename so it's not all evolution specific
            $table->renameColumn('is_pro', 'evolution_pro');
            $table->renameColumn('is_agency_club', 'evolution_club');
        });
    }
}
