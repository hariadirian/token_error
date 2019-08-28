<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTmiiEtOrderedTicketTxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tmii_et_ordered_ticket_txes', function (Blueprint $table) {
            $table->increments('id_et_ordered_ticket_txes');
            $table->string('cd_et_ordered_ticket_txes');
            $table->integer('id_et_cart_product_hd');
            $table->decimal('total_amount', 9,2);
            $table->timestamp('paid_at')->nullable();
            $table->integer('paid_state')->default(1)->comment('1:ONPROGRESS, 2:PAID, 3:EXPIRED, 4:CANCEL, 9:ELSE');
            $table->timestamps();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->string('deleted_by')->nullable();
            $table->enum('is_active', ['Y','N'])->default('Y');
            $table->enum('state', ['Y','N'])->default('Y');
            $table->foreign('id_et_cart_product_hd')->references('id_et_cart_product_hd')->on('tmii_et_cart_product_hd');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tmii_et_ordered_ticket_txes');
    }
}
