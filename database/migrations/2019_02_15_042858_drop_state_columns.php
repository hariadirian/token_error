<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropStateColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('us_frontend_hd', function (Blueprint $table) {            
            $table->dropColumn('is_active');
            $table->dropColumn('state');
        });
        Schema::table('us_frontend_dt', function (Blueprint $table) {            
            $table->dropColumn('state');
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
        //     // $table->enum('is_active',['Y', 'N'])->default('Y');
        //     $table->enum('state',['Y', 'N'])->default('Y');
        // });
        // Schema::table('us_frontend_dt', function (Blueprint $table) {
        //     $table->enum('state',['Y', 'N'])->default('Y');
        // });
    }
}
