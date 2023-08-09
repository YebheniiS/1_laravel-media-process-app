<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInteractionLayerInModals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasColumn('modals', 'interaction_layer')) {
            Schema::table('modals', function (Blueprint $table) {
                $table->integer('interaction_layer')->default(0);
            });
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('modals', 'interaction_layer')) {
            Schema::table('modals', function (Blueprint $table) {
                $table->dropColumn('interaction_layer');
            });
        }
    }
}
