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
        Schema::create('apartness_ies', function (Blueprint $table) {
             //the apartness that will be by fixed cost per season
             $table->id();
             $table->unsignedBigInteger('owner_id');
             $table->unsignedBigInteger('apartness_id');
             $table->integer('total_account')->default(0);
             $table->integer('payed')->default(0);
             $table->integer('remaining')->default(0);
             $table->foreign('owner_id')->references('id')->on('owners')->onDelete('cascade');
             $table->foreign('apartness_id')->references('id')->on('apartnesses')->onDelete('cascade');
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
        Schema::dropIfExists('apartness_ies');
    }
};
