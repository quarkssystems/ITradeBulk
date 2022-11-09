<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminUserCompanyRequest;
use App\Models\LocationCity;
use App\Models\LocationCountry;
use App\Models\LocationState;
use App\Models\UserCompany;
use App\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminVendorCompanyController extends AdminUserCompanyController
{
    public $userRole = 'VENDOR';
    public $route = 'admin.vendor-company';
    public $redirectRoute = 'admin.manage-vendor.edit';
    public $redirectBackRoute = 'admin.manage-vendor.index';
    public $navTab = 'admin.users.vendor.navTab';

    public function store($user_uuid, AdminUserCompanyRequest $request, UserCompany $vendor_company)
    {
        return parent::store($user_uuid, $request, $vendor_company);
    }

    public function edit(Request $request, $user_uuid, UserCompany $vendor_company, User $userModel, LocationCountry $locationCountryModel, LocationState $locationStateModel, LocationCity $locationCityModel): View
    {
        return parent::edit($request, $user_uuid, $vendor_company, $userModel, $locationCountryModel, $locationStateModel, $locationCityModel);
    }

    public function update($user_uuid, AdminUserCompanyRequest $request, UserCompany $vendor_company)
    {
        return parent::update($user_uuid, $request, $vendor_company);
    }
}