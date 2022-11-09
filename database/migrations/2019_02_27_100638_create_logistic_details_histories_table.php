<?php

use App\Models\LocationCity;
use App\Models\LocationCountry;
use App\Models\LocationState;
use App\Models\LocationZipcode;
use App\Models\LogisticDetails;
use App\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogisticDetailsHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logistic_details_histories', function (Blueprint $table) {
            $zipcodeModel = new LocationZipcode();
            $cityModel = new LocationCity();
            $stateModel = new LocationState();
            $countryModel = new LocationCountry();
            $userModel = new User();
            $logisticDetailModel = new LogisticDetails();

            $table->increments('id');
            $table->uuid('uuid');
            $table->index('uuid');

            $table->string('phone')->nullable(true);
            $table->string('driving_licence')->nullable(true);
            $table->string('transport_type')->nullable(true);
            $table->string('transport_capacity')->nullable(true);
            $table->text('pallets_available')->nullable(true);
            $table->string('pallets_required')->nullable(true);
            $table->string('work_type')->nullable(true);
            $table->string('availability')->nullable(true);

            $table->uuid('user_id');
            $table->foreign('user_id')->references('uuid')->on($userModel->getTable());

            $table->string('address1')->nullable(true);
            $table->string('address2')->nullable(true);

            /**
             * Zipcode Id
             */
            $table->uuid('zipcode_id');
            $table->foreign('zipcode_id')->references('uuid')->on($zipcodeModel->getTable());

            /**
             * City Id
             */
            $table->uuid('city_id');
            $table->foreign('city_id')->references('uuid')->on($cityModel->getTable());

            /**
             * State Id
             */
            $table->uuid('state_id');
            $table->foreign('state_id')->references('uuid')->on($stateModel->getTable());

            /**
             * Country Id
             */
            $table->uuid('country_id');
            $table->foreign('country_id')->references('uuid')->on($countryModel->getTable());

            /**
             * User who has updated this record
             */
            $table->uuid('updated_by');
            $table->foreign('updated_by')->references('uuid')->on($userModel->getTable());

            $table->text('update_note')->nullable(true);

            $table->uuid('history_of');
            $table->foreign('history_of')->references('uuid')->on($logisticDetailModel->getTable());
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
        Schema::dropIfExists('logistic_details_histories');
    }
}
