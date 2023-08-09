<?php

use App\Helper\MigrationHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SurveyAndFacebookCLickableOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('hotspot_elements', function (Blueprint $table){
            $table->integer('send_facebook_click_event')->after('actionArg')->default(0);
            $table->integer('facebook_click_event_id')->after('actionArg')->default(0);

            $table->integer('send_survey_click_event')->after('actionArg')->default(0);
        });
        Schema::table('button_elements', function (Blueprint $table){
            $table->integer('send_facebook_click_event')->after('actionArg')->default(0);
            $table->integer('facebook_click_event_id')->after('actionArg')->default(0);

            $table->integer('send_survey_click_event')->after('actionArg')->default(0);
        });

        Schema::table('form_elements', function (Blueprint $table){
            $table->integer('send_facebook_onSubmit_event')->after('actionArg')->default(0);
            $table->integer('facebook_onSubmit_event_id')->after('actionArg')->default(0);
        });

        Schema::table('image_elements', function (Blueprint $table){
            $table->integer('send_facebook_click_event')->after('actionArg')->default(0);
            $table->integer('facebook_click_event_id')->after('actionArg')->default(0);

            $table->integer('send_survey_click_event')->after('actionArg')->default(0);
        });

        Schema::table('interactions', function (Blueprint $table){
           $table->integer('send_facebook_view_event')->default(0);
           $table->integer('facebook_view_event_id')->default(0);
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
        MigrationHelper::dropColumn('hotspot_elements', ['send_facebook_click_event', 'facebook_click_event_id', 'send_survey_click_event'] );

        MigrationHelper::dropColumn('button_elements', ['send_facebook_click_event', 'facebook_click_event_id', 'send_survey_click_event'] );

        MigrationHelper::dropColumn('form_elements', ['send_facebook_onSubmit_event', 'facebook_onSubmit_event_id'] );

        MigrationHelper::dropColumn('image_elements', ['send_facebook_click_event', 'facebook_click_event_id', 'send_survey_click_event'] );

        MigrationHelper::dropColumn('interactions', ['send_facebook_view_event', 'facebook_view_event_id']);
    }
}
