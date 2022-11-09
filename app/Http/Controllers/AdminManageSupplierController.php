<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminUserRequest;
use App\User;
use Illuminate\View\View;

class AdminManageSupplierController extends AdminUserController
{
    public $dataUrl = '/admin/manage-supplier';
    public $userRole = 'SUPPLIER';
    public $route = 'admin.manage-supplier';
    public $navTab = 'admin.users.supplier.navTab';

    public function store(AdminUserRequest $request, User $manage_supplier)
    {
        return parent::store($request, $manage_supplier);
    }

    public function edit(User $manage_supplier): View
    {
        return parent::edit($manage_supplier);
    }

    public function update(AdminUserRequest $request, User $manage_supplier)
    {
        return parent::update($request, $manage_supplier);
    }

    public function destroy(User $manage_supplier)
    {
        return parent::destroy($manage_supplier);
    }
}
