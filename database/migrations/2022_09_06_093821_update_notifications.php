<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateNotifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // \DB::raw('ALTER TABLE `notifications` ADD `accept_or_reject` VARCHAR(255) NULL AFTER `status`');
        // \DB::raw('ALTER TABLE `notifications` ADD `reject_reason` VARCHAR(255) NULL AFTER `status`');
        // \DB::raw('ALTER TABLE `notifications` ADD `type` VARCHAR(255) NULL AFTER `status`');

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