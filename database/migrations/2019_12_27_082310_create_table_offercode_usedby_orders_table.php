<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\User;
use App\Models\SalesOrder;
use App\Models\OfferDeals;


class CreateTableOffercodeUsedbyOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offercode_usedby_orders', function (Blueprint $table) {
            $userModel = new User();
            $salesModel = new SalesOrder();
            $offerModel = new OfferDeals();
               

            $table->increments('id');
            $table->uuid('uuid');
            $table->index('uuid');

            $table->string('order_id')->nullable(true);
            $table->foreign('order_id')->references('uuid')->on($salesModel->getTable());

            $table->uuid('user_id');
            $table->foreign('user_id')->references('uuid')->on($userModel->getTable());

            $table->uuid('offer_id');
            $table->foreign('offer_id')->references('uuid')->on($offerModel->getTable());
            
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
       
         Schema::dropIfExists('offercode_usedby_orders');
    }
}
