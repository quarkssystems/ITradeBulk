<?php

use App\Models\History\UserHistory;
use App\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLatitudeLongtitudeColumnsInUserModel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $userModel = new User();
        $userHistoryModel = new UserHistory();
        Schema::table($userModel->getTable(), function (Blueprint $table) {
            $table->decimal("longitude", 11,8)->after("status");
            $table->decimal("latitude", 10,8)->after("status");
        });

        Schema::table($userHistoryModel->getTable(), function (Blueprint $table) {
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
        $userModel = new User();
        $userHistoryModel = new UserHistory();
        Schema::table($userModel->getTable(), function (Blueprint $table) {
            $table->dropColumn(['longitude', 'latitude']);
        });

        Schema::table($userHistoryModel->getTable(), function (Blueprint $table) {
            $table->dropColumn(['longitude', 'latitude']);
        });
    }
}
