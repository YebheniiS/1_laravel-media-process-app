<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddErrorFieldsInQencodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('qencode_tasks', function (Blueprint $table) {
            $table->boolean('failed')->nullable()->after('status');
            $table->string('error_description')->nullable()->after('failed');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('qencode_tasks', function (Blueprint $table) {
            $table->dropColumn([
                'failed',
                'error_description'
            ]);
        });
    }
}
