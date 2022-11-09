<?php

use App\Models\DeliveryVehicleMaster;
use App\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveryVehicleMasterHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_vehicle_master_histories', function (Blueprint $table) {
            $userModel = new User();
            $deliveryVehicleMasterModel = new DeliveryVehicleMaster();

            $table->increments('id');
            $table->uuid('uuid');
            $table->index('uuid');

            $table->string('vehicle_type')->nullable(true);
            $table->double('capacity')->nullable(true);
            $table->double('price_per_km')->nullable(true);

            $table->softDeletes();

            /**
            * User who has updated this record
            */
            $table->uuid('updated_by');
            $table->foreign('updated_by')->references('uuid')->on($userModel->getTable());

            $table->text('update_note')->nullable(true);

            $table->uuid('history_of');
            $table->foreign('history_of')->references('uuid')->on($deliveryVehicleMasterModel->getTable());

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
        Schema::dropIfExists('delivery_vehicle_master_histories');
    }
}
