<?php

use App\Models\History\LogisticDetailsHistory;
use App\Models\LogisticDetails;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPalletDepositeColumnInLogisticDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $logisticDetailsModel = new LogisticDetails();
        $logisticDetailsHistoryModel = new LogisticDetailsHistory();
        Schema::table($logisticDetailsModel->getTable(), function (Blueprint $table) {
            $table->string('pallets_deposit')->nullable(true)->after('pallets_required');
        });
        Schema::table($logisticDetailsHistoryModel->getTable(), function (Blueprint $table) {
            $table->string('pallets_deposit')->nullable(true)->after('pallets_required');
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
            //
        });
    }
}
