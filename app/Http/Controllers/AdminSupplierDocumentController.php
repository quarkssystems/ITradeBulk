<?php

namespace App\Http\Controllers;

class AdminSupplierDocumentController extends AdminUserDocumentController
{
    public $userRole = 'SUPPLIER';
    public $route = 'admin.supplier-document';
    public $redirectRoute = 'admin.users.edit';
    public $redirectBackRoute = 'admin.users.index';
    public $navTab = 'admin.users.supplier.navTab';
}
