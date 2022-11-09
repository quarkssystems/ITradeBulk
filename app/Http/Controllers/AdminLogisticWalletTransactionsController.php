<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminUserWalletTransactionsRequest;
use App\Models\WalletTransactions;
use Illuminate\Http\Request;

class AdminLogisticWalletTransactionsController extends AdminUserWalletTransactionsController
{
    public $userRole = 'LOGISTICS';
    public $route = 'admin.logistic-wallet';
    public $redirectRoute = 'admin.users.edit';
    public $redirectBackRoute = 'admin.users.index';
    public $navTab = 'admin.users.logistic.navTab';

    public function store($user_uuid, AdminUserWalletTransactionsRequest $request, WalletTransactions $walletTransactions)
    {
        return parent::store($user_uuid, $request, $walletTransactions);
    }
}
