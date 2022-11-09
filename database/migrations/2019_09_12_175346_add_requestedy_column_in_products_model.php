<?php

use App\Models\History\ProductHistory;
use App\Models\Product;
use App\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRequestedyColumnInProductsModel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $productModel = new Product();
        Schema::table($productModel->getTable(), function (Blueprint $table) {
            $userModel = new User();
            $table->uuid('user_id')->nullable(true)->after("status");
            $table->foreign('user_id')->references('uuid')->on($userModel->getTable());
            $table->string('barcode')->nullable(true);
        });

        $productHistoryModel = new ProductHistory();
        Schema::table($productHistoryModel->getTable(), function (Blueprint $table) {
            $userModel = new User();
            $table->uuid('user_id')->nullable(true)->after("status");
            $table->foreign('user_id')->references('uuid')->on($userModel->getTable());
            $table->string('barcode')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $productModel = new Product();
        Schema::table($productModel->getTable(), function (Blueprint $table) {
//            $userModel = new User();
//            $table->uuid('user_id')->nullable(true)->after("status");
//            $table->foreign('user_id')->references('uuid')->on($userModel->getTable());
        });

        $productHistoryModel = new ProductHistory();
        Schema::table($productHistoryModel->getTable(), function (Blueprint $table) {
//            $userModel = new User();
//            $table->uuid('user_id')->nullable(true)->after("status");
//            $table->foreign('user_id')->references('uuid')->on($userModel->getTable());
        });
    }
}
