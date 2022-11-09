<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminUserRequest;
use App\User;
use Illuminate\View\View;

class AdminManageTransportersController extends AdminUserController
{
    public $dataUrl = '/admin/manage-transporter-company';
    public $userRole = 'COMPANY';
    public $route = 'admin.manage-transporter-company';
    public $navTab = 'admin.users.logistic.navTab';

    public function __construct()
    {
        //\DB::connection()->enableQueryLog();

    }
    public function store(AdminUserRequest $request, User $manage_transporter_company)
    {
        return parent::store($request, $manage_transporter_company);
    }

    public function edit(User $manage_transporter_company): View
    {
       // dd($manage_logistic);
        return parent::edit($manage_transporter_company);
    }

    public function update(AdminUserRequest $request, User $manage_transporter_company)
    {
        return parent::update($request, $manage_transporter_company);
    }

    public function destroy(User $manage_transporter_company)
    {
        return parent::destroy($manage_transporter_company);
    }
}
