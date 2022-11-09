<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToDeliveryVehicleMastersHistories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('delivery_vehicle_master_histories', function (Blueprint $table) {
            $table->string('truck_length')->nullable(true);
            $table->string('truck_width')->nullable(true);
            $table->string('truck_height')->nullable(true);
            $table->string('truck_payload')->nullable(true);
            $table->string('truck_max_pallets')->nullable(true);
            $table->string('trailer_length')->nullable(true);
            $table->string('trailer_width')->nullable(true);
            $table->string('trailer_height')->nullable(true);
            $table->string('trailer_payload')->nullable(true);
            $table->string('trailer_max_pallets')->nullable(true);
            $table->string('body_volumn')->nullable(true);
            $table->string('combine_payload')->nullable(true);
            $table->string('combine_pallets')->nullable(true);
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
            $table->dropColumn('truck_length')->nullable(true);
            $table->dropColumn('truck_width')->nullable(true);
            $table->dropColumn('truck_height')->nullable(true);
            $table->dropColumn('truck_payload')->nullable(true);
            $table->dropColumn('truck_max_pallets')->nullable(true);
            $table->dropColumn('trailer_length')->nullable(true);
            $table->dropColumn('trailer_width')->nullable(true);
            $table->dropColumn('trailer_height')->nullable(true);
            $table->dropColumn('trailer_payload')->nullable(true);
            $table->dropColumn('trailer_max_pallets')->nullable(true);
            $table->dropColumn('body_volumn')->nullable(true);
            $table->dropColumn('combine_payload')->nullable(true);
            $table->dropColumn('combine_pallets')->nullable(true);
        });
    }
}
