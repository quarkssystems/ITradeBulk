<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSalesOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // \DB::raw('ALTER TABLE `sales_orders` ADD `order_lead_time` DATE NULL AFTER `updated_at`');
        // \DB::raw('ALTER TABLE `sales_order_histories` ADD `order_lead_time` DATE NULL AFTER `updated_at`');
        // \DB::raw('ALTER TABLE `sales_orders` ADD `order_lead_time_clock` VARCHAR(255) NULL AFTER `order_lead_time`');
        // \DB::raw('ALTER TABLE `sales_orders` ADD `order_lead_time_to_clock` VARCHAR(255) NULL AFTER `order_lead_time_clock`');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}