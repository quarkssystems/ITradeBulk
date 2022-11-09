<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouriersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('couriers', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('default_courier', ['0', '1'])->default('0');
            $table->string('delivery_option')->nullable(true);
            $table->string('upload_option_pic')->nullable(true);
            $table->string('std_lead_time')->nullable(true);
            $table->string('courier_lead_time')->nullable(true);
            $table->string('delivery_markup')->nullable(true);
            $table->string('min_delivery_fee')->nullable(true);
            $table->string('status')->nullable(true);
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
        Schema::dropIfExists('couriers');
    }
}
