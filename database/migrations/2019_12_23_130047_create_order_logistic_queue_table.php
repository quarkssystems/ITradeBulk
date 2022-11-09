<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\User;
use App\Models\SalesOrder;

class CreateOrderLogisticQueueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_logistic_queue', function (Blueprint $table) {
            //
            $userModel = new User();
            $salesModel = new SalesOrder();
               

            $table->increments('id');
            $table->uuid('uuid');
            $table->index('uuid');

            $table->string('order_id')->nullable(true);
             $table->foreign('order_id')->references('uuid')->on($salesModel->getTable());

            $table->uuid('vendor_id');
            $table->foreign('vendor_id')->references('uuid')->on($userModel->getTable());

            $table->uuid('supplier_id');
            $table->foreign('supplier_id')->references('uuid')->on($userModel->getTable());
            $table->uuid('driver_id');
            $table->foreign('driver_id')->references('uuid')->on($userModel->getTable());
            
            $table->float('distance')->nullable(true);
            $table->string('status')->nullable(true);

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
       
         Schema::dropIfExists('order_logistic_queue');
    }
}
