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
        Schema::create('reservation_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reservation_id');
            $table->unsignedBigInteger('customer_id');
            $table->tinyInteger('payment_method')->comment('1=>cash,2=>bank,3=>wallet');
            $table->integer('total')->default('0');
            $table->integer('payed')->default('0');
            $table->integer('remaining')->default('0');
            $table->foreign('reservation_id')->references('id')->on('reservation_datas')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customer_infos')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservation_payments');
    }
};
