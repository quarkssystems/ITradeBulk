<?php



namespace App\Http\Controllers;



use App\Http\Requests\AdminUserRequest;

use App\User;

use Illuminate\View\View;



class AdminManageVendorController extends AdminUserController

{

    public $dataUrl = '/admin/manage-vendor';

    public $userRole = 'VENDOR';

    public $route = 'admin.manage-vendor';

    public $navTab = 'admin.users.vendor.navTab';



    public function store(AdminUserRequest $request, User $manage_vendor)

    {

        return parent::store($request, $manage_vendor);

    }



    public function edit(User $manage_vendor): View

    {

        return parent::edit($manage_vendor);

    }



    public function update(AdminUserRequest $request, User $manage_vendor)

    {

        return parent::update($request, $manage_vendor);

    }



    public function destroy(User $manage_vendor)

    {

        return parent::destroy($manage_vendor);

    }

}

