<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomListsEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_lists_emails', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('custom_lists_id');
            $table->foreign('custom_lists_id')
                    ->references('id')
                    ->on('custom_lists')
                    ->onDelete('cascade');
            $table->string('email');
            $table->string('name')->nullable();
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
        Schema::dropIfExists('custom_lists_emails');
    }
}
