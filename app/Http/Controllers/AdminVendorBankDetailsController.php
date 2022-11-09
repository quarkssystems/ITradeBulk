<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminUserBankDetailsRequest;
use App\Models\BankBranch;
use App\Models\BankMaster;
use App\Models\UserBankDetails;
use App\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminVendorBankDetailsController extends AdminUserBankDetailsController
{
    public $userRole = 'VENDOR';
    public $route = 'admin.vendor-bank-details';
    public $redirectRoute = 'admin.users.edit';
    public $redirectBackRoute = 'admin.users.index';
    public $navTab = 'admin.users.vendor.navTab';

    public function store($user_uuid, AdminUserBankDetailsRequest $request, UserBankDetails $vendor_bank_detail)
    {
        return parent::store($user_uuid, $request, $vendor_bank_detail);
    }

    public function edit(Request $request, $user_uuid, UserBankDetails $vendor_bank_detail, User $userModel, BankBranch $bankBranchModel, BankMaster $bankMasterModel): View
    {
        return parent::edit($request, $user_uuid, $vendor_bank_detail, $userModel, $bankBranchModel, $bankMasterModel);
    }

    public function update($user_uuid, AdminUserBankDetailsRequest $request, UserBankDetails $vendor_bank_detail)
    {
        return parent::update($user_uuid, $request, $vendor_bank_detail);
    }

    public function destroy($user_uuid, UserBankDetails $vendor_bank_detail)
    {
        return parent::destroy($user_uuid, $vendor_bank_detail);
    }
}
