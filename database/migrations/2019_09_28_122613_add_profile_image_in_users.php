<?php

use App\Models\History\UserHistory;
use App\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProfileImageInUsers extends Migration
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
            $table->string('image')->nullable(true);
        });

        Schema::table($userHistoryModel->getTable(), function (Blueprint $table) {
            $table->string('image')->nullable(true);
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
            $table->dropColumn('image');
        });

        Schema::table($userHistoryModel->getTable(), function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
}
