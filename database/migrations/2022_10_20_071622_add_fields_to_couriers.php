<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToCouriers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('couriers', function (Blueprint $table) {
            $table->string('name')->nullable(true);
            $table->string('account')->nullable(true);
            $table->string('link_to_portal')->nullable(true);
            $table->string('address')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('couriers', function (Blueprint $table) {
            $table->dropColumn('name')->nullable(true);
            $table->dropColumn('account')->nullable(true);
            $table->dropColumn('link_to_portal')->nullable(true);
            $table->dropColumn('address')->nullable(true);
        });
    }
}
