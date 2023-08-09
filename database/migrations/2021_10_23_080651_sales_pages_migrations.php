<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SalesPagesMigrations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('domains', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('domain_name');
            $table->string('facebook_pixel');
            $table->text('custom_scripts')->nullable();
            $table->text('custom_styles')->nullable();
        });

        Schema::create('funnels', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->string('url');
            $table->integer('is_facebook')->default(0);
            $table->text('custom_scripts')->nullable();
            $table->text('custom_styles')->nullable();
            $table->integer('domain_id');
        });

        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->string('url');
            $table->integer('template_id');
            $table->string('price')->nullable();
            $table->integer('price_two')->nullable();
            $table->string('custom_content_one')->nullable();
            $table->string('custom_content_two')->nullable();
            $table->string('buy_button_one')->nullable();
            $table->string('buy_button_two')->nullable();
            $table->string('no_thanks_link')->nullable();
            $table->string('banner_text')->nullable();
            $table->integer('funnel_id');
            $table->text('custom_scripts')->nullable();
            $table->text('custom_styles')->nullable();
            $table->integer('checkout_id')->nullable();
        });

        Schema::create('templates', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->string('blade_template');
        });

        Schema::create('redirects', function (Blueprint $table){
            $table->id();
            $table->timestamps();
            $table->string('from');
            $table->string('to');
            $table->integer('visits')->default(0);
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('domains');
        Schema::dropIfExists('funnels');
        Schema::dropIfExists('pages');
        Schema::dropIfExists('templates');
        Schema::dropIfExists('redirects');
    }
}
