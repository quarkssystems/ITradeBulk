<?php

namespace App\Http\Controllers;

class AdminCompanyDocumentController extends AdminUserDocumentController
{
    public $userRole = 'COMPANY';
    public $route = 'admin.company-document';
    public $redirectRoute = 'admin.users.edit';
    public $redirectBackRoute = 'admin.users.index';
    public $navTab = 'admin.users.logistic.navTab';
}
