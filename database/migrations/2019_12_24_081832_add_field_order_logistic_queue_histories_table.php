<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldOrderLogisticQueueHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_logistic_queue_histories', function (Blueprint $table) {
            
            $table->uuid('updated_by');
            $table->foreign('updated_by')->references('uuid')->on('users');

            $table->text('update_note')->nullable(true);

            $table->uuid('history_of');
            $table->foreign('history_of')->references('uuid')->on('order_logistic_queue');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_logistic_queue_histories', function (Blueprint $table) {
             $table->dropColumn(['updated_by', 'update_note', 'history_of']);
        });


    }
}
