<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\DataGrid;
use App\Http\Requests\AdminUserTaxDetailsRequest;
use App\Models\UserTaxDetails;
use App\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminUserTaxDetailsController extends Controller
{
    use DataGrid;

    public $userRole = 'ADMIN';
    public $route = 'admin.user-tax-details';
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
     * @param $user_uuid
     * @param UserTaxDetails $user_tax_detail
     * @param User $userModel
     * @return View
     */
    public function create($user_uuid, UserTaxDetails $user_tax_detail, User $userModel) : View
    {
        $route = $this->route;
        $redirectBackRoute = $this->redirectBackRoute;
        $role = $this->userRole;
        $pageTitle = "TAX DETAILS";
        $user = $userModel->where('uuid', $user_uuid)->first();
        $navTab = $this->navTab;
        $user_tax_detail->setUserId($user_uuid);
        $verifyTaxDetailsDropDown = $user_tax_detail->getVerifyTaxDetailsDropDown();
        return view('admin.userTaxDetails.form', compact('user', 'pageTitle', 'route', 'role', 'navTab', 'redirectBackRoute', 'user_tax_detail', 'verifyTaxDetailsDropDown'));
    }

    /**
     * @param $user_uuid
     * @param AdminUserTaxDetailsRequest $request
     * @param UserTaxDetails $user_tax_detail
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store($user_uuid, AdminUserTaxDetailsRequest $request, UserTaxDetails $user_tax_detail)
    {
        if($request->hasFile('passport_document') && $request->file('passport_document')->isValid())
        {
            $documentFile = $user_tax_detail->uploadMedia($request->file('passport_document'));
            $document = $documentFile['path'].$documentFile['name'];
            $request->merge(['passport_document_file' => $document]);
        }
        $userTaxDetailData = $user_tax_detail->create($request->all());
        $route = $this->route;
        return redirect(route("$route.edit", ['user_uuid' => $user_uuid, 'user_tax_detail' => $userTaxDetailData] ))->with(['status' => 'success', 'message' => trans('success.admin|userTaxDetails|created')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserTaxDetails  $userTaxDetails
     * @return \Illuminate\Http\Response
     */
    public function show($user_uuid, UserTaxDetails $userTaxDetails)
    {
        return redirect()->route($this->redirectRoute, $user_uuid);
    }

    /**
     * @param $user_uuid
     * @param UserTaxDetails $user_tax_detail
     * @param User $userModel
     * @return View
     */
    public function edit($user_uuid, UserTaxDetails $user_tax_detail, User $userModel) : View
    {
        $route = $this->route;
        $redirectBackRoute = $this->redirectBackRoute;
        $role = $this->userRole;
        $pageTitle = "TAX DETAILS";
        $user = $userModel->where('uuid', $user_uuid)->first();
        $navTab = $this->navTab;
        $user_tax_detail->setUserId($user_uuid);
        $verifyTaxDetailsDropDown = $user_tax_detail->getVerifyTaxDetailsDropDown();
        return view('admin.userTaxDetails.form', compact('user', 'pageTitle', 'route', 'role', 'navTab', 'redirectBackRoute', 'user_tax_detail', 'verifyTaxDetailsDropDown'));
    }

    /**
     * @param $user_uuid
     * @param AdminUserTaxDetailsRequest $request
     * @param UserTaxDetails $user_tax_detail
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($user_uuid, AdminUserTaxDetailsRequest $request, UserTaxDetails $user_tax_detail)
    {
        if($request->hasFile('passport_document') && $request->file('passport_document')->isValid())
        {
            $documentFile = $user_tax_detail->uploadMedia($request->file('passport_document'));
            $document = $documentFile['path'].$documentFile['name'];
            $request->merge(['passport_document_file' => $document]);
        }
        $userTaxDetailData = $user_tax_detail->update($request->all());
        $route = $this->route;
        return redirect(route("$route.edit", ['user_uuid' => $user_uuid, 'user_tax_detail' => $user_tax_detail->uuid] ))->with(['status' => 'success', 'message' => trans('success.admin|userTaxDetails|updated')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserTaxDetails  $userTaxDetails
     * @return \Illuminate\Http\Response
     */
    public function destroy($user_uuid, UserTaxDetails $userTaxDetails)
    {
        return redirect()->route($this->redirectRoute, $user_uuid);
    }
}
