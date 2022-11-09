<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldToNotifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->string('accept_or_reject')->nullable(true); //
            $table->string('reject_reason')->nullable(true); //
            $table->string('type')->nullable(true); //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn('accept_or_reject')->nullable(true); //
            $table->dropColumn('reject_reason')->nullable(true); //
            $table->dropColumn('type')->nullable(true); //
        });
    }
}