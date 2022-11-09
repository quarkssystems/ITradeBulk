<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToSupplierItemInventory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supplier_item_inventories', function (Blueprint $table) {
            $table->string('store_id')->nullable(true); //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('supplier_item_inventories', function (Blueprint $table) {
            $table->dropColumn('store_id')->nullable(true); //
        });
    }
}