<?php

use App\Models\History\ProductHistory;
use App\Models\Product;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBasePriceColumnInProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $productModel = new Product();
        $productHistoryModel = new ProductHistory();
        Schema::table($productModel->getTable(), function (Blueprint $table) {
            $table->double('base_price')->nullable(true)->after('base_image');
        });

        Schema::table($productHistoryModel->getTable(), function (Blueprint $table) {
            $table->double('base_price')->nullable(true)->after('base_image');
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
        $productHistoryModel = new ProductHistory();
        Schema::table($productModel->getTable(), function (Blueprint $table) {
            $table->dropColumn('base_price');
        });

        Schema::table($productHistoryModel->getTable(), function (Blueprint $table) {
            $table->dropColumn('base_price');
        });
    }
}
