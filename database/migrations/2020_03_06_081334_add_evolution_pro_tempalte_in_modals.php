<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEvolutionProTempalteInModals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('modals', function (Blueprint $table) {
            $table->boolean('evolution_pro_template')->after('is_template')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('modals', function (Blueprint $table) {
            $table->dropColumn('evolution_pro_template');
        });
    }
}
