<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldSalesOrdersHistoriesTable extends Migration
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
                $table->timestamp('placed_date')->nullable(true)->after('delivery_type');
            $table->timestamp('packed_date')->nullable(true)->after('delivery_type');
            $table->timestamp('dispatched_date')->nullable(true)->after('delivery_type');
            $table->timestamp('delivered_date')->nullable(true)->after('delivery_type');
            $table->timestamp('cancelled_date')->nullable(true)->after('delivery_type');
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
             $table->dropColumn(['placed_date', 'packed_date','dispatched_date','delivered_date','cancelled_date']);
        });
    }
}
