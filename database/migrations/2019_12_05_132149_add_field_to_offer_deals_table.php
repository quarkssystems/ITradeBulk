<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldToOfferDealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('offer_deals', function (Blueprint $table) {
         //
            $table->uuid('user_id')->nullable(true)->after('offer_id');
            $table->timestamp('startdate')->nullable(true)->after('user_id');
            $table->timestamp('enddate')->nullable(true)->after('user_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offer_deals', function (Blueprint $table) {
            //
             $table->dropColumn(['user_id', 'startdate', 'enddate']);
        });

    }
}
