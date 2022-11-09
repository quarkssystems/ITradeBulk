<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TradingArea extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trading_area', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->index('uuid');
            $table->string('area')->nullable(true);
            $table->enum('status',[0,1])->default('1');
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
        Schema::dropIfExists('trading_area');

    }
}