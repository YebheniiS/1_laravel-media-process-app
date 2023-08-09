<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePksProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pks_products', function (Blueprint $table) {
            $table->id();
            $table->string('campaign_name');
            $table->string('product_name');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('access_level_id');
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
        Schema::dropIfExists('pks_products');
    }
}
