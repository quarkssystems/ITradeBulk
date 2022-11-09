<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUpdatedLocationSalesorderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('sales_orders', function (Blueprint $table) {
            $table->decimal("vendor_updated_longitude", 11,8)->nullable(true)->after('approx_pallet_capacity');
            $table->decimal("vendor_updated_latitude", 10,8)->nullable(true)->after('vendor_updated_longitude');  

            $table->decimal("supplier_updated_longitude", 11,8)->nullable(true)->after('vendor_updated_latitude');
            $table->decimal("supplier_updated_latitude", 10,8)->nullable(true)->after('supplier_updated_longitude');  

            $table->decimal("driver_updated_longitude", 11,8)->nullable(true)->after('supplier_updated_latitude');
            $table->decimal("driver_updated_latitude", 10,8)->nullable(true)->after('driver_updated_longitude');  
             
        });

         Schema::table('sales_order_histories', function (Blueprint $table) {
          $table->decimal("vendor_updated_longitude", 11,8)->nullable(true)->after('approx_pallet_capacity');
            $table->decimal("vendor_updated_latitude", 10,8)->nullable(true)->after('vendor_updated_longitude');  

            $table->decimal("supplier_updated_longitude", 11,8)->nullable(true)->after('vendor_updated_latitude');
            $table->decimal("supplier_updated_latitude", 10,8)->nullable(true)->after('supplier_updated_longitude');  

            $table->decimal("driver_updated_longitude", 11,8)->nullable(true)->after('supplier_updated_latitude');
            $table->decimal("driver_updated_latitude", 10,8)->nullable(true)->after('driver_updated_longitude');  

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
             $table->dropColumn(['vendor_updated_longitude', 'vendor_updated_latitude', 'supplier_updated_longitude', 'supplier_updated_latitude', 'driver_updated_longitude', 'driver_updated_latitude']);
        });
        Schema::table('sales_order_histories', function (Blueprint $table) {
             $table->dropColumn(['vendor_updated_longitude', 'vendor_updated_latitude', 'supplier_updated_longitude', 'supplier_updated_latitude', 'driver_updated_longitude', 'driver_updated_latitude']);
        });
    }
}
