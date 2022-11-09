<?php

use App\Models\BankBranch;
use App\Models\BankMaster;
use App\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserBankDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_bank_details', function (Blueprint $table) {
            $bankMasterModel = new BankMaster();
            $bankBranchModel = new BankBranch();
            $userModel = new User();

            $table->increments('id');
            $table->uuid('uuid');
            $table->index('uuid');

            $table->string('bank_account_name')->nullable(true);
            $table->string('bank_account_number')->nullable(true);
            $table->string('bank_account_type')->nullable(true);

            $table->uuid('bank_id')->nullable(true);
            $table->foreign('bank_id')->references('uuid')->on($bankMasterModel->getTable());

            $table->uuid('bank_branch_id')->nullable(true);
            $table->foreign('bank_branch_id')->references('uuid')->on($bankBranchModel->getTable());

            $table->uuid('user_id')->nullable(true);
            $table->foreign('user_id')->references('uuid')->on($userModel->getTable());

            $table->string('account_confirmation_letter_file')->nullable(true);
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
        Schema::dropIfExists('user_bank_details');
    }
}
