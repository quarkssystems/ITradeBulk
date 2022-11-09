<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToLogisticDetailsHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('logistic_details_histories', function (Blueprint $table) {
            $table->string('name')->nullable();
            $table->enum('status', ["0", "1"])->default("1");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('logistic_details_histories', function (Blueprint $table) {
            $table->dropColumn('name')->nullable();
            $table->dropColumn('status')->nullable(true);
        });
    }
}
