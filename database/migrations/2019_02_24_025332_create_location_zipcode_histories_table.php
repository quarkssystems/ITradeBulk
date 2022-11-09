<?php

use App\Models\LocationCity;
use App\Models\LocationCountry;
use App\Models\LocationState;
use App\Models\LocationZipcode;
use App\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocationZipcodeHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('location_zipcode_histories', function (Blueprint $table) {
            $stateModel = new LocationState();
            $countryModel = new LocationCountry();
            $cityModel = new LocationCity();
            $zipcodeModel = new LocationZipcode();
            $userModel = new User();

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

            /**
             * User who has updated this record
             */
            $table->uuid('updated_by');
            $table->foreign('updated_by')->references('uuid')->on($userModel->getTable());

            $table->text('update_note')->nullable(true);

            $table->uuid('history_of');
            $table->foreign('history_of')->references('uuid')->on($zipcodeModel->getTable());
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
        Schema::dropIfExists('location_zipcode_histories');
    }
}
