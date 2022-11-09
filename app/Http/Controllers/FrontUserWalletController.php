<?php

namespace App\Http\Controllers;

use App\Http\Requests\FrontWalletTransactionRequest;
use Illuminate\Support\Facades\Mail;
use App\Models\WalletTransactions;
use App\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\EmailTemplate;

class FrontUserWalletController extends Controller
{
    public $route = 'user.wallet';

    public function index(WalletTransactions $transactionsModel) : View
    {
        $userId = auth()->user()->uuid;
        $transactions = $transactionsModel->where('user_id', $userId)->orderBy('id', 'DESC')->get();
        $walletBalance = auth()->user()->wallet_balance;
//        $roles = $userModel->getRoleDropDown();
//        $status = $userModel->getStatusesDropDown();

        $route = $this->route;
        $role = auth()->user()->role;
        if(auth()->user()->role == 'VENDOR'){
            $pageTitle = "Wallet Transactions";
        }else{
            $pageTitle = "Payment Summary";
        }
        
        $title =$pageTitle;
        return view('user.wallet.index', compact('transactions',  'title', 'pageTitle', 'route', 'role', 'walletBalance'));
    }

    public function create(WalletTransactions $wallet) : View
    {
        $route = $this->route;
        $role = auth()->user()->role;
        $transactionTypeDropDown = $wallet->getTransactionTypeDropDown();
        $pageTitle = "Wallet Credit";
        return view('user.wallet.form', compact('wallet','pageTitle', 'route', 'role', 'transactionTypeDropDown'));
    }

    public function store(FrontWalletTransactionRequest $request, WalletTransactions $wallet)
    {
        $admin = User::whereNull('deleted_at')->where([['status','ACTIVE'],['role','ADMIN']])->first();
        $adminEmail = $admin->email;
        $from = env('MAIL_FROM_ADDRESS');
        $app_name = env('APP_NAME');

        $user_email = auth()->user()->email;
        $request->merge(['user_id' => auth()->user()->uuid]);
        $route = $this->route;
        $request->merge(["status" => "PENDING"]);

         if($request->hasFile('receipt') && $request->file('receipt')->isValid())
        {
            $documentFile = $category->uploadMedia($request->file('receipt'));
            $document = $documentFile['path'].$documentFile['name'];
            $request->merge(['receipt' => $document]);
        }
        $wallet = $wallet->create($request->all());
        $refNo = $wallet->id;
        // dd($wallet);
        $redirectRoute = route("$route.index");
        $data['subject'] = 'Credit wallet request by user';
        $data['first_name'] = \Auth::user()->first_name;
        $data['last_name'] = \Auth::user()->last_name;
        $data['role'] = \Auth::user()->role;
        $data['amount'] = $request->get('credit_amount');


        $phone = '+88 0123 4567 890, +88 0123 4567 999';
        $facebook_url = 'https://www.facebook.com/';
        $instagram_url = 'https://www.instagram.com/';
        $twitter_url = 'https://www.twitter.com/';
        $pinterest_url = 'https://www.pinterest.com/';

        $email = EmailTemplate::where('name','=','credit_request')->first();

        if(isset($email)){
            $email->description = str_replace('[ADMIN_NAME]', $admin->first_name.' '.$admin->last_name, $email->description);
            $email->description = str_replace('[CUSTOMER_NAME]', $data['first_name'].' '.$data['last_name'], $email->description);
            $email->description = str_replace('[AMOUNT]', $data['amount'], $email->description);
            $email->description = str_replace('[ROLE]', $data['role'], $email->description);
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

            Mail::send([], [], function ($message) use ($data, $emailContent, $adminEmail, $from, $app_name) {
               $message->to($adminEmail)
                ->subject($data['subject'])
                ->setBody($emailContent, 'text/html'); // for HTML rich messages
                $message->from($from, $app_name);
            });
        } 

        // Mail::send([], $data, function($message) use ($data,$adminEmail,$user_email,$from,$app_name) {
        //     $message->to($adminEmail)->subject
        //        ($data['subject'])
        //        ->setBody('Hello Admin, Trader :  '.$data['first_name'].' '.$data['last_name'].' request credit of R '.$data['amount'].' in the ITZ wallet, Please review and approve the request. Thank You');
        //     $message->from($from,$app_name);
        //  });
        if(isset($wallet) && $wallet->transaction_type == 'EFT'){
            return redirect($redirectRoute)->with(['status' => 'success', 'message' => '<p>Wallet credit request successfully, Amount will be reflected in your account once its approved by admin.</p> <p>Please deposit the amount in our bank account and enter your beneficiary number W-'.$refNo.' in your reference details.</p> <p>You can confirm your payment by sending us proof of payment, or Please give us a day or two to reflect the payment in our bank.</p> <p>Thank You</p>']);
        }else {
            return redirect($redirectRoute)->with(['status' => 'success', 'message' => trans('success.wallet|credit|success')]);
        }
    }
}