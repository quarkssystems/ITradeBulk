<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\DataGrid;
use App\Http\Requests\AdminUserTaxDetailsRequest;
use App\Http\Requests\FrontUserTaxDetailsRequest;
use App\Models\UserTaxDetails;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FrontUserTaxDetailsController extends Controller
{
    use DataGrid;

    public $userRole = 'SUPPLIER';
    public $route = 'supplier.tax-details';
    public $redirectRoute = 'supplier.tax-details.edit';
    public $redirectBackRoute = 'supplier.tax-details.index';

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
        $user_tax_detail->setUserId($user_uuid);
        $user_tax_detail->setUserId($user_uuid);
        $user_tax_detail = $user_tax_detail->OfUser()->first();
        return view('supplier.taxDetails.index', compact('user', 'pageTitle', 'route', 'role', 'redirectBackRoute', 'user_tax_detail'));
    }

    /**
     * @param $user_uuid
     * @param FrontUserTaxDetailsRequest $request
     * @param UserTaxDetails $user_tax_detail
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(FrontUserTaxDetailsRequest $request, UserTaxDetails $user_tax_detail)
    {
        $user_uuid = auth()->user()->uuid;
        if($request->hasFile('passport_document') && $request->file('passport_document')->isValid())
        {
            $documentFile = $user_tax_detail->uploadMedia($request->file('passport_document'));
            $document = $documentFile['path'].$documentFile['name'];
            $request->merge(['passport_document_file' => $document]);
        }
        $request->merge(['user_id' => $user_uuid]);

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
    public function edit( UserTaxDetails $user_tax_detail, User $userModel) : View
    {
        $user_uuid = auth()->user()->uuid;
        $route = $this->route;
        $redirectBackRoute = $this->redirectBackRoute;
        $role = $this->userRole;
        $pageTitle = "TAX DETAILS";
        $user = $userModel->where('uuid', $user_uuid)->first();
        $user_tax_detail->setUserId($user_uuid);

        $user_tax_detail = $user_tax_detail->OfUser()->first();
        return view('supplier.taxDetails.index', compact('user', 'pageTitle', 'route', 'role', 'redirectBackRoute', 'user_tax_detail'));
    }

    /**
     * @param $user_uuid
     * @param FrontUserTaxDetailsRequest $request
     * @param UserTaxDetails $user_tax_detail
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($user_uuid, FrontUserTaxDetailsRequest $request, UserTaxDetails $user_tax_detail)
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
