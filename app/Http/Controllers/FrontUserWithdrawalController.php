<?php

namespace App\Http\Controllers;

use App\Http\Requests\FrontWithdrawalRequest;
use Illuminate\Support\Facades\Mail;
use App\Models\WalletTransactions;
use App\Models\Withdrawal;
use App\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\EmailTemplate;

class FrontUserWithdrawalController extends Controller
{
    public $route = 'user.withdrawal';

    public function index(Withdrawal $transactionsModel) : View
    {
        $userId = auth()->user()->uuid;
        $transactions = $transactionsModel->where('user_id', $userId)->orderBy('id', 'DESC')->get();
        $walletBalance = auth()->user()->wallet_balance;
//        $roles = $userModel->getRoleDropDown();
//        $status = $userModel->getStatusesDropDown();

        $route = $this->route;
        $role = auth()->user()->role;
        $pageTitle = "Settle Request";
        $title =$pageTitle;
        return view('user.withdrawal.index', compact('transactions',  'title', 'pageTitle', 'route', 'role', 'walletBalance'));
    }

    public function create(withdrawal $withdrawal) : View
    {
        $route = $this->route;
        $role = auth()->user()->role;
        //$transactionTypeDropDown = $wallet->getTransactionTypeDropDown();
        $pageTitle = "Wallet Debit";
        return view('user.withdrawal.form', compact('withdrawal','pageTitle', 'route', 'role'));
    }

    public function store(FrontWithdrawalRequest $request, Withdrawal $wallet)
    {
        // $request->merge(['user_id' => auth()->user()->uuid]);
        $admin = User::whereNull('deleted_at')->where([['status','ACTIVE'],['role','ADMIN']])->first();
        $adminEmail = $admin->email;
        $from = env('MAIL_FROM_ADDRESS');
        $app_name = env('APP_NAME');

        $route = $this->route;
        $redirectRoute = route("$route.index");
        // $request->merge(["status" => "PENDING"]);
        $walletAmount = auth()->user()->wallet_balance;

        if(isset($request->amount) && !empty($request->amount) && $request->amount <= $walletAmount) {
            
            $wallet->create([

                "credit_amount" => 0,
                "amount" => $request->amount,
                "user_id" => auth()->user()->uuid,
                "remarks" => $request->remarks,
                "status" => "PENDING"

            ]);
            
            $data['subject'] = 'Withdrawal request by user';
            $data['first_name'] = \Auth::user()->first_name;
            $data['last_name'] = \Auth::user()->last_name;
            $data['role'] = \Auth::user()->role;
            $data['amount'] = $request->amount;

            $phone = '+88 0123 4567 890, +88 0123 4567 999';
            $facebook_url = 'https://www.facebook.com/';
            $instagram_url = 'https://www.instagram.com/';
            $twitter_url = 'https://www.twitter.com/';
            $pinterest_url = 'https://www.pinterest.com/';

            $email = EmailTemplate::where('name','=','withdrawal_request')->first();

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

            // Mail::send([], $data, function($message) use ($data) {
            //     $message->to($admin_email)->subject
            //        ($data['subject'])
            //        ->setBody('Hello Admin, :  '.$data['first_name'].' '.$data['last_name'].' Request for withdrawal R '.$data['amount'].' From wallet. Please review and approve.Regards 
            //        Itradezon');
            //     $message->from('info@itradezon.com','iTradezon');
            //  });
            return redirect($redirectRoute)->with(['status' => 'success', 'message' => trans('success.withdraw|request|success')]);

        }else {
            return redirect($redirectRoute)->withErrors(['message', 'You have not sufficient amount in your wallet']);
        }

        // $wallet->create($request->all());
        
    }
}