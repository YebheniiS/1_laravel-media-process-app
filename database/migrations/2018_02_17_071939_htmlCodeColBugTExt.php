<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class HtmlCodeColBugTExt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('custom_html_elements', function(Blueprint $table) {
            $table->longText('html')->change();
        });
    }

    /**
     * Reverse the migrations.
     *,
     * ,,,,
     * @return void
     */
    public function down()
    {
        //
    }
}
