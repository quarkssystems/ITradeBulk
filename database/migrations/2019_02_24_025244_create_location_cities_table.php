<?php

use App\Models\LocationCity;
use App\Models\LocationCountry;
use App\Models\LocationState;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocationCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('location_cities', function (Blueprint $table) {
            $stateModel = new LocationState();
            $countryModel = new LocationCountry();
            $cityModel = new LocationCity();

            $table->increments('id');
            $table->uuid('uuid');
            $table->index('uuid');

            $table->string('city_name');
            $table->enum('status', $cityModel->getStatuses())->nullable(true);

            /**
             * Parent State
             */
            $table->uuid('state_id');
            $table->foreign('state_id')->references("uuid")->on($stateModel->getTable());

            /**
             * Parent Country
             */
            $table->uuid('country_id');
            $table->foreign('country_id')->references("uuid")->on($countryModel->getTable());
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
        Schema::dropIfExists('location_cities');
    }
}
