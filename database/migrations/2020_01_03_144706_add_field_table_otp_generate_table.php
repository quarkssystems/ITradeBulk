<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldTableOtpGenerateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('otp_generate', function (Blueprint $table) {
            //
            $table->string('status')->nullable(true);
            $table->integer('attempt')->default(0);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('otp_generate', function (Blueprint $table) {
            //
             $table->dropColumn(['status', 'attempt']);
        });
    }
}
