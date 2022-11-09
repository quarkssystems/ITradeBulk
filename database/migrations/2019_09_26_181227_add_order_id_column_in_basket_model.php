<?php

use App\Models\Basket;
use App\Models\SalesOrder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderIdColumnInBasketModel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $basketModel = new Basket();
        Schema::table($basketModel->getTable(), function (Blueprint $table) {
            $salesOrderModel = new SalesOrder();
            $table->uuid('order_id')->nullable(true)->after("user_id");
            $table->foreign('order_id')->references('uuid')->on($salesOrderModel->getTable());
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $basketModel = new Basket();
        Schema::table($basketModel->getTable(), function (Blueprint $table) {
            //
        });
    }
}
