<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToLogisticDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('logistic_details', function (Blueprint $table) {
            $table->string('truck_length')->nullable(true);
            $table->string('truck_width')->nullable(true);
            $table->string('truck_height')->nullable(true);
            $table->string('truck_payload')->nullable(true);
            $table->string('truck_max_pallets')->nullable(true);
            $table->string('trailer_length')->nullable(true);
            $table->string('trailer_width')->nullable(true);
            $table->string('trailer_height')->nullable(true);
            $table->string('trailer_payload')->nullable(true);
            $table->string('trailer_max_pallets')->nullable(true);
            $table->string('body_volumn')->nullable(true);
            $table->string('combine_payload')->nullable(true);
            $table->string('combine_pallets')->nullable(true);
            $table->string('trading_area')->nullable(true);
            
        });

        Schema::table('logistic_details_histories', function (Blueprint $table) {
            $table->string('truck_length')->nullable(true);
            $table->string('truck_width')->nullable(true);
            $table->string('truck_height')->nullable(true);
            $table->string('truck_payload')->nullable(true);
            $table->string('truck_max_pallets')->nullable(true);
            $table->string('trailer_length')->nullable(true);
            $table->string('trailer_width')->nullable(true);
            $table->string('trailer_height')->nullable(true);
            $table->string('trailer_payload')->nullable(true);
            $table->string('trailer_max_pallets')->nullable(true);
            $table->string('body_volumn')->nullable(true);
            $table->string('combine_payload')->nullable(true);
            $table->string('combine_pallets')->nullable(true);
            $table->string('trading_area')->nullable(true);
            
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
            $table->dropColumn('truck_length');
            $table->dropColumn('truck_width');
            $table->dropColumn('truck_height');
            $table->dropColumn('truck_payload');
            $table->dropColumn('truck_max_pallets');
            $table->dropColumn('trailer_length');
            $table->dropColumn('trailer_width');
            $table->dropColumn('trailer_height');
            $table->dropColumn('trailer_payload');
            $table->dropColumn('trailer_max_pallets');
            $table->dropColumn('body_volumn');
            $table->dropColumn('combine_payload');
            $table->dropColumn('combine_pallets');
            $table->dropColumn('trading_area');
        });

        Schema::table('logistic_details_histories', function (Blueprint $table) {
            $table->dropColumn('truck_length');
            $table->dropColumn('truck_width');
            $table->dropColumn('truck_height');
            $table->dropColumn('truck_payload');
            $table->dropColumn('truck_max_pallets');
            $table->dropColumn('trailer_length');
            $table->dropColumn('trailer_width');
            $table->dropColumn('trailer_height');
            $table->dropColumn('trailer_payload');
            $table->dropColumn('trailer_max_pallets');
            $table->dropColumn('body_volumn');
            $table->dropColumn('combine_payload');
            $table->dropColumn('combine_pallets');
            $table->dropColumn('trading_area');
        });
    }
}