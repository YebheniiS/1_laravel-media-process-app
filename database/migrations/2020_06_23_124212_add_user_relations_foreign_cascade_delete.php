<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserRelationsForeignCascadeDelete extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign('projects_user_id_foreign');
            $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');
        });

        Schema::table('media', function (Blueprint $table) {
            $table->dropForeign('media_user_id_foreign');
            $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');
        });

        Schema::table('project_groups', function (Blueprint $table) {
            $table->dropForeign('project_groups_user_id_foreign');
            $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');
        });

        Schema::table('custom_lists', function (Blueprint $table) {
            $table->dropForeign('custom_lists_user_id_foreign');
            $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {


        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign('projects_user_id_foreign');
            $table->foreign('user_id')
                    ->references('id')
                    ->on('users');
        });

        Schema::table('media', function (Blueprint $table) {
            $table->dropForeign('media_user_id_foreign');
            $table->foreign('user_id')
                    ->references('id')
                    ->on('users');
        });

        Schema::table('project_groups', function (Blueprint $table) {
            $table->dropForeign('project_groups_user_id_foreign');
            $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');
        });

        Schema::table('custom_lists', function (Blueprint $table) {
            $table->dropForeign('custom_lists_user_id_foreign');
            $table->foreign('user_id')
                    ->references('id')
                    ->on('users');
        });
    }
}
