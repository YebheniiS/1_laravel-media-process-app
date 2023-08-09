<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class JvZoopayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('jvzoo_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('transaction_id');
            $table->integer('product_id');
            $table->float('amount');
            $table->string('email')->nullable();
            $table->string('name')->nullable();
            $table->string('affiliate');
            $table->integer('user_id')->nullable();
            $table->integer('refunded')->nullable();
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
        //
        Schema::dropIfExists('jvzoo_payments');
    }
}
