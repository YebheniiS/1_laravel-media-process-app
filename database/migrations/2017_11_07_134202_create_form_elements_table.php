<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormElementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_elements', function (Blueprint $table) {
            $table->increments('id');

            $table->boolean('show_name_field')->default(false);

            // the autoresponder this form is linked to
            $table->string('integration')->nullable();
            $table->string('integration_list')->nullable();

            // submit action
            $table->string('action')->nullable();
            $table->string('actionArg')->nullable();
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
        Schema::dropIfExists('form_elements');
    }
}
