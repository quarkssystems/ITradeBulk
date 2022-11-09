<?php

use App\Models\History\ProductHistory;
use App\Models\Product;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVolumnColumnsInProductsModel extends Migration
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
            $table->double('single_height')->after('single_qty')->nullable(true);
            $table->double('single_width')->after('single_qty')->nullable(true);
            $table->double('single_length')->after('single_qty')->nullable(true);

            $table->double('shrink_height')->after('single_qty')->nullable(true);
            $table->double('shrink_width')->after('single_qty')->nullable(true);
            $table->double('shrink_length')->after('single_qty')->nullable(true);

            $table->double('case_height')->after('single_qty')->nullable(true);
            $table->double('case_width')->after('single_qty')->nullable(true);
            $table->double('case_length')->after('single_qty')->nullable(true);

            $table->double('pallet_height')->after('single_qty')->nullable(true);
            $table->double('pallet_width')->after('single_qty')->nullable(true);
            $table->double('pallet_length')->after('single_qty')->nullable(true);
        });

        Schema::table($productHistoryModel->getTable(), function (Blueprint $table) {
            $table->double('single_height')->after('single_qty')->nullable(true);
            $table->double('single_width')->after('single_qty')->nullable(true);
            $table->double('single_length')->after('single_qty')->nullable(true);

            $table->double('shrink_height')->after('single_qty')->nullable(true);
            $table->double('shrink_width')->after('single_qty')->nullable(true);
            $table->double('shrink_length')->after('single_qty')->nullable(true);

            $table->double('case_height')->after('single_qty')->nullable(true);
            $table->double('case_width')->after('single_qty')->nullable(true);
            $table->double('case_length')->after('single_qty')->nullable(true);

            $table->double('pallet_height')->after('single_qty')->nullable(true);
            $table->double('pallet_width')->after('single_qty')->nullable(true);
            $table->double('pallet_length')->after('single_qty')->nullable(true);
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
            $table->dropColumn(['single_height', 'single_width', 'single_length', 'shrink_height', 'shrink_width', 'shrink_length', 'case_height', 'case_width', 'case_length', 'pallet_height', 'pallet_width', 'pallet_length']);
        });

        Schema::table($productHistoryModel->getTable(), function (Blueprint $table) {
            $table->dropColumn(['single_height', 'single_width', 'single_length', 'shrink_height', 'shrink_width', 'shrink_length', 'case_height', 'case_width', 'case_length', 'pallet_height', 'pallet_width', 'pallet_length']);
        });
    }
}
