<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterStateColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('us_frontend_hd', function (Blueprint $table) {
            $table->string('is_active',1)->default('Y');
            $table->string('state',1)->default('Y');
        });
        Schema::table('us_frontend_dt', function (Blueprint $table) {
            $table->string('state',1)->default('Y');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('us_frontend_hd', function (Blueprint $table) {
        //     $table->enum('is_active',['Y', 'N'])->default('Y');
        //     $table->enum('state',['Y', 'N'])->default('Y');
        // });
    }
}
