<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\VehicleCapacity;

class AddVehicleCapacityToDeliveryVehicleMasterHistories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('delivery_vehicle_master_histories', function (Blueprint $table) {
            $vehicleCapacityModel  = new VehicleCapacity();

            $table->string('transport_type')->nullable(true);
            $table->uuid('vehicle_capacity_id')->nullable(true);
            $table->index('vehicle_capacity_id');
            $table->foreign('vehicle_capacity_id')->references('uuid')->on($vehicleCapacityModel->getTable());
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
            $table->dropForeign('delivery_vehicle_master_histories_vehicle_capacity_id_foreign');
            $table->dropIndex('delivery_vehicle_master_histories_vehicle_capacity_id_index');
            $table->dropColumn(['vehicle_capacity_id','transport_type']);
        });
    }
}
