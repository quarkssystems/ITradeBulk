<?php

use App\Models\LocationCountry;
use App\Models\LocationState;
use App\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocationStateHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('location_state_histories', function (Blueprint $table) {
            $countryModel = new LocationCountry();
            $userModel = new User();
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

            /**
             * User who has updated this record
             */
            $table->uuid('updated_by');
            $table->foreign('updated_by')->references('uuid')->on($userModel->getTable());

            $table->text('update_note')->nullable(true);

            $table->uuid('history_of');
            $table->foreign('history_of')->references('uuid')->on($stateModel->getTable());
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
        Schema::dropIfExists('location_state_histories');
    }
}
