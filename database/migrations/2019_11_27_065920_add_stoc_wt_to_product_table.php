<?php

use App\Models\History\ProductHistory;
use App\Models\Product;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStocWtToProductTable extends Migration
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
             $table->double('stoc_wt')->after('single_height')->nullable(true);
            //
        });
        Schema::table($productHistoryModel->getTable(), function (Blueprint $table) {
             $table->double('stoc_wt')->after('single_height')->nullable(true);
            //
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
           $table->dropColumn(['stoc_wt']);
        });
        Schema::table($productHistoryModel->getTable(), function (Blueprint $table) {
           $table->dropColumn(['stoc_wt']);
        });
    }
}
