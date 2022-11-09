<?php

use App\Models\History\ProductHistory;
use App\Models\Product;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLogisticsColumnsInProductsTable extends Migration
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
            $table->integer('single_qty')->after('tax_id')->nullable(true);
            $table->double('single_weight')->after('tax_id')->nullable(true);

            $table->integer('shrink_qty')->after('tax_id')->nullable(true);
            $table->double('shrink_weight')->after('tax_id')->nullable(true);

            $table->integer('case_qty')->after('tax_id')->nullable(true);
            $table->double('case_weight')->after('tax_id')->nullable(true);

            $table->integer('pallet_qty')->after('tax_id')->nullable(true);
            $table->double('pallet_weight')->after('tax_id')->nullable(true);
        });

        Schema::table($productHistoryModel->getTable(), function (Blueprint $table) {
            $table->integer('single_qty')->after('tax_id')->nullable(true);
            $table->double('single_weight')->after('tax_id')->nullable(true);

            $table->integer('shrink_qty')->after('tax_id')->nullable(true);
            $table->double('shrink_weight')->after('tax_id')->nullable(true);

            $table->integer('case_qty')->after('tax_id')->nullable(true);
            $table->double('case_weight')->after('tax_id')->nullable(true);

            $table->integer('pallet_qty')->after('tax_id')->nullable(true);
            $table->double('pallet_weight')->after('tax_id')->nullable(true);
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
            $table->dropColumn(['single_qty', 'single_weight', 'shrink_qty', 'shrink_weight', 'case_qty', 'case_weight', 'pallet_qty', 'pallet_weight']);
        });

        Schema::table($productHistoryModel->getTable(), function (Blueprint $table) {
            $table->dropColumn(['single_qty', 'single_weight', 'shrink_qty', 'shrink_weight', 'case_qty', 'case_weight', 'pallet_qty', 'pallet_weight']);
        });
    }
}
