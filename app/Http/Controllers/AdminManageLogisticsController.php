<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminUserRequest;
use App\User;
use Illuminate\View\View;

class AdminManageLogisticsController extends AdminUserController
{
    public $dataUrl = '/admin/manage-logistic';
    public $userRole = 'DRIVER';
    public $route = 'admin.manage-logistic';
    public $navTab = 'admin.users.logistic.navTab';

    public function store(AdminUserRequest $request, User $manage_logistic)
    {
        return parent::store($request, $manage_logistic);
    }

    public function edit(User $manage_logistic): View
    {
        return parent::edit($manage_logistic);
    }

    public function update(AdminUserRequest $request, User $manage_logistic)
    {
        return parent::update($request, $manage_logistic);
    }

    public function destroy(User $manage_logistic)
    {
        return parent::destroy($manage_logistic);
    }
}
