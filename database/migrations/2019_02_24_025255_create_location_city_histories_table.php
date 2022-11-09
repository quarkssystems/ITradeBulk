<?php

use App\Models\LocationCity;
use App\Models\LocationCountry;
use App\Models\LocationState;
use App\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocationCityHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('location_city_histories', function (Blueprint $table) {
            $stateModel = new LocationState();
            $countryModel = new LocationCountry();
            $cityModel = new LocationCity();
            $userModel = new User();

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

            /**
             * User who has updated this record
             */
            $table->uuid('updated_by');
            $table->foreign('updated_by')->references('uuid')->on($userModel->getTable());

            $table->text('update_note')->nullable(true);

            $table->uuid('history_of');
            $table->foreign('history_of')->references('uuid')->on($cityModel->getTable());
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
        Schema::dropIfExists('location_city_histories');
    }
}
