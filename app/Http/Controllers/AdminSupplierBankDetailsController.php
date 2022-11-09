<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminUserBankDetailsRequest;
use App\Models\BankBranch;
use App\Models\BankMaster;
use App\Models\UserBankDetails;
use App\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminSupplierBankDetailsController extends AdminUserBankDetailsController
{
    public $userRole = 'SUPPLIER';
    public $route = 'admin.supplier-bank-details';
    public $redirectRoute = 'admin.users.edit';
    public $redirectBackRoute = 'admin.users.index';
    public $navTab = 'admin.users.supplier.navTab';

    public function store($user_uuid, AdminUserBankDetailsRequest $request, UserBankDetails $supplier_bank_detail)
    {
        return parent::store($user_uuid, $request, $supplier_bank_detail);
    }

    public function edit(Request $request, $user_uuid, UserBankDetails $supplier_bank_detail, User $userModel, BankBranch $bankBranchModel, BankMaster $bankMasterModel): View
    {
        return parent::edit($request, $user_uuid, $supplier_bank_detail, $userModel, $bankBranchModel, $bankMasterModel);
    }

    public function update($user_uuid, AdminUserBankDetailsRequest $request, UserBankDetails $supplier_bank_detail)
    {
        return parent::update($user_uuid, $request, $supplier_bank_detail);
    }

    public function destroy($user_uuid, UserBankDetails $supplier_bank_detail)
    {
        return parent::destroy($user_uuid, $supplier_bank_detail);
    }
}
