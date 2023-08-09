<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgencyClubDfyContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agency_club_dfy_contents', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->json('projects');
            $table->json('landing_pages');
            $table->string('niche');
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
        Schema::dropIfExists('agency_club_dfy_contents');
    }
}
