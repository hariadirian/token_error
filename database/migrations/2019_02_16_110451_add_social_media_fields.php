<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSocialMediaFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('us_frontend_hd', function (Blueprint $table) {
            $table->string('provider')->nullable();
            $table->string('google_id')->nullable();
            $table->string('facebook_id')->nullable();
            $table->string('twitter_id')->nullable();
            // $table->string('email')->nullable()->change();
            $table->string('password')->nullable()->change();
        });
        Schema::table('us_frontend_dt', function (Blueprint $table) {
            $table->string('id_card')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('us_frontend_hd', function (Blueprint $table) {
            $table->dropColumn('google_id');
            $table->dropColumn('facebook_id');
            $table->dropColumn('twitter_id');
        });
        Schema::table('us_frontend_dt', function (Blueprint $table) {
            $table->string('id_card')->change();
        });
    }
}
