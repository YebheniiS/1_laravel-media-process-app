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
        Schema::table('pks_products', function (Blueprint $table) {
            //
            $table->dropColumn('is_addons');
            $table->integer('streaming_mins')->default(0);
            $table->float('upload_gb')->default(0);
            $table->string('buy_link');
            $table->boolean('is_pro')->default(false);
            $table->boolean('is_agency')->default(false);
            $table->integer('downgrade_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pks_products', function (Blueprint $table) {
            //
            $table->boolean('is_addons')->default(false);
            $table->dropColumn('streaming_mins');
            $table->dropColumn('upload_gb');
            $table->dropColumn('buy_link');
            $table->dropColumn('is_pro');
            $table->dropColumn('is_agency');
            $table->dropColumn('downgrade_id');
        });
    }
};
