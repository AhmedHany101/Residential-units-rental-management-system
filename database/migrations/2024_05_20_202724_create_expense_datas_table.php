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
        Schema::create('expense_datas', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('expense_type')->comment('1=>officeExpenseInput,2=>laborExpenseInput,3=>privateExpenseInput,4=>adminExpenseInput');
            $table->text('note');
            $table->integer('cost');
            $table->string('data_entry_name');
            $table->date('start_date')->nullable();
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
        Schema::dropIfExists('expense_datas');
    }
};
