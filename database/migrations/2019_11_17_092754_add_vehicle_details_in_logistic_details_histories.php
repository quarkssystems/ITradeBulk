<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVehicleDetailsInLogisticDetailsHistories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('logistic_details_histories', function (Blueprint $table) {
            $table->string('vehicle_make')->nullable(true)->after('country_id');
            $table->string('vehicle_registration_number')->nullable(true)->after('country_id');
            $table->string('vehicle_model')->nullable(true)->after('country_id');
            $table->string('vin_number')->nullable(true)->after('country_id');
            $table->string('vehicle_color')->nullable(true)->after('country_id');
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
            $table->dropColumn(['vehicle_make', 'vehicle_registration_number', 'vehicle_model', 'vin_number','vehicle_color']);
        });
    }
}
