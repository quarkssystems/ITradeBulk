<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminUserBankDetailsRequest;
use App\Models\BankBranch;
use App\Models\BankMaster;
use App\Models\UserBankDetails;
use App\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminLogisticBankDetailsController extends AdminUserBankDetailsController
{
    public $userRole = 'LOGISTIC';
    public $route = 'admin.logistic-bank-details';
    public $redirectRoute = 'admin.users.edit';
    public $redirectBackRoute = 'admin.users.index';
    public $navTab = 'admin.users.logistic.navTab';

    public function store($user_uuid, AdminUserBankDetailsRequest $request, UserBankDetails $logistic_bank_detail)
    {
        return parent::store($user_uuid, $request, $logistic_bank_detail);
    }

    public function edit(Request $request, $user_uuid, UserBankDetails $logistic_bank_detail, User $userModel, BankBranch $bankBranchModel, BankMaster $bankMasterModel): View
    {
        return parent::edit($request, $user_uuid, $logistic_bank_detail, $userModel, $bankBranchModel, $bankMasterModel);
    }

    public function update($user_uuid, AdminUserBankDetailsRequest $request, UserBankDetails $logistic_bank_detail)
    {
        return parent::update($user_uuid, $request, $logistic_bank_detail);
    }

    public function destroy($user_uuid, UserBankDetails $logistic_bank_detail)
    {
        return parent::destroy($user_uuid, $logistic_bank_detail);
    }
}
