<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToTransporterTradingArea extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transporter_trading_areas', function (Blueprint $table) {
            $table->string('transporter_vehicle_id')->nullable(true);
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transporter_trading_areas', function (Blueprint $table) {
            $table->dropColumn('transporter_vehicle_id')->nullable(true);
            //
        });
    }
}
