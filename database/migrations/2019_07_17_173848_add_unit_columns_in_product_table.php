<?php

use App\Models\History\ProductHistory;
use App\Models\Product;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUnitColumnsInProductTable extends Migration
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
            $table->string('unit_value')->nullable(true)->after('weight');
            $table->string('unit_name')->nullable(true)->after('weight');
            $table->string('unit')->nullable(true)->after('weight');
        });

        Schema::table($productModel->getTable(), function (Blueprint $table) {
            $table->dropColumn('weight');
        });

        Schema::table($productHistoryModel->getTable(), function (Blueprint $table) {
            $table->string('unit_value')->nullable(true)->after('weight');
            $table->string('unit_name')->nullable(true)->after('weight');
            $table->string('unit')->nullable(true)->after('weight');
        });

        Schema::table($productHistoryModel->getTable(), function (Blueprint $table) {
            $table->dropColumn('weight');
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
            $table->dropColumn(['unit_value', 'unit_name', 'unit']);
        });

        Schema::table($productModel->getTable(), function (Blueprint $table) {
            $table->float('weight')->nullable(true)->after('slug');
        });

        Schema::table($productHistoryModel->getTable(), function (Blueprint $table) {
            $table->dropColumn(['unit_value', 'unit_name', 'unit']);
        });

        Schema::table($productHistoryModel->getTable(), function (Blueprint $table) {
            $table->float('weight')->nullable(true)->after('slug');
        });
    }
}
