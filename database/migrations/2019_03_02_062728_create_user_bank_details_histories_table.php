<?php

use App\Models\BankBranch;
use App\Models\BankMaster;
use App\Models\UserBankDetails;
use App\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserBankDetailsHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_bank_details_histories', function (Blueprint $table) {
            $bankMasterModel = new BankMaster();
            $bankBranchModel = new BankBranch();
            $userModel = new User();
            $userBankDetailsModel = new UserBankDetails();

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

            /**
             * User who has updated this record
             */
            $table->uuid('updated_by');
            $table->foreign('updated_by')->references('uuid')->on($userModel->getTable());

            $table->text('update_note')->nullable(true);

            $table->uuid('history_of');
            $table->foreign('history_of')->references('uuid')->on($userBankDetailsModel->getTable());
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
        Schema::dropIfExists('user_bank_details_histories');
    }
}
