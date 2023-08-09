<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSubUserIdIntoFormElements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('form_elements', function (Blueprint $table) {
            $table->integer('sub_user')->default(0)->after('integration_list');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('form_elements', function (Blueprint $table) {
            $table->dropColumn('sub_user');
        });
    }
}
