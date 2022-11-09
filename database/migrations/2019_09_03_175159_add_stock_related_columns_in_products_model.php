<?php

use App\Models\History\ProductHistory;
use App\Models\Product;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStockRelatedColumnsInProductsModel extends Migration
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
            $table->double('shrink_single_qty')->nullable(true)->after('shrink_qty');
            $table->double('case_single_qty')->nullable(true)->after('case_qty');
            $table->double('case_shrink_qty')->nullable(true)->after('case_qty');
            $table->double('pallet_single_qty')->nullable(true)->after('pallet_qty');
            $table->double('pallet_shrink_qty')->nullable(true)->after('pallet_qty');
            $table->double('pallet_case_qty')->nullable(true)->after('pallet_qty');
        });

        Schema::table($productHistoryModel->getTable(), function (Blueprint $table) {
            $table->double('shrink_single_qty')->nullable(true)->after('shrink_qty');
            $table->double('case_single_qty')->nullable(true)->after('case_qty');
            $table->double('case_shrink_qty')->nullable(true)->after('case_qty');
            $table->double('pallet_single_qty')->nullable(true)->after('pallet_qty');
            $table->double('pallet_shrink_qty')->nullable(true)->after('pallet_qty');
            $table->double('pallet_case_qty')->nullable(true)->after('pallet_qty');
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
            $table->dropColumn(['shrink_single_qty', 'case_single_qty', 'case_shrink_qty', 'pallet_single_qty', 'pallet_shrink_qty', 'pallet_case_qty']);
        });

        Schema::table($productHistoryModel->getTable(), function (Blueprint $table) {
            $table->dropColumn(['shrink_single_qty', 'case_single_qty', 'case_shrink_qty', 'pallet_single_qty', 'pallet_shrink_qty', 'pallet_case_qty']);
        });
    }
}
