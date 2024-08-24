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
        Schema::create('apartness_xes', function (Blueprint $table) {
            //the apartness that will be by ration i each rent
            $table->id();
            $table->unsignedBigInteger('owner_id');
            $table->unsignedBigInteger('apartness_id');
            $table->integer('total_account')->default(0);
            $table->integer('percentage')->default(0);
            $table->integer('total_outgoings')->default(0);
            $table->timestamps();
            $table->foreign('owner_id')->references('id')->on('owners')->onDelete('cascade');
            $table->foreign('apartness_id')->references('id')->on('apartnesses')->onDelete('cascade');
        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('apartness_xes');
    }
};
