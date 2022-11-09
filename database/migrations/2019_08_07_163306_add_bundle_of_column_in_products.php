<?php

use App\Models\History\ProductHistory;
use App\Models\Product;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBundleOfColumnInProducts extends Migration
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
            $table->string('single_bundle_of')->nullable(true)->after('single_weight');
            $table->string('shrink_bundle_of')->nullable(true)->after('shrink_weight');
            $table->string('case_bundle_of')->nullable(true)->after('case_weight');
            $table->string('pallet_bundle_of')->nullable(true)->after('pallet_weight');
        });

        Schema::table($productHistoryModel->getTable(), function (Blueprint $table) {
            $table->string('single_bundle_of')->nullable(true)->after('single_weight');
            $table->string('shrink_bundle_of')->nullable(true)->after('shrink_weight');
            $table->string('case_bundle_of')->nullable(true)->after('case_weight');
            $table->string('pallet_bundle_of')->nullable(true)->after('pallet_weight');
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
            $table->dropColumn(['single_bundle_of', 'shrink_bundle_of', 'case_bundle_of', 'pallet_bundle_of']);
        });

        Schema::table($productHistoryModel->getTable(), function (Blueprint $table) {
            $table->dropColumn(['single_bundle_of', 'shrink_bundle_of', 'case_bundle_of', 'pallet_bundle_of']);
        });
    }
}
