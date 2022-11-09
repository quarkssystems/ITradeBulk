<?php

namespace App\Http\Controllers;

class AdminLogisticDocumentController extends AdminUserDocumentController
{
    public $userRole = 'LOGISTICS';
    public $route = 'admin.logistic-document';
    public $redirectRoute = 'admin.users.edit';
    public $redirectBackRoute = 'admin.users.index';
    public $navTab = 'admin.users.logistic.navTab';
}
