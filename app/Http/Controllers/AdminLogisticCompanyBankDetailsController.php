<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\BaseController;
use App\Http\Controllers\Helpers\DataGrid;
use App\Http\Requests\AdminUserBankDetailsRequest;
use App\Models\BankBranch;
use App\Models\BankMaster;
use App\Models\LogisticCompany;
use App\Models\LogisticCompanyBankDetails;
use App\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminLogisticCompanyBankDetailsController extends Controller
{
    use DataGrid, BaseController;
    public $route = 'admin.logistic-company-bank-details';
    public $redirectRoute = 'admin.users.edit';
    public $redirectBackRoute = 'admin.users.index';
    public $navTab = 'admin.logisticCompanyDetails.navTab';

    /**
     * @param Request $request
     * @param $user_uuid
     * @param UserBankDetails $user_bank_detail
     * @param User $userModel
     * @param BankBranch $bankBranchModel
     * @param BankMaster $bankMasterModel
     * @return View
     */
    public function create(Request $request, $logistic_company_uuid, LogisticCompanyBankDetails $logistic_company_bank_detail, LogisticCompany $logisticCompanyModel, BankBranch $bankBranchModel, BankMaster $bankMasterModel) : View
    {
        $route = $this->route;
        $url = route($route.'.create', $logistic_company_uuid);
        $redirectBackRoute = $this->redirectBackRoute;
        $pageTitle = "BANK DETAILS";
        $logisticCompany = $logisticCompanyModel->where('uuid', $logistic_company_uuid)->first();
        $navTab = $this->navTab;
        $accountTypes = $logistic_company_bank_detail->getAccountTypesDropDown();

        $bankGridData = $this->bankBranchGrid($request, $bankBranchModel, $bankMasterModel, $url);
        $dataGridTitle = $bankGridData['dataGridTitle'];
        $dataGridSearch = $bankGridData['dataGridSearch'];
        $dataGridPagination = $bankGridData['dataGridPagination'];
        $data = $bankGridData['data'];

        if ($request->ajax()) {
            return view('admin.logisticCompanyBankDetails.bankBranch.grid', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'user_bank_detail'));
        }
        else
        {
            return view('admin.logisticCompanyBankDetails.form', compact('logisticCompany', 'pageTitle', 'route', 'navTab', 'redirectBackRoute', 'accountTypes', 'data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'logistic_company_bank_detail'));
        }
    }

    /**
     * @param $user_uuid
     * @param AdminUserBankDetailsRequest $request
     * @param UserBankDetails $user_bank_detail
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store($user_uuid, AdminUserBankDetailsRequest $request, LogisticCompanyBankDetails $logistc_company_bank_detail)
    {
        if($request->hasFile('account_confirmation_letter') && $request->file('account_confirmation_letter')->isValid())
        {
            $documentFile = $logistc_company_bank_detail->uploadMedia($request->file('account_confirmation_letter'));
            $document = $documentFile['path'].$documentFile['name'];
            $request->merge(['account_confirmation_letter_file' => $document]);
        }
        $userBankDetailData = $logistc_company_bank_detail->create($request->all());
        $route = $this->route;
        return redirect(route("$route.edit", ['user_uuid' => $user_uuid, 'logistc_company_bank_detail' => $userBankDetailData] ))->with(['status' => 'success', 'message' => trans('success.admin|userBankDetails|created')]);
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
    public function edit(Request $request, $logistic_company_uuid, LogisticCompanyBankDetails $logistic_company_bank_detail, LogisticCompany $logisticCompanyModel, BankBranch $bankBranchModel, BankMaster $bankMasterModel) : View
    {

        $route = $this->route;
        $url = route($route . '.edit', [ 'logistic_company_uuid' => $logistic_company_uuid, 'logistic_company_bank_detail' => $logistic_company_bank_detail->uuid]);
        $redirectBackRoute = $this->redirectBackRoute;
        $pageTitle = "BANK DETAILS";
        $logisticCompany = $logisticCompanyModel->where('uuid', $logistic_company_uuid)->first();
        $navTab = $this->navTab;
        $accountTypes = $logistic_company_bank_detail->getAccountTypesDropDown();

        $bankGridData = $this->bankBranchGrid($request, $bankBranchModel, $bankMasterModel, $url);
        $dataGridTitle = $bankGridData['dataGridTitle'];
        $dataGridSearch = $bankGridData['dataGridSearch'];
        $dataGridPagination = $bankGridData['dataGridPagination'];
        $data = $bankGridData['data'];

        if ($request->ajax()) {
            return view('admin.logisticCompanyBankDetails.bankBranch.grid', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'user_bank_detail'));
        } else {
            return view('admin.logisticCompanyBankDetails.form', compact('logisticCompany', 'pageTitle', 'route', 'role', 'navTab', 'redirectBackRoute', 'accountTypes', 'data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'logistic_company_bank_detail'));
        }
    }

    /**
     * @param $user_uuid
     * @param AdminUserBankDetailsRequest $request
     * @param UserBankDetails $user_bank_detail
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($logistic_company_uuid, AdminUserBankDetailsRequest $request, LogisticCompanyBankDetails $logistic_company_bank_detail)
    {
        if($request->hasFile('account_confirmation_letter') && $request->file('account_confirmation_letter')->isValid())
        {
            $documentFile = $logistic_company_bank_detail->uploadMedia($request->file('account_confirmation_letter'));
            $document = $documentFile['path'].$documentFile['name'];
            $request->merge(['account_confirmation_letter_file' => $document]);
        }
        $logistic_company_bank_detail = $logistic_company_bank_detail->create($request->all());
        $route = $this->route;
        return redirect(route("$route.edit", ['logistic_company_uuid' => $logistic_company_uuid, 'logistic_company_bank_detail' => $logistic_company_bank_detail] ))->with(['status' => 'success', 'message' => trans('success.admin|userBankDetails|updated')]);
    }
    /**
     * @param $user_uuid
     * @param LogisticCompanyBankDetails $user_bank_detail
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($logistic_company_uuid, LogisticCompanyBankDetails $user_bank_detail)
    {
        return redirect()->route($this->redirectRoute, $logistic_company_uuid);
    }
}
