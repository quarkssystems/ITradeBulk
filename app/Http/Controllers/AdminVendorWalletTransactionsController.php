<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminUserWalletTransactionsRequest;
use App\Models\WalletTransactions;
use Illuminate\Http\Request;

class AdminVendorWalletTransactionsController extends AdminUserWalletTransactionsController
{
    public $userRole = 'VENDOR';
    public $route = 'admin.vendor-wallet';
    public $redirectRoute = 'admin.users.edit';
    public $redirectBackRoute = 'admin.users.index';
    public $navTab = 'admin.users.vendor.navTab';

    public function store($user_uuid, AdminUserWalletTransactionsRequest $request, WalletTransactions $walletTransactions)
    {
        return parent::store($user_uuid, $request, $walletTransactions);
    }
}
