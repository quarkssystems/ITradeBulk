<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminUserTaxDetailsRequest;
use App\Models\UserTaxDetails;
use App\User;
use Illuminate\View\View;

class AdminSupplierTaxDetailsController extends AdminUserTaxDetailsController
{
    public $userRole = 'VENDOR';
    public $route = 'admin.supplier-tax-details';
    public $redirectRoute = 'admin.users.edit';
    public $redirectBackRoute = 'admin.users.index';
    public $navTab = 'admin.users.supplier.navTab';

    public function store($user_uuid, AdminUserTaxDetailsRequest $request, UserTaxDetails $supplier_tax_detail)
    {
        return parent::store($user_uuid, $request, $supplier_tax_detail);
    }

    public function edit($user_uuid, UserTaxDetails $supplier_tax_detail, User $userModel): View
    {
        return parent::edit($user_uuid, $supplier_tax_detail, $userModel);
    }

    public function update($user_uuid, AdminUserTaxDetailsRequest $request, UserTaxDetails $supplier_tax_detail)
    {
        return parent::update($user_uuid, $request, $supplier_tax_detail);
    }
}
