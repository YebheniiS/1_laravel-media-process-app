<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SharePageColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('projects', function(Blueprint $table) {
            $table->integer('show_more_videos_on_share_page')->default(1);
            $table->integer('allow_comments')->default(1);
            $table->integer('is_public')->default(0);
        });

        Schema::table('users', function(Blueprint $table) {
            $table->string('company_name')->default('');
            $table->string('logo')->default('');
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
