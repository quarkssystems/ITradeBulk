<?php

use App\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWalletTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $userModel  = new User();

            $table->increments('id');
            $table->uuid('uuid');
            $table->index('uuid');

            $table->uuid('user_id')->nullable(true);
            $table->foreign('user_id')->references('uuid')->on($userModel->getTable());

            $table->bigInteger('credit_amount')->nullable(true);
            $table->bigInteger('debit_amount')->nullable(true);
            $table->string('remarks')->nullable(true);

            $table->softDeletes();
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
        Schema::dropIfExists('wallet_transactions');
    }
}
