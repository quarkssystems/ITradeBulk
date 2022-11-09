<?php

use App\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_orders', function (Blueprint $table) {
            $userModel = new User();

            $table->increments('id');
            $table->uuid('uuid');
            $table->index('uuid');

            $table->string('order_id')->nullable(true);
            $table->uuid('user_id')->nullable(true);
            $table->foreign('user_id')->references('uuid')->on($userModel->getTable());

            $table->uuid('supplier_id')->nullable(true);
            $table->foreign('supplier_id')->references('uuid')->on($userModel->getTable());

            $table->uuid('logistic_id')->nullable(true);
            $table->foreign('logistic_id')->references('uuid')->on($userModel->getTable());

            $table->double('cart_amount')->nullable(true);
            $table->double('shipment_amount')->nullable(true);
            $table->double('discount_amount')->nullable(true);
            $table->double('final_total')->nullable(true);
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
        Schema::dropIfExists('sales_orders');
    }
}
