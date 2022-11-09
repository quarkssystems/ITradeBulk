<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldToSalesOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales_orders', function (Blueprint $table) {
            $table->string('order_lead_time')->nullable(true);
            $table->string('order_lead_time_clock')->nullable(true);
            $table->string('order_lead_time_to_clock')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales_orders', function (Blueprint $table) {
            $table->dropColumn('order_lead_time')->nullable(true);
            $table->dropColumn('order_lead_time_clock')->nullable(true);
            $table->dropColumn('order_lead_time_to_clock')->nullable(true);
        });
    }
}