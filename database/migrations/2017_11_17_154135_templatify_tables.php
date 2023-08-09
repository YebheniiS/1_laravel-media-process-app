<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TemplatifyTables extends Migration
{
    protected function templatify(Blueprint $table)
    {
        $table->boolean('is_template')->default(false);
        $this->addTemplateMeta($table);
    }

    protected function addTemplateMeta(Blueprint $table)
    {

        $table->string('template_image_url')->nullable();
        $table->string('template_name')->nullable();
    }
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function(Blueprint $table) {
            $this->addTemplateMeta($table);
        });
        Schema::table('modals', function($table) {$this->templatify($table);});
        Schema::table('form_elements', function($table) {$this->templatify($table);});

        // templates are now stored within the tables they belong to.
        Schema::drop('templates');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        $a = new CreateTemplatesTable();
        $a->up();
    }
}
