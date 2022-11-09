<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderstatusUpdateHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orderstatus_update_histories', function (Blueprint $table) {
            //
            $table->increments('id');
            $table->uuid('uuid');
            $table->index('uuid');

            $table->uuid('sales_id')->nullable(true);
            $table->foreign('sales_id')->references('uuid')->on('sales_orders');

            $table->uuid('user_id')->nullable(true);
            $table->foreign('user_id')->references('uuid')->on('users');

            
            $table->string('order_status')->nullable(true);

            $table->uuid('updated_by');
            $table->foreign('updated_by')->references('uuid')->on('users');

            $table->text('update_note')->nullable(true);

            $table->uuid('history_of');
            $table->foreign('history_of')->references('uuid')->on('orderstatus_updates');

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
        Schema::create('orderstatus_update_histories', function (Blueprint $table) {
            Schema::dropIfExists('orderstatus_update_histories');
        });
    }
}
