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
            $table->string('name')->nullable();
            $table->enum('status', ["0", "1"])->default("1");
            $table->enum('is_available', ["0", "1"])->default("1");
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
            $table->dropColumn('name')->nullable(true);
            $table->dropColumn('status')->nullable(true);
            $table->dropColumn('is_available')->nullable(true);
        });
    }
}
