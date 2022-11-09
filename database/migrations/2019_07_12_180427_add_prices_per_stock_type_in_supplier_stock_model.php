<?php

use App\Models\SupplierItemInventory;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPricesPerStockTypeInSupplierStockModel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $supplierItemInventoryModel = new SupplierItemInventory();
        Schema::table($supplierItemInventoryModel->getTable(), function (Blueprint $table) {
            $table->double('single_price')->nullable(true)->default(0)->after('single');
            $table->double('shrink_price')->nullable(true)->default(0)->after('shrink');
            $table->double('case_price')->nullable(true)->default(0)->after('case');
            $table->double('pallet_price')->nullable(true)->default(0)->after('pallet');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $supplierItemInventoryModel = new SupplierItemInventory();
        Schema::table($supplierItemInventoryModel->getTable(), function (Blueprint $table) {
            $table->dropColumn(['single_price', 'shrink_price', 'case_price', 'pallet_price']);
        });
    }
}
