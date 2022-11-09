<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderstatusUpdateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orderstatus_updates', function (Blueprint $table) {
            //
            $table->increments('id');
            $table->uuid('uuid');
            $table->index('uuid');

            $table->uuid('sales_id')->nullable(true);
            $table->foreign('sales_id')->references('uuid')->on('sales_orders');

            $table->uuid('user_id')->nullable(true);
            $table->foreign('user_id')->references('uuid')->on('users');

            
            $table->string('order_status')->nullable(true);

            $table->softDeletes();
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
        Schema::create('orderstatus_updates', function (Blueprint $table) {
            //
             Schema::dropIfExists('orderstatus_updates');
        });
    }
}
