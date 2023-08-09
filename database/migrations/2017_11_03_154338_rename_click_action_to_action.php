<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameClickActionToAction extends Migration
{
    protected $tables = ['button_elements', 'hotspot_elements', 'image_elements'];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        foreach($this->tables as $tableName) {
            Schema::table($tableName, function ($table) {
                $table->renameColumn('"clickAction"', 'action');
                $table->renameColumn('"clickActionArg"', 'actionArg');
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
        foreach($this->tables as $tableName) {
            Schema::table($tableName, function ($table) {
                $table->renameColumn('action', 'clickAction');
                $table->renameColumn('actionArg', 'clickActionArg');
            });
        }
    }
}
