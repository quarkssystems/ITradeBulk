<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\UserDocument;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    public function redirectPath()
    {

        if(auth()->check())
        {
            switch (auth()->user()->role)
            {
                case "ADMIN":
                    return route('admin.dashboard');
                    break;

                case "SUPPLIER";

                    $userdoc = New UserDocument; 
                    if($userdoc->getDocumentStatus()) //check document approve or not
                    {   
                        return route('supplier.dashboard'); 
                       
                    }
                    else
                    {
                      return route('supplier.document.create',auth()->user()->uuid);
                    }
                    
                    break;
                case "PICKER";
                    return route('supplier.dashboard');
                    break;
                 case "DISPATCHER";
                    return route('supplier.dashboard');
                    break;
                case "VENDOR";
                    return route('supplier.dashboard');
                    break;

                case "DRIVER";
                    return route('supplier.dashboard');
                    break;
                case "COMPANY";
                    return route('supplier.dashboard');
                    break;    
            }
        }
        return route("admin.dashboard");
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showAdminLoginForm()
    {
        $pageTitle = 'Login';
        return view('admin.auth.login', compact('pageTitle'));
    }
    public function showUserLoginForm()
    {
        $pageTitle = 'Login to your account';
        $pageSubTitle = 'enter your credentials';
        return view('frontend.auth.login', compact('pageTitle','pageSubTitle'));
    }
    public function showUserForgotPasswordForm()
    {
        $pageTitle = 'Forgot Password';
        $pageSubTitle = 'Enter your email';
        return view('frontend.auth.password-reset', compact('pageTitle','pageSubTitle'));
    }
}
