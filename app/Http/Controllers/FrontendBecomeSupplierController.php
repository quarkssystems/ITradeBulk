<?php

namespace App\Http\Controllers;

use App\Http\Requests\FrontendBecomeSupplierRequest;
use App\Models\Banner;
use App\Models\LocationCity;
use App\Models\LocationCountry;
use App\Models\LocationState;
use App\Models\UserCompany;
use App\Models\UserDocument;
use App\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Mail;

class FrontendBecomeSupplierController extends Controller
{
    public $userRole = 'SUPPLIER';
    public $route = 'become-supplier';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function index(Request $request, User $user, UserCompany $userCompanyModel, LocationCountry $locationCountryModel, LocationState $locationStateModel, LocationCity $locationCityModel, UserDocument $userDocumentModel): View
    {
        $type = array();
        $gender = $user->getGenderDropDown();
        $title = $user->getTitleDropDown();
        $roles = $user->getRoleDropDown();
        $status = $user->getStatusesDropDown();

        $businessType = $userCompanyModel->getBusinessTypeDropDown();
        $countries = $locationCountryModel->getDropdown();
        $states = [];
        $cities = [];
        $zipcodes = [];

        $foundingYears = range(date('Y'), (date('Y') - 100));
        $foundingYears = array_combine($foundingYears, $foundingYears);

        $countryInput = $request->old('country_id');
        $stateInput = $request->old('state_id');
        $cityInput = $request->old('city_id');
        if (!is_null($countryInput)) {
            $states = $locationCountryModel->where('uuid', $countryInput)->first()->getStateDropDown();
        }
        if (!is_null($stateInput)) {
            $cities = $locationStateModel->where('uuid', $stateInput)->first()->getCityDropDown();
        }
        if (!is_null($cityInput)) {
            $zipcodes = $locationCityModel->where('uuid', $cityInput)->first()->getZipcodeDropDown();
        }

        $documentTypes = $userDocumentModel->getSupplierDocuments();
        $banner = Banner::whereNull('deleted_at')->where([['status', 'Active'], ['page_name', 1]])->first();
        $banner = $banner->image ?? '';
        $route = $this->route;
        $role = $this->userRole;
        $pageTitle = "Become Supplier";
        $breadcrumb = [
            'home' => 'Home',
            'become-supplier' => $pageTitle
        ];
        return view('frontend.becomeSupplier.form', compact('user', 'gender', 'title', 'type', 'roles', 'status', 'pageTitle', 'route', 'role', 'breadcrumb', 'businessType', 'countries', 'states', 'cities', 'zipcodes', 'foundingYears', 'documentTypes', 'banner'));
    }

    public function store(FrontendBecomeSupplierRequest $request, User $user)
    {
        $company = $request->get('company');
        $company = array_merge($company, [
            'zipcode_id' => $request->get('zipcode_id'),
            'city_id' => $request->get('city_id'),
            'state_id' => $request->get('state_id'),
            'country_id' => $request->get('country_id')
        ]);

        $request->merge(['status' => 'INACTIVE', 'role' => $this->userRole, 'password' => bcrypt($request->get("password"))]);
        $userData = $user->create($request->all());
        $userData->company()->create($company);
        $userData->sendEmailVerificationNotification();

        //TODO: Send Verification email + confirmation mail that we got your request

        $userEmail = $request->email;
        $phone = '+88 0123 4567 890, +88 0123 4567 999';
        $facebook_url = 'https://www.facebook.com/';
        $instagram_url = 'https://www.instagram.com/';
        $twitter_url = 'https://www.twitter.com/';
        $pinterest_url = 'https://www.pinterest.com/';

        $email = EmailTemplate::where('name', '=', 'supplier_KYC_pending_notification')->first();

        if (isset($email)) {
            $email->description = str_replace('[CUSTOMER_NAME]', $request['first_name'] . ' ' . $request['last_name'], $email->description);
            $email->description = str_replace('[SITE_NAME]', env('WEBSITE'), $email->description);
            $email->description = str_replace('[PHONE]', $phone, $email->description);
            $email->description = str_replace('[FACEBOOK_URL]', $facebook_url, $email->description);
            $email->description = str_replace('[TWITTER_URL]', $twitter_url, $email->description);
            $email->description = str_replace('[INSTAGRAM_URL]', $instagram_url, $email->description);
            $email->description = str_replace('[PINTEREST_URL]', $pinterest_url, $email->description);
            $email->description = str_replace('[LOGO]', asset('assets/images/logo.png'), $email->description);
        }

        $emailContent = $email->description;

        Mail::send([], [], function ($message) use ($userEmail, $emailContent) {
            $message->to($userEmail)
                ->subject('Supplier - KYC Pending Notification')
                ->setBody($emailContent, 'text/html'); // for HTML rich messages
        });

        return redirect('become-supplier')->with(['status' => 'success', 'message' => trans('success.frontend|become-supplier-request|success')]);
    }
}
