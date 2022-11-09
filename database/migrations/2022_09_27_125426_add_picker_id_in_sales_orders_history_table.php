<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\User;

class AddPickerIdInSalesOrdersHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales_order_histories', function (Blueprint $table) {
            $userModel = new User();
            $table->uuid('picker_id')->nullable(true);
            $table->foreign('picker_id')->references('uuid')->on($userModel->getTable());

            $table->uuid('dispatcher_id')->nullable(true);
            $table->foreign('dispatcher_id')->references('uuid')->on($userModel->getTable());
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales_order_histories', function (Blueprint $table) {
            $table->dropColumn('picker_id');
            $table->dropColumn('dispatcher_id');
        });
    }
}
