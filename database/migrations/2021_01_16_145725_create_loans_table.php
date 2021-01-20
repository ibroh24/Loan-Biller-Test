<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('userid');
            $table->decimal('loanamount');
            $table->integer('interest');
            $table->integer('loanperiod');
            $table->decimal('interestpayable', 19, 4);
            $table->decimal('totalrefundable', 19, 4);
            $table->date('startdate');
            $table->date('enddate');
            $table->tinyInteger('paid')->default(0);
            $table->tinyInteger('isactive')->default(0);
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
        Schema::dropIfExists('loans');
    }
}
