<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgencyClubLandingPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agency_club_landing_pages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('convertri_url')->nullable();
            $table->string('html_url')->nullable();
            $table->string('clickfunnels_url');
            $table->string('preview_url');
            $table->string('image_url');
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
        Schema::dropIfExists('agency_club_landing_pages');
    }
}
