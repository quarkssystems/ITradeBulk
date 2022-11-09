<?php

use App\Models\BankMaster;
use App\Models\LocationCity;
use App\Models\LocationCountry;
use App\Models\LocationState;
use App\Models\LocationZipcode;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_branches', function (Blueprint $table) {
            $bankMasterModel = new BankMaster();
            $zipcodeModel = new LocationZipcode();
            $cityModel = new LocationCity();
            $stateModel = new LocationState();
            $countryModel = new LocationCountry();

            $table->increments('id');
            $table->uuid('uuid');
            $table->index('uuid');

            $table->string('branch_name')->nullable(true);
            $table->string('branch_code')->nullable(true);
            $table->string('swift_code')->nullable(true);

            $table->uuid('bank_master_id')->nullable(true);
            $table->foreign('bank_master_id')->references('uuid')->on($bankMasterModel->getTable());

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
        Schema::dropIfExists('bank_branches');
    }
}
