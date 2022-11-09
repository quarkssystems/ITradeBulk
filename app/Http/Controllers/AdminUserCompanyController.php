<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\DataGrid;
use App\Http\Requests\AdminUserCompanyRequest;
use App\Models\LocationCity;
use App\Models\LocationCountry;
use App\Models\LocationState;
use App\Models\UserCompany;
use App\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminUserCompanyController extends Controller
{
    use DataGrid;

    public $userRole = 'ADMIN';
    public $route = 'admin.user-company';
    public $redirectRoute = 'admin.users.edit';
    public $redirectBackRoute = 'admin.users.index';
    public $navTab = 'admin.users.admin.navTab';

    /**
     * @param $user_uuid
     * @return string
     */
    public function index($user_uuid)
    {
        return redirect()->route($this->redirectRoute, $user_uuid);
    }

    /**
     * @param Request $request
     * @param $user_uuid
     * @param UserCompany $user_company
     * @param User $userModel
     * @param LocationCountry $locationCountryModel
     * @param LocationState $locationStateModel
     * @param LocationCity $locationCityModel
     * @return View
     */
    public function create(Request $request, $user_uuid, UserCompany $user_company, User $userModel, LocationCountry $locationCountryModel, LocationState $locationStateModel, LocationCity $locationCityModel) : View
    {
        $businessType = $user_company->getBusinessTypeDropDown();
        $countries = $locationCountryModel->getDropdown();
        $states = [];
        $cities = [];
        $zipcodes = [];

        $foundingYears = range(date('Y'), (date('Y') - 100));
        $foundingYears = array_combine($foundingYears, $foundingYears);

        $countryInput = $request->old('country_id');
        $stateInput = $request->old('state_id');
        $cityInput = $request->old('city_id');
        if(!is_null($countryInput)) {
            $states = $locationCountryModel->where('uuid', $countryInput)->first()->getStateDropDown();
        }
        if(!is_null($stateInput)) {
            $cities = $locationStateModel->where('uuid', $stateInput)->first()->getCityDropDown();
        }
        if(!is_null($cityInput)) {
            $zipcodes = $locationCityModel->where('uuid', $cityInput)->first()->getZipcodeDropDown();
        }

        $route = $this->route;
        $redirectBackRoute = $this->redirectBackRoute;
        $role = $this->userRole;
        $pageTitle = "CREATE COMPANY";
        $user = $userModel->where('uuid', $user_uuid)->first();
        $navTab = $this->navTab;
        return view('admin.userCompany.form', compact('user', 'user_company', 'pageTitle', 'route', 'role', 'businessType', 'navTab', 'redirectBackRoute', 'countries', 'states', 'cities', 'zipcodes', 'foundingYears'));
    }

    /**
     * @param $user_uuid
     * @param AdminUserCompanyRequest $request
     * @param UserCompany $user_company
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store($user_uuid, AdminUserCompanyRequest $request, UserCompany $user_company)
    {
        $userCompanyData = $user_company->create($request->all());
        $route = $this->route;
        return redirect(route("$route.edit", ['user_uuid' => $user_uuid, 'vendor_company' => $userCompanyData] ))->with(['status' => 'success', 'message' => trans('success.admin|userCompany|created')]);
    }

    /**
     * @param $user_uuid
     * @param UserCompany $user_company
     * @return string
     */
    public function show($user_uuid, UserCompany $user_company)
    {
        return redirect()->route($this->redirectRoute, $user_uuid);
    }

    /**
     * @param Request $request
     * @param $user_uuid
     * @param UserCompany $user_company
     * @param User $userModel
     * @param LocationCountry $locationCountryModel
     * @param LocationState $locationStateModel
     * @param LocationCity $locationCityModel
     * @return View
     */
    public function edit(Request $request, $user_uuid, UserCompany $user_company, User $userModel, LocationCountry $locationCountryModel, LocationState $locationStateModel, LocationCity $locationCityModel) : View
    {
        $businessType = $user_company->getBusinessTypeDropDown();
        $countries = $locationCountryModel->getDropdown();
        $states = [];
        $cities = [];
        $zipcodes = [];

        $foundingYears = range(date('Y'), (date('Y') - 100));
        $foundingYears = array_combine($foundingYears, $foundingYears);

        $countryInput = $request->has('country_id') ? $request->get('country_id') : $user_company->country_id;
        $stateInput = $request->has('state_id') ? $request->get('state_id') : $user_company->state_id;
        $cityInput = $request->has('city_id') ? $request->get('city_id') : $user_company->city_id;
        if(!is_null($countryInput)) {
            $states = $locationCountryModel->where('uuid', $countryInput)->count() > 0 ? $locationCountryModel->where('uuid', $countryInput)->first()->getStateDropDown() : [];
        }
        if(!is_null($stateInput)) {
            $cities = $locationStateModel->where('uuid', $stateInput)->count() > 0 ? $locationStateModel->where('uuid', $stateInput)->first()->getCityDropDown() : [];
        }
        if(!is_null($cityInput)) {
            $zipcodes = $locationCityModel->where('uuid', $cityInput)->count() > 0 ? $locationCityModel->where('uuid', $cityInput)->first()->getZipcodeDropDown() : [];
        }

        $route = $this->route;
        $redirectBackRoute = $this->redirectBackRoute;
        $role = $this->userRole;
        $pageTitle = "EDIT COMPANY";
        $user = $userModel->where('uuid', $user_uuid)->first();
        $navTab = $this->navTab;
        return view('admin.userCompany.form', compact('user', 'user_company', 'pageTitle', 'route', 'role', 'businessType', 'navTab', 'redirectBackRoute', 'countries', 'states', 'cities', 'zipcodes', 'foundingYears'));
    }

    /**
     * @param $user_uuid
     * @param AdminUserCompanyRequest $request
     * @param UserCompany $user_company
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($user_uuid, AdminUserCompanyRequest $request, UserCompany $user_company)
    {
        $user_company->update($request->all());
        $route = $this->route;
        return redirect(route("$route.edit", ['user_uuid' => $user_uuid, 'vendor_company' => $user_company->uuid]))->with(['status' => 'success', 'message' => trans('success.admin|userCompany|updated')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserCompany  $userCompany
     * @return \Illuminate\Http\Response
     */
    public function destroy($user_uuid, UserCompany $userCompany)
    {
        return redirect()->route($this->redirectRoute, $user_uuid);
    }
}