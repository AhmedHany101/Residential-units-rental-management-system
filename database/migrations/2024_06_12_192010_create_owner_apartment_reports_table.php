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
        Schema::create('owner_apartment_reports', function (Blueprint $table) {
            $table->id();
            $table->string('data_entry_name');
            $table->unsignedBigInteger('apartment_id');
            $table->integer('amount');
            $table->integer('total_account');
            $table->tinyInteger('process_type')->comment('0 => reservation, 1 => paid, 2 => outgoing');
            $table->timestamps();
            $table->foreign('apartment_id')->references('id')->on('apartnesses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('owner_apartment_reports');
    }
};
