<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminUserWalletTransactionsRequest;
use App\Models\WalletTransactions;
use App\User;
use Illuminate\Http\Request;

class AdminSupplierWalletTransactionsController extends AdminUserWalletTransactionsController
{

    public $userRole = 'SUPPLIER';
    public $route = 'admin.supplier-wallet';
    public $redirectRoute = 'admin.users.edit';
    public $redirectBackRoute = 'admin.users.index';
    public $navTab = 'admin.users.supplier.navTab';

    public function store($user_uuid, AdminUserWalletTransactionsRequest $request, WalletTransactions $walletTransactions)
    {
        return parent::store($user_uuid, $request, $walletTransactions);
    }

//    public function edit($user_uuid, WalletTransactions $walletTransactions, User $userModel): View
//    {
//        return parent::edit($user_uuid, $walletTransactions, $userModel);
//    }
//
//    public function update($user_uuid, AdminUserWalletTransactionsRequest $request, WalletTransactions $walletTransactions)
//    {
//        return parent::update($user_uuid, $request, $walletTransactions);
//    }
}
