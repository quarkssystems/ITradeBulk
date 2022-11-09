<?php

use App\Models\LocationCity;
use App\Models\LocationCountry;
use App\Models\LocationState;
use App\Models\LocationZipcode;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocationZipcodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('location_zipcodes', function (Blueprint $table) {
            $stateModel = new LocationState();
            $countryModel = new LocationCountry();
            $cityModel = new LocationCity();
            $zipcodeModel = new LocationZipcode();

            $table->increments('id');
            $table->uuid('uuid');
            $table->index('uuid');

            $table->string('zipcode_name');
            $table->string('zipcode');
            $table->enum('status', $zipcodeModel->getStatuses())->nullable(true);

            /**
             * Parent City
             */
            $table->uuid('city_id');
            $table->foreign('city_id')->references("uuid")->on($cityModel->getTable());

            /**
             * Parent state
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
        Schema::dropIfExists('location_zipcodes');
    }
}
