<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\BaseController;
use App\Http\Controllers\Helpers\DataGrid;
use App\Http\Requests\FrontUserBankDetailsRequest;
use App\Http\Requests\FrontendBankBranchRequest;
use App\Models\BankBranch;
use App\Models\BankMaster;
use App\Models\LocationCity;
use App\Models\LocationCountry;
use App\Models\LocationState;
use App\Models\UserBankDetails;
use App\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Validator;

class FrontUserBankDetailsController extends Controller
{
    use DataGrid, BaseController;

    public $userRole = 'SUPPLIER';
    public $route = 'supplier.bank-details';
    public $redirectRoute = 'supplier.bank-details.edit';
    public $redirectBackRoute = 'supplier.bank-details.index';

    /**
     * @param $user_uuid
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        return redirect()->route($this->route);
    }

    /**
     * @param Request $request
     * @param $user_uuid
     * @param UserBankDetails $user_bank_detail
     * @param User $userModel
     * @param BankBranch $bankBranchModel
     * @param BankMaster $bankMasterModel
     * @return View
     */
    public function create(Request $request, UserBankDetails $user_bank_detail, User $userModel, BankBranch $bankBranchModel, BankMaster $bankMasterModel, LocationCountry $locationCountryModel, LocationState $locationStateModel, LocationCity $locationCityModel)
    {
        $banks = $bankMasterModel->getBankDropDown();
        $countries = $locationCountryModel->getDropdown();
        $states = [];
        $cities = [];
        $zipcodes = [];

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
        $banks = $bankMasterModel->getBankDropDown();
       
        $user_uuid = auth()->user()->uuid;
        $user_bank_detail->setUserId($user_uuid);
        $url = route($route.'.edit');
        $redirectBackRoute = $this->redirectBackRoute;
        $role = $this->userRole;
        $pageTitle = "BANK DETAILS";
        $accountTypes = $user_bank_detail->getAccountTypesDropDown();

        $bankGridData = $this->bankBranchGrid($request, $bankBranchModel, $bankMasterModel, $url);
        $dataGridTitle = $bankGridData['dataGridTitle'];
        $dataGridSearch = $bankGridData['dataGridSearch'];
        $dataGridPagination = $bankGridData['dataGridPagination'];
        $data = $bankGridData['data'];
        $user_bank_detail = $user_bank_detail->ofUser()->first();
         $selectId ='';
        if ($request->ajax()) {
           
            return view('frontend.bankBranch.grid', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'user_bank_detail','selectId'));
        }
        else {
            return view('supplier.bankDetails.index', compact( 'pageTitle', 'route', 'role', 'redirectBackRoute', 'accountTypes', 'data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'user_bank_detail', 'countries', 'states', 'cities', 'zipcodes' , 'banks','bankBranchModel','selectId'));
        }
    }

    /**
     * @param $user_uuid
     * @param FrontUserBankDetailsRequest $request
     * @param UserBankDetails $user_bank_detail
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(FrontUserBankDetailsRequest $request, UserBankDetails $user_bank_detail)
    {
        

        $user_uuid = auth()->user()->uuid;
        if($request->hasFile('account_confirmation_letter') && $request->file('account_confirmation_letter')->isValid())
        {
            $documentFile = $user_bank_detail->uploadMedia($request->file('account_confirmation_letter'));
            $document = $documentFile['path'].$documentFile['name'];
            $request->merge(['account_confirmation_letter_file' => $document]);
        }
        $request->merge(['user_id' => $user_uuid]);
        $userBankDetailData = $user_bank_detail->create($request->all());
        $route = $this->route;
        return redirect(route("$route.edit", ['user_uuid' => $user_uuid, 'user_bank_detail' => $userBankDetailData] ))->with(['status' => 'success', 'message' => trans('success.admin|userBankDetails|created')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserBankDetails  $userBankDetails
     * @return \Illuminate\Http\Response
     */
    public function show(UserBankDetails $userBankDetails)
    {
        //
    }

    /**
     * @param Request $request
     * @param $user_uuid
     * @param UserBankDetails $user_bank_detail
     * @param User $userModel
     * @param BankBranch $bankBranchModel
     * @param BankMaster $bankMasterModel
     * @return View
     */
    public function edit($user_uuid,Request $request, UserBankDetails $user_bank_detail, User $userModel, BankBranch $bankBranchModel, BankMaster $bankMasterModel, LocationCountry $locationCountryModel, LocationState $locationStateModel, LocationCity $locationCityModel)
    {

        $route = $this->route;
        $user_bank_detail->setUserId($user_uuid);
        $url = $request->url();

        $countries = $locationCountryModel->getDropdown();
        $states = [];
        $cities = [];
        $zipcodes = [];

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

        $banks = $bankMasterModel->getBankDropDown();


        //echo user_bank_detail->uuid;
       // $url = route($route . '.edit', [ 'user_uuid' => $user_uuid, 'user_bank_detail' => $user_bank_detail->uuid]);
        //echo $url; die;

        $redirectBackRoute = $this->redirectBackRoute;
        $role = $this->userRole;
        $pageTitle = "BANK DETAILS";
        $accountTypes = $user_bank_detail->getAccountTypesDropDown();

        $bankGridData = $this->bankBranchGrid($request, $bankBranchModel, $bankMasterModel, $url);
        $dataGridTitle = $bankGridData['dataGridTitle'];
        $dataGridSearch = $bankGridData['dataGridSearch'];
        $dataGridPagination = $bankGridData['dataGridPagination'];
        $data = $bankGridData['data'];
        $user_bank_detail = $user_bank_detail->ofUser()->first();

        $selectId ='';
//        return response()->json($user_bank_detail);
        if ($request->ajax()) {
            
            return view('frontend.bankBranch.grid', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'user_bank_detail','selectId'));
        } else {
            return view('supplier.bankDetails.index', compact( 'pageTitle', 'route', 'role', 'redirectBackRoute', 'accountTypes', 'data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'user_bank_detail', 'countries', 'states', 'cities', 'zipcodes' , 'banks','bankBranchModel','selectId'));
        }
    }

    /**
     * @param $user_uuid
     * @param FrontUserBankDetailsRequest $request
     * @param UserBankDetails $user_bank_detail
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($bank_uuid, FrontUserBankDetailsRequest $request, UserBankDetails $user_bank_detail)
    {


        $user_bank_detail->setUserId(auth()->user()->uuid);
        if($request->hasFile('account_confirmation_letter') && $request->file('account_confirmation_letter')->isValid())
        {
            $documentFile = $user_bank_detail->uploadMedia($request->file('account_confirmation_letter'));
            $document = $documentFile['path'].$documentFile['name'];
            $request->merge(['account_confirmation_letter_file' => $document]);
        }
        $userBankDetailData = $user_bank_detail->updateOrCreate(['uuid'=>$bank_uuid],$request->all());
        $route = $this->route;
        return redirect(route("$route.edit",['user_uuid' => auth()->user()->uuid, 'user_bank_detail' => $userBankDetailData] ))->with(['status' => 'success', 'message' => trans('success.admin|userBankDetails|updated')]);
    }

    /**
     * @param $user_uuid
     * @param UserBankDetails $user_bank_detail
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($user_uuid, UserBankDetails $user_bank_detail)
    {
        return redirect()->route($this->redirectRoute, $user_uuid);
    }

}
