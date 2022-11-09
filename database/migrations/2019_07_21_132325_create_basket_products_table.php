<?php

use App\Models\Basket;
use App\Models\Product;
use App\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBasketProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('basket_products', function (Blueprint $table) {
            $basketModel = new Basket();
            $productModel = new Product();

            $table->increments('id');
            $table->uuid('uuid');
            $table->index('uuid');

            $table->uuid('basket_id')->nullable(true);
            $table->foreign('basket_id')->references('uuid')->on($basketModel->getTable());

            $table->uuid('product_id')->nullable(true);
            $table->foreign('product_id')->references('uuid')->on($productModel->getTable());

            $table->integer('single_qty')->default(0)->nullable();
            $table->integer('shrink_qty')->default(0)->nullable();
            $table->integer('case_qty')->default(0)->nullable();
            $table->integer('pallet_qty')->default(0)->nullable();

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
        Schema::dropIfExists('basket_products');
    }
}
