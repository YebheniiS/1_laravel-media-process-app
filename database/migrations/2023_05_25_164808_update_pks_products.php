<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('pks_products', function (Blueprint $table) {
            $table->integer('usage_plan_id');
            $table->dropColumn('campaign_name');
            $table->dropColumn('access_level_id');
            $table->dropColumn('ac_list_id');
            $table->dropColumn('streaming_mins');
            $table->dropColumn('upload_gb');
        });

        DB::statement("ALTER TABLE pks_products AUTO_INCREMENT = 1;");
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
};
