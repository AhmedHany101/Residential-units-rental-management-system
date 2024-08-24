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
        Schema::create('apartnesses', function (Blueprint $table) {
            $table->id();
            $table->string('apartness_no');
            $table->string('buliding_no');
            $table->string('floor_no');
            $table->string('room_no');
            $table->string('sys_type');
            $table->integer('cost_per_night');
            $table->unsignedBigInteger('owner_id')->nullable();
            $table->string('owner_type')->nullable();
            $table->text('not')->nullable();
            $table->foreign('owner_id')->references('id')->on('owners')->onDelete('cascade');
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
        Schema::dropIfExists('apartnesses');
    }
};
