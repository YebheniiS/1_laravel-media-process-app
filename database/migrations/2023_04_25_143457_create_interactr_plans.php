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
    public function up()
    {
        Schema::create('interactr_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('streaming_mins')->default(0);
            $table->float('upload_gb')->default(0);
            $table->integer('product_id');
            $table->string('buy_link');
            $table->integer('pro_features')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('interactr_plans');
    }
};
