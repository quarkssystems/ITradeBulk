<?php

use App\Models\WalletTransactions;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTransactionStatusColumnInTransactionModel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $walletModel = new WalletTransactions();
        Schema::table($walletModel->getTable(), function (Blueprint $table) {
            $table->string('status')->nullable(true)->after('remarks');
            $table->string('transaction_type')->nullable(true)->after('remarks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $walletModel = new WalletTransactions();
        Schema::table($walletModel->getTable(), function (Blueprint $table) {
            $table->dropColumn(['status', 'transaction_type']);
        });
    }
}
