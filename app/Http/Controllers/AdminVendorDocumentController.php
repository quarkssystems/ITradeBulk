<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\DataGrid;
use Illuminate\Http\Request;

class AdminVendorDocumentController extends AdminUserDocumentController
{
    use DataGrid;

    public $userRole = 'VENDOR';
    public $route = 'admin.vendor-document';
    public $redirectRoute = 'admin.users.edit';
    public $redirectBackRoute = 'admin.users.index';
    public $navTab = 'admin.users.vendor.navTab';
}
