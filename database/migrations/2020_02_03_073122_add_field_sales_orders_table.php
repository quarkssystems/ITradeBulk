<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldSalesOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales_orders', function (Blueprint $table) {
             $table->string('total_weight')->nullable(true)->after('delivery_type');
             $table->string('deliver_vehicle')->nullable(true)->after('total_weight');
             $table->string('distance')->nullable(true)->after('deliver_vehicle');
             $table->double('approx_pallet_capacity')->after("distance")->nullable(true);
        });

         Schema::table('sales_order_histories', function (Blueprint $table) {
             $table->string('total_weight')->nullable(true)->after('delivery_type');
             $table->string('deliver_vehicle')->nullable(true)->after('total_weight');
             $table->string('distance')->nullable(true)->after('deliver_vehicle');
             $table->double('approx_pallet_capacity')->after("distance")->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales_orders', function (Blueprint $table) {
             $table->dropColumn(['total_weight', 'deliver_vehicle', 'distance', 'approx_pallet_capacity']);
        });
        Schema::table('sales_order_histories', function (Blueprint $table) {
             $table->dropColumn(['total_weight', 'deliver_vehicle', 'distance', 'approx_pallet_capacity']);
        });
    }
}
