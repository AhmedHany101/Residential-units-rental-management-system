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
        Schema::create('customer_infos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone_one');
            $table->string('phone_two')->nullable();
            $table->string('phone_three')->nullable();
            $table->tinyInteger('customer_type')->comment('take 0 ,1 , 0=>egyption,1=>foregin');
            $table->tinyInteger('customer_rate')->comment('0=undefine,1=>bad,2=>good,3=>VIP');
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
        Schema::dropIfExists('customer_infos');
    }
};
