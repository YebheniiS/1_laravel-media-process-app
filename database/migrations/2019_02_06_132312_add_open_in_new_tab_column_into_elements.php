<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOpenInNewTabColumnIntoElements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('hotspot_elements', function(Blueprint $table) {
            $table->boolean('open_in_new_tab')->default(true)->after('actionArg');
        });
        Schema::table('button_elements', function(Blueprint $table) {
            $table->boolean('open_in_new_tab')->default(true)->after('actionArg');
        });
        Schema::table('form_elements', function(Blueprint $table) {
            $table->boolean('open_in_new_tab')->default(true)->after('actionArg');
        });
        Schema::table('image_elements', function(Blueprint $table) {
            $table->boolean('open_in_new_tab')->default(true)->after('actionArg');
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
    }
}
