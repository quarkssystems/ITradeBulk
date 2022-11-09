<?php

use App\Models\History\RequestQuoteHistory;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUUIDColumnInRequestQuoteModel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $requestQuoteHistoryModel = new RequestQuoteHistory();
        Schema::table($requestQuoteHistoryModel->getTable(), function (Blueprint $table) {
            $table->uuid('uuid')->after('id');
            $table->index('uuid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//
//        Schema::table('request_quote_model', function (Blueprint $table) {
//            //
//        });
    }
}
