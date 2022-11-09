<?php

use App\Models\LocationCountry;
use App\Models\LocationState;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocationStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('location_states', function (Blueprint $table) {
            $countryModel = new LocationCountry();
            $stateModel = new LocationState();

            $table->increments('id');
            $table->uuid('uuid');
            $table->index('uuid');
            $table->string('state_name');
            $table->enum('status', $stateModel->getStatuses())->nullable(true);

            /**
             * Parent Country Id
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
        Schema::dropIfExists('location_states');
    }
}
