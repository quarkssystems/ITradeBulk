<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewFieldLogisticDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('logistic_details', function (Blueprint $table) {
            $table->string('vehicle_type')->nullable(true)->after('transport_type');
        });
        Schema::table('logistic_details_histories', function (Blueprint $table) {
            $table->string('vehicle_type')->nullable(true)->after('transport_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('logistic_details', function (Blueprint $table) {
             $table->dropColumn(['vehicle_type']);
        });
        Schema::table('logistic_details_histories', function (Blueprint $table) {
             $table->dropColumn(['vehicle_type']);
        });
    }
}
