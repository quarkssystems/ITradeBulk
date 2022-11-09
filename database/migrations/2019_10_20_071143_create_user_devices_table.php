<?php

use App\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_devices', function (Blueprint $table) {
            $userModel = new User();

            $table->increments('id');

            $table->uuid('uuid');
            $table->index('uuid');

            $table->uuid('user_id')->nullable(true);
            $table->foreign('user_id')->references('uuid')->on($userModel->getTable());


            $table->string('device_id')->nullable();
            $table->text('device_token')->nullable();
            $table->enum('device_type',['Android','iOS']);

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
        Schema::dropIfExists('user_devices');
    }
}
