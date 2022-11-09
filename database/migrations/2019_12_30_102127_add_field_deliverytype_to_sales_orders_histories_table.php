<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldDeliverytypeToSalesOrdersHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales_order_histories', function (Blueprint $table) {
            //
            $table->string('delivery_type')->nullable(true)->after('payment_status');
             $table->double('tax_amount')->nullable(true)->after('discount_amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales_order_histories', function (Blueprint $table) {
            //
            $table->dropColumn('delivery_type','tax_amount');
        });
    }
}
