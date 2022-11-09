<?php

use App\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableOtpGenerateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('otp_generate', function (Blueprint $table) {
            //
            $userModel = new User();
            $table->increments('id');
            $table->uuid('uuid');
            $table->index('uuid');

            $table->uuid('sales_id')->nullable(true);
            $table->foreign('sales_id')->references('uuid')->on('sales_orders');

            $table->uuid('sender_id')->nullable(true);
            $table->foreign('sender_id')->references('uuid')->on($userModel->getTable());
            $table->uuid('receiver_id')->nullable(true);
            $table->foreign('receiver_id')->references('uuid')->on($userModel->getTable());
            $table->string('otp')->nullable(true);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('otp_generate', function (Blueprint $table) {
            //
             Schema::dropIfExists('otp_generate');
        });
    }
}
