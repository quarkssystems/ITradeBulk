<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\BaseController;
use App\Http\Controllers\Helpers\DataGrid;
use App\Http\Requests\AdminUserWalletTransactionsRequest;
use App\Models\WalletTransactions;
use App\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\General\ChangeOrderStatus;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Mail;

class AdminUserWalletTransactionsController extends Controller
{
    use DataGrid, BaseController;

    public $userRole = 'ADMIN';
    public $route = 'admin.user-wallet';
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create( $user_uuid, WalletTransactions $walletTransactions, User $userModel): View
    {
        $walletTransactions->setUserId($user_uuid);
        $route = $this->route;
        $role = $this->userRole;
        $pageTitle = "Wallet Transactions";
        $user = $userModel->where('uuid', $user_uuid)->first();
        $navTab = $this->navTab;
        $transactionTypes = $walletTransactions->getTransactionTypesDropDown();
        $walletTransactionsCounts = $walletTransactions->walletTransactionsCount($user_uuid);
        $transactionTypesDropdown = $walletTransactions->getTransactionTypeDropDown();
//        $walletCount = $walletTransactions
        $walletTransactions = $walletTransactions->ofUser()->orderBy('id','DESC')->get();
        return view('admin.userWallet.form', compact('user', 'pageTitle', 'route', 'role', 'navTab','walletTransactions','transactionTypes','walletTransactionsCounts', 'transactionTypesDropdown'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store($user_uuid,AdminUserWalletTransactionsRequest $request,WalletTransactions $walletTransactions)
    {

        $transactionArray = array(
            'user_id' => $user_uuid,
            'remarks' => $request->remarks
        );
        if($request->transaction_mode == 'DEBIT'){
            $transactionArray['debit_amount'] = $request->amount;
        }else if($request->transaction_mode == 'CREDIT'){
            $transactionArray['credit_amount'] = $request->amount;
        }else{
            return redirect()->back()->with(['status' => 'success', 'message' => trans('success.admin|transaction|type')]);
        }
        $walletTransactions->create($transactionArray);
        $route = $this->route;
        return redirect(route("$route.create", ['user_uuid' => $user_uuid, 'walletTransactions' => $walletTransactions] ))->with(['status' => 'success', 'message' => trans('success.admin|transaction|create')]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function approveTransaction($transaction_id, WalletTransactions $wallet)
    {
        $wallet->where('uuid', $transaction_id)->update(["status" => "APPROVED"]);
        $walletData = $wallet->where('uuid', $transaction_id)->first();
        $user = User::whereNull('deleted_at')->where([['status','ACTIVE'],['uuid',$walletData->user_id]])->first();
        $userEmail = $user->email;
        ChangeOrderStatus::walletTransaction($transaction_id);

        $phone = '+88 0123 4567 890, +88 0123 4567 999';
        $facebook_url = 'https://www.facebook.com/';
        $instagram_url = 'https://www.instagram.com/';
        $twitter_url = 'https://www.twitter.com/';
        $pinterest_url = 'https://www.pinterest.com/';

        $app_name = env('MAIL_FROM_NAME');
        $from = env('MAIL_FROM_ADDRESS');

        $email = EmailTemplate::where('name','=','credit_approved')->first();

        if(isset($email)){
            $email->description = str_replace('[CUSTOMER_NAME]', $user->first_name.' '.$user->last_name, $email->description);
            $email->description = str_replace('[EMAIL]', env('SUPPORT'), $email->description);
            $email->description = str_replace('[SITE_NAME]', env('WEBSITE'), $email->description);
            $email->description = str_replace('[PHONE]', $phone, $email->description);
            $email->description = str_replace('[FACEBOOK_URL]', $facebook_url, $email->description);
            $email->description = str_replace('[TWITTER_URL]', $twitter_url, $email->description);
            $email->description = str_replace('[INSTAGRAM_URL]', $instagram_url, $email->description);
            $email->description = str_replace('[PINTEREST_URL]', $pinterest_url, $email->description);
            $email->description = str_replace('[LOGO]', asset('assets/images/logo.png'), $email->description);
            $email->description = str_replace('[BECOME_SUPPLIER]', asset("assets/frontend/images/Become-Supplier.png"), $email->description);
            $email->description = str_replace('[BECOME_TRANSPORTER]', asset("assets/frontend/images/Become-Driver-1.png"), $email->description);
            $email->description = str_replace('[BECOME_TRADER]', asset("assets/frontend/images/Become-Vender.png"), $email->description);

            $emailContent = $email->description;

            Mail::send([], [], function ($message) use ($emailContent, $userEmail, $from, $app_name) {
               $message->to($userEmail)
                ->subject('Funds added into the iTradeBulk™ wallet')
                // ->subject('Funds added into the ITZ wallet')
                ->setBody($emailContent, 'text/html'); // for HTML rich messages
                $message->from($from, $app_name);
            });
        } 

        return redirect()->back()->with(['status' => 'success', 'message' => "Transaction approved successfully"]);
    }

    public function cancelTransaction($transaction_id, WalletTransactions $wallet)
    {
        $wallet->where('uuid', $transaction_id)->update(["status" => "CANCELED"]);
        $walletData = $wallet->where('uuid', $transaction_id)->first();
        $user = User::whereNull('deleted_at')->where([['status','ACTIVE'],['uuid',$walletData->user_id]])->first();
        $userEmail = $user->email;
        ChangeOrderStatus::walletTransaction($transaction_id);

        $phone = '+88 0123 4567 890, +88 0123 4567 999';
        $facebook_url = 'https://www.facebook.com/';
        $instagram_url = 'https://www.instagram.com/';
        $twitter_url = 'https://www.twitter.com/';
        $pinterest_url = 'https://www.pinterest.com/';

        $app_name = env('MAIL_FROM_NAME');
        $from = env('MAIL_FROM_ADDRESS');

        $email = EmailTemplate::where('name','=','credit_canceled')->first();

        if(isset($email)){
            $email->description = str_replace('[CUSTOMER_NAME]', $user->first_name.' '.$user->last_name, $email->description);
            $email->description = str_replace('[EMAIL]', env('SUPPORT'), $email->description);
            $email->description = str_replace('[SITE_NAME]', env('WEBSITE'), $email->description);
            $email->description = str_replace('[PHONE]', $phone, $email->description);
            $email->description = str_replace('[FACEBOOK_URL]', $facebook_url, $email->description);
            $email->description = str_replace('[TWITTER_URL]', $twitter_url, $email->description);
            $email->description = str_replace('[INSTAGRAM_URL]', $instagram_url, $email->description);
            $email->description = str_replace('[PINTEREST_URL]', $pinterest_url, $email->description);
            $email->description = str_replace('[LOGO]', asset('assets/images/logo.png'), $email->description);
            $email->description = str_replace('[BECOME_SUPPLIER]', asset("assets/frontend/images/Become-Supplier.png"), $email->description);
            $email->description = str_replace('[BECOME_TRANSPORTER]', asset("assets/frontend/images/Become-Driver-1.png"), $email->description);
            $email->description = str_replace('[BECOME_TRADER]', asset("assets/frontend/images/Become-Vender.png"), $email->description);

            $emailContent = $email->description;

            Mail::send([], [], function ($message) use ($emailContent, $userEmail, $from, $app_name) {
               $message->to($userEmail)
                ->subject('Credit request canceled by user ')
                ->setBody($emailContent, 'text/html'); // for HTML rich messages
                $message->from($from, $app_name);
            });
        } 

        return redirect()->back()->with(['status' => 'success', 'message' => "Transaction canceled successfully"]);
    }
}