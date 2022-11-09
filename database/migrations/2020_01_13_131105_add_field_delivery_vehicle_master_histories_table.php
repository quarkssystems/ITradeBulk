<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldDeliveryVehicleMasterHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('delivery_vehicle_master_histories', function (Blueprint $table) {
            //
            $table->double('pallet_capacity_standard')->after("price_per_km")->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('delivery_vehicle_master_histories', function (Blueprint $table) {
             $table->dropColumn(['pallet_capacity_standard']);
        });
    }
}
