<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldToWalletTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
              $table->uuid('order_id')->nullable(true)->after('receipt');
              $table->foreign('order_id')->references('uuid')->on('sales_orders');
              $table->bigInteger('admin_charge')->nullable(true)->after('order_id');//
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            //

        });
    }
}
