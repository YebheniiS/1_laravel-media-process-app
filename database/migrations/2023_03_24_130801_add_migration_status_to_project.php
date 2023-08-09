<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    /*********************************
     * 
     *  migration_status has 3 values
     *  0 - not migrated
     *  1 - migrating
     *  2 - migration is done
     * 
     *********************************/

    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            if (!Schema::hasColumn('projects', 'migration_status')) {
                $table->tinyInteger('migration_status')->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            if (Schema::hasColumn('projects', 'migration_status')) {
                $table->dropColumn('migration_status');
            }
        });
    }
};
