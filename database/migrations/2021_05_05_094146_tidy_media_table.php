<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TidyMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('media', function (Blueprint $table) {
            $table->dropColumn('hls_stream');
            $table->dropColumn('stream_url');
            $table->dropColumn('hidden');
            $table->dropColumn('compressed_mp4');
            $table->dropColumn('compressed_url');
            $table->dropColumn('preload_manifest_url');

            $table->string('temp_storage_url')->nullable()->after('manifest_url');

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
        Schema::table('media', function (Blueprint $table) {
            $table->string('hls_stream')->nullable();
            $table->string('stream_url')->nullable();
            $table->integer('compressed_mp4')->nullable();
            $table->integer('hidden')->nullable();
            $table->string('compressed_url')->nullable();
            $table->string('preload_manifest_url')->nullable();

            $table->dropColumn('temp_storage_url');
        });

    }
}
