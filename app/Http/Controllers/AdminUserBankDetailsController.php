<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\BaseController;
use App\Http\Controllers\Helpers\DataGrid;
use App\Http\Requests\AdminUserBankDetailsRequest;
use App\Models\BankBranch;
use App\Models\BankMaster;
use App\Models\UserBankDetails;
use App\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminUserBankDetailsController extends Controller
{
    use DataGrid, BaseController;

    public $userRole = 'ADMIN';
    public $route = 'admin.user-bank-details';
    public $redirectRoute = 'admin.users.edit';
    public $redirectBackRoute = 'admin.users.index';
    public $navTab = 'admin.users.admin.navTab';

    /**
     * @param $user_uuid
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index($user_uuid)
    {
        return redirect()->route($this->redirectRoute, $user_uuid);
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
    public function create(Request $request, $user_uuid, UserBankDetails $user_bank_detail, User $userModel, BankBranch $bankBranchModel, BankMaster $bankMasterModel) : View
    {
        $route = $this->route;
        $url = route($route.'.create', $user_uuid);
        $redirectBackRoute = $this->redirectBackRoute;
        $role = $this->userRole;
        $pageTitle = "BANK DETAILS";
        $user = $userModel->where('uuid', $user_uuid)->first();
        $navTab = $this->navTab;
        $accountTypes = $user_bank_detail->getAccountTypesDropDown();

        $bankGridData = $this->bankBranchGrid($request, $bankBranchModel, $bankMasterModel, $url);
        $dataGridTitle = $bankGridData['dataGridTitle'];
        $dataGridSearch = $bankGridData['dataGridSearch'];
        $dataGridPagination = $bankGridData['dataGridPagination'];
        $data = $bankGridData['data'];

        if ($request->ajax()) {
            return view('admin.userBankDetails.bankBranch.grid', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'user_bank_detail'));
        }
        else
        {
            return view('admin.userBankDetails.form', compact('user', 'pageTitle', 'route', 'role', 'navTab', 'redirectBackRoute', 'accountTypes', 'data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'user_bank_detail'));
        }


    }

    /**
     * @param $user_uuid
     * @param AdminUserBankDetailsRequest $request
     * @param UserBankDetails $user_bank_detail
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store($user_uuid, AdminUserBankDetailsRequest $request, UserBankDetails $user_bank_detail)
    {
        if($request->hasFile('account_confirmation_letter') && $request->file('account_confirmation_letter')->isValid())
        {
            $documentFile = $user_bank_detail->uploadMedia($request->file('account_confirmation_letter'));
            $document = $documentFile['path'].$documentFile['name'];
            $request->merge(['account_confirmation_letter_file' => $document]);
        }
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
    public function edit(Request $request, $user_uuid, UserBankDetails $user_bank_detail, User $userModel, BankBranch $bankBranchModel, BankMaster $bankMasterModel) : View
    {

        $route = $this->route;
        $url = route($route . '.edit', [ 'user_uuid' => $user_uuid, 'user_bank_detail' => $user_bank_detail->uuid]);
        $redirectBackRoute = $this->redirectBackRoute;
        $role = $this->userRole;
        $pageTitle = "BANK DETAILS";
        $user = $userModel->where('uuid', $user_uuid)->first();
        $navTab = $this->navTab;
        $accountTypes = $user_bank_detail->getAccountTypesDropDown();

        $bankGridData = $this->bankBranchGrid($request, $bankBranchModel, $bankMasterModel, $url);
        $dataGridTitle = $bankGridData['dataGridTitle'];
        $dataGridSearch = $bankGridData['dataGridSearch'];
        $dataGridPagination = $bankGridData['dataGridPagination'];
        $data = $bankGridData['data'];

        if ($request->ajax()) {
            return view('admin.userBankDetails.bankBranch.grid', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'user_bank_detail'));
        } else {
            return view('admin.userBankDetails.form', compact('user', 'pageTitle', 'route', 'role', 'navTab', 'redirectBackRoute', 'accountTypes', 'data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'user_bank_detail'));
        }
    }

    /**
     * @param $user_uuid
     * @param AdminUserBankDetailsRequest $request
     * @param UserBankDetails $user_bank_detail
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($user_uuid, AdminUserBankDetailsRequest $request, UserBankDetails $user_bank_detail)
    {
        if($request->hasFile('account_confirmation_letter') && $request->file('account_confirmation_letter')->isValid())
        {
            $documentFile = $user_bank_detail->uploadMedia($request->file('account_confirmation_letter'));
            $document = $documentFile['path'].$documentFile['name'];
            $request->merge(['account_confirmation_letter_file' => $document]);
        }
        $userBankDetailData = $user_bank_detail->create($request->all());
        $route = $this->route;
        return redirect(route("$route.edit", ['user_uuid' => $user_uuid, 'user_bank_detail' => $userBankDetailData] ))->with(['status' => 'success', 'message' => trans('success.admin|userBankDetails|updated')]);
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
