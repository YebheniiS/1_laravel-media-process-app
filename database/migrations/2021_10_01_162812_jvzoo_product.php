<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class JvzooProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jvzoo_products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('access_level_id');
            $table->unsignedInteger('ac_list_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jvzoo_products');
    }
}
