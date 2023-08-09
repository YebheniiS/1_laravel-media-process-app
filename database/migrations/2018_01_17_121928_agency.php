<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Agency extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('agency', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable()->default('');
            $table->string('domain')->nullable()->default('');
            $table->string('page_title')->nullable()->default('');
            $table->string('primary_color')->nullable()->default('#00b382');
            $table->string('secondary_color')->nullable()->default('#00a2e1');
            $table->string('icon')->nullable()->default('');
            $table->string('logo')->nullable()->default('');
            $table->timestamps();
        });

        Schema::table('users', function(Blueprint $table) {
            $table->integer('is_agency')->default(0);
            $table->integer('is_club')->default(0);
            $table->integer('parent_user_id')->default(0);
            $table->json('projects')->nullable();
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
    }
}
