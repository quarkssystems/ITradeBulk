<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToSupplierItemInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supplier_item_inventories', function (Blueprint $table) {
            $table->string('stoc_vat')->nullable();
            $table->double('cost')->nullable();
            $table->double('markup')->nullable();
            $table->double('autoprice')->nullable();
            $table->integer('min_order_quantity')->nullable();
            $table->date('stock_expiry_date')->nullable();
            $table->boolean('audited')->default(0);
            $table->uuid('promotion_id')->nullable(true);
            $table->foreign('promotion_id')->references('uuid')->on('promotions');
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
            $table->dropColumn('stoc_vat');
            $table->dropColumn('cost');
            $table->dropColumn('markup');
            $table->dropColumn('autoprice');
            $table->dropColumn('min_order_quantity');
            $table->dropColumn('stock_expiry_date');
            $table->dropColumn('audited');
            $table->dropForeign('supplier_item_inventories_promotion_id_foreign');
            $table->dropColumn('promotion_id');
        });
    }
}
