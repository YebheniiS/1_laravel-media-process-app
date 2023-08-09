<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Cleanup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (! Schema::hasColumn('modals', 'background_animation')) {
            Schema::table('modals', function (Blueprint $table) {
                $table->json('background_animation')->nullabe();
            });
        }
        if (Schema::hasColumn('modals', 'animation')) {
            Schema::table('modals', function (Blueprint $table) {
                $table->dropColumn('animation');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
