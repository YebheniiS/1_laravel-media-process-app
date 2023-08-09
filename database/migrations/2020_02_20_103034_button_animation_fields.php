<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ButtonAnimationFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('button_elements', function (Blueprint $table) {
            $table->json('animation')->nullable();
        });

        Schema::table('image_elements', function (Blueprint $table) {
            $table->json('animation')->nullable();
        });

        Schema::table('text_elements', function (Blueprint $table) {
            $table->json('animation')->nullable();
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
            $table->dropColumn('animation');
        });

        Schema::table('image_elements', function (Blueprint $table) {
            $table->dropColumn('animation');
        });

        Schema::table('text_elements', function (Blueprint $table) {
            $table->dropColumn('animation');
        });
    }
}
