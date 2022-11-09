<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\VehicleCapacity;

class AddVehicleCapacityToLogisticDetailsHistories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('logistic_details_histories', function (Blueprint $table) {
            $vehicleCapacityModel  = new VehicleCapacity();

            $table->uuid('vehicle_capacity_id')->nullable(true)->after('country_id');
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
        Schema::table('logistic_details_histories', function (Blueprint $table) {
            $table->dropForeign('logistic_details_histories_vehicle_capacity_id_foreign');
            $table->dropIndex('logistic_details_histories_vehicle_capacity_id_index');
            $table->dropColumn(['vehicle_capacity_id']);
        });
    }
}
