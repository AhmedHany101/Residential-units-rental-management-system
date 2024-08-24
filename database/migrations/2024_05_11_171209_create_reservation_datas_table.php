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
        Schema::create('reservation_datas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('department_id');
            $table->integer('price_per_night');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('employ_name')->nullable();
            $table->unsignedBigInteger('data_entry');
            $table->foreign('customer_id')->references('id')->on('customer_infos')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('apartnesses')->onDelete('cascade');
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('reservation_datas');
    }
};
