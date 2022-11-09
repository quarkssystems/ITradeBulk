<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\DataGrid;
use App\Http\Requests\AdminUserTaxDetailsRequest;
use App\Models\LogisticCompany;
use App\Models\LogisticCompanyTaxDetails;
use App\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminLogisticCompanyTaxDetailsController extends Controller
{
    use DataGrid;

    public $userRole = 'ADMIN';
    public $route = 'admin.logistic-company-tax-details';
    public $redirectRoute = 'admin.logistic-company.edit';
    public $redirectBackRoute = 'admin.users.index';
    public $navTab = 'admin.logisticCompanyDetails.navTab';

    /**
     * @param $user_uuid
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index($user_uuid)
    {
        return redirect()->route($this->redirectRoute, $user_uuid);
    }

    /**
     * @param $user_uuid
     * @param LogisticCompanyTaxDetails $user_tax_detail
     * @param User $userModel
     * @return View
     */
    public function create($logistic_company_uuid, LogisticCompanyTaxDetails $logistic_company_tax_detail, LogisticCompany $logisticCompanyrModel) : View
    {
        $route = $this->route;
        $redirectBackRoute = $this->redirectBackRoute;
        $pageTitle = "TAX DETAILS";
        $logisticCompany = $logisticCompanyrModel->where('uuid', $logistic_company_uuid)->first();
        $navTab = $this->navTab;
        $logistic_company_tax_detail->setLogisticCompanyId($logistic_company_uuid);
        $verifyTaxDetailsDropDown = $logistic_company_tax_detail->getVerifyTaxDetailsDropDown();
        return view('admin.logisticCompanyTaxDetails.form', compact('logisticCompany', 'pageTitle', 'route', 'role', 'navTab', 'redirectBackRoute', 'logistic_company_tax_detail', 'verifyTaxDetailsDropDown'));
    }

    /**
     * @param $user_uuid
     * @param AdminUserTaxDetailsRequest $request
     * @param LogisticCompanyTaxDetails $user_tax_detail
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store($logistic_company_uuid, AdminUserTaxDetailsRequest $request, LogisticCompanyTaxDetails $logistic_company_tax_detail)
    {
        if($request->hasFile('passport_document') && $request->file('passport_document')->isValid())
        {
            $documentFile = $logistic_company_tax_detail->uploadMedia($request->file('passport_document'));
            $document = $documentFile['path'].$documentFile['name'];
            $request->merge(['passport_document_file' => $document]);
        }
        $logistic_company_tax_detail = $logistic_company_tax_detail->create($request->all());
        $route = $this->route;
        return redirect(route("$route.edit", ['logistic_company_uuid' => $logistic_company_uuid, 'logistic_company_tax_detail' => $logistic_company_tax_detail] ))->with(['status' => 'success', 'message' => trans('success.admin|userTaxDetails|created')]);
    }

    /**
     * @param $user_uuid
     * @param LogisticCompanyTaxDetails $user_tax_detail
     * @param User $userModel
     * @return View
     */
    public function edit($logistic_company_uuid, LogisticCompanyTaxDetails $logistic_company_tax_detail, LogisticCompany $logisticCompanyrModel) : View
    {
        $route = $this->route;
        $redirectBackRoute = $this->redirectBackRoute;
        $role = $this->userRole;
        $pageTitle = "TAX DETAILS";
        $logisticCompany = $logisticCompanyrModel->where('uuid', $logistic_company_uuid)->first();
        $navTab = $this->navTab;
        $logistic_company_tax_detail->setLogisticCompanyId($logistic_company_uuid);
        $verifyTaxDetailsDropDown = $logistic_company_tax_detail->getVerifyTaxDetailsDropDown();
        return view('admin.logisticCompanyTaxDetails.form', compact('logisticCompany', 'pageTitle', 'route', 'role', 'navTab', 'redirectBackRoute', 'logistic_company_tax_detail', 'verifyTaxDetailsDropDown'));
    }

    /**
     * @param $user_uuid
     * @param AdminUserTaxDetailsRequest $request
     * @param LogisticCompanyTaxDetails $user_tax_detail
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($logistic_company_uuid, AdminUserTaxDetailsRequest $request, LogisticCompanyTaxDetails $logistic_company_tax_detail)
    {
        if($request->hasFile('passport_document') && $request->file('passport_document')->isValid())
        {
            $documentFile = $logistic_company_tax_detail->uploadMedia($request->file('passport_document'));
            $document = $documentFile['path'].$documentFile['name'];
            $request->merge(['passport_document_file' => $document]);
        }
        $logistic_company_tax_detail->update($request->all());
        $route = $this->route;
        return redirect(route("$route.edit", ['logistic_company_uuid' => $logistic_company_uuid, 'logistic_company_tax_detail' => $logistic_company_tax_detail->uuid] ))->with(['status' => 'success', 'message' => trans('success.admin|userTaxDetails|updated')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LogisticCompanyTaxDetails  $userTaxDetails
     * @return \Illuminate\Http\Response
     */
    public function destroy($logistic_company_uuid, LogisticCompanyTaxDetails $userTaxDetails)
    {
        return redirect()->route($this->redirectRoute, $logistic_company_uuid);
    }
}
