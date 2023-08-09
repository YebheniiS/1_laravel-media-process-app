<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDefaultValuesColumnForTextElements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('button_elements', function (Blueprint $table) {
            $table->json('default_values')->after('html');
        });

        Schema::table('custom_html_elements', function (Blueprint $table) {
            $table->json('default_values')->after('html');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('button_elements', function (Blueprint $table) {
            $table->dropColumn('default_values');
        });

        Schema::table('custom_html_elements', function (Blueprint $table) {
            $table->dropColumn('default_values');
        });

    }
}
