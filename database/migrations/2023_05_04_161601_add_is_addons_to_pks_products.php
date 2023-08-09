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
            if (!Schema::hasColumn('pks_products', 'is_addons')) {
                $table->boolean('is_addons')->default(false);
            }
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
            if (Schema::hasColumn('pks_products', 'is_addons')) {
                $table->dropColumn('is_addons');
            }
        });
    }
};
