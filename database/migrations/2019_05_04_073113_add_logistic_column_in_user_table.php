<?php

use App\Models\History\UserHistory;
use App\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLogisticColumnInUserTable extends Migration
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
            $table->string('logistic_type')->nullable(true)->after('password');
            $table->string('transporter_name')->nullable(true)->after('password');
        });
        Schema::table($userHistoryModel->getTable(), function (Blueprint $table) {
            $table->string('logistic_type')->nullable(true)->after('password');
            $table->string('transporter_name')->nullable(true)->after('password');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user', function (Blueprint $table) {
            //
        });
    }
}
