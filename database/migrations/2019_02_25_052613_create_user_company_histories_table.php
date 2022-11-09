<?php

use App\Models\LocationCity;
use App\Models\LocationCountry;
use App\Models\LocationState;
use App\Models\LocationZipcode;
use App\Models\UserCompany;
use App\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserCompanyHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_company_histories', function (Blueprint $table) {
            $zipcodeModel = new LocationZipcode();
            $cityModel = new LocationCity();
            $stateModel = new LocationState();
            $countryModel = new LocationCountry();
            $userModel = new User();
            $userCompanyModel = new UserCompany();

            $table->increments('id');
            $table->uuid('uuid');
            $table->index('uuid');

            $table->string('legal_name')->nullable(true);
            $table->string('trading_name')->nullable(true);
            $table->string('business_type')->nullable(true);
            $table->text('product_service_offered')->nullable(true);
            $table->string('representative_first_name')->nullable(true);
            $table->string('representative_last_name')->nullable(true);
            $table->string('email')->nullable(true);
            $table->string('phone')->nullable(true);
            $table->string('website')->nullable(true);
            $table->string('founding_year')->nullable(true);
            $table->string('company_size')->nullable(true);
            $table->string('audience')->nullable(true);
            $table->string('geographical_target')->nullable(true);

            $table->uuid('owner_user_id');
            $table->foreign('owner_user_id')->references('uuid')->on($userModel->getTable());

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
            $table->foreign('history_of')->references('uuid')->on($userCompanyModel->getTable());
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
        Schema::dropIfExists('user_company_histories');
    }
}
