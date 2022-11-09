<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehicleCapacitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_capacities', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->index('uuid');

            $table->string('name')->nullable(true);
            $table->double('max_weight')->nullable(true);
            $table->double('load_space_volume')->nullable(true);
            $table->double('load_floor_length')->nullable(true);
            $table->double('load_floor_width')->nullable(true);
            $table->double('side_load_height')->nullable(true);
            $table->double('side_load_length')->nullable(true);
            $table->double('pallet_capacity_standard')->nullable(true);
            $table->double('pallet_capacity_euro')->nullable(true);
            $table->double('full_pallet_dimension_width')->nullable(true);
            $table->double('full_pallet_dimension_depth')->nullable(true);
            $table->double('full_pallet_dimension_height')->nullable(true);
            $table->double('full_pallet_dimension_max_weight')->nullable(true);

            $table->double('half_pallet_dimension_width')->nullable(true);
            $table->double('half_pallet_dimension_depth')->nullable(true);
            $table->double('half_pallet_dimension_height')->nullable(true);
            $table->double('half_pallet_dimension_max_weight')->nullable(true);

            $table->double('quarter_pallet_dimension_width')->nullable(true);
            $table->double('quarter_pallet_dimension_depth')->nullable(true);
            $table->double('quarter_pallet_dimension_height')->nullable(true);
            $table->double('quarter_pallet_dimension_max_weight')->nullable(true);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicle_capacities');
    }
}
