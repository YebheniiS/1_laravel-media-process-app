<?php

use App\Helper\MigrationHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FormElementBorderFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('form_elements', function(Blueprint $table){
            $table->integer('border_width')->default(0);
            $table->string('border_type')->default('');
            $table->string('border_color')->default('');
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
        MigrationHelper::dropColumn('form_elements', ['border_width', 'border_type', 'border_color'] );
    }
}
