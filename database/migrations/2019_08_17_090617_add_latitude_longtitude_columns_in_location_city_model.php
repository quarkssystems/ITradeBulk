<?php

use App\Models\History\LocationCityHistory;
use App\Models\LocationCity;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLatitudeLongtitudeColumnsInLocationCityModel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $cityModel = new LocationCity();
        $cityHistoryModel = new LocationCityHistory();
        Schema::table($cityModel->getTable(), function (Blueprint $table) {
            $table->decimal("longitude", 11,8)->after("status");
            $table->decimal("latitude", 10,8)->after("status");
        });

        Schema::table($cityHistoryModel->getTable(), function (Blueprint $table) {
            $table->decimal("longitude", 11,8)->after("status");
            $table->decimal("latitude", 10,8)->after("status");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $cityModel = new LocationCity();
        $cityHistoryModel = new LocationCityHistory();
        Schema::table($cityModel->getTable(), function (Blueprint $table) {
            $table->dropColumn(['longitude', 'latitude']);
        });

        Schema::table($cityHistoryModel->getTable(), function (Blueprint $table) {
            $table->dropColumn(['longitude', 'latitude']);
        });
    }
}
