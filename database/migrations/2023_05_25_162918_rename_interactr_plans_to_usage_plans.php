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
        Schema::rename('interactr_plans', 'usage_plans');
        Schema::table('usage_plans', function (Blueprint $table) {
            $table->dropColumn('product_id');
            $table->dropColumn('buy_link');
            $table->dropColumn('pro_features');
        });
        DB::statement("ALTER TABLE usage_plans AUTO_INCREMENT = 1;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
};
