<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldLogisticDetailsHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('logistic_details_histories', function (Blueprint $table) {
            $table->double('pallet_capacity_standard')->after("transport_capacity")->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('logistic_details_histories', function (Blueprint $table) {
            $table->dropColumn(['pallet_capacity_standard']);
        });
    }
}
