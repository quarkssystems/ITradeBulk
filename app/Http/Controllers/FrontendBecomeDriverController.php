<?php

namespace App\Http\Controllers;

use App\Http\Requests\FrontendBecomeDriverRequest;
use App\Http\Requests\FrontendBecomeSupplierRequest;
use App\Models\Banner;
use App\Models\LocationCity;
use App\Models\LocationCountry;
use App\Models\LocationState;
use App\Models\LogisticDetails;
use App\Models\UserCompany;
use App\Models\UserDocument;
use App\Models\DeliveryVehicleMaster;
use App\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Mail;
use DB;

class FrontendBecomeDriverController extends Controller
{
    public $userRole = 'DRIVER';
    public $route = 'become-driver';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function index(Request $request, User $user, LogisticDetails $logisticDetailsModel, LocationCountry $locationCountryModel, LocationState $locationStateModel, LocationCity $locationCityModel, UserDocument $userDocumentModel): View
    {
        $type = array();
        $gender = $user->getGenderDropDown();
        $title = $user->getTitleDropDown();
        $roles = $user->getRoleDropDown();
        $status = $user->getStatusesDropDown();

        $transportTypes = $logisticDetailsModel->getTransportTypesDropDown();
        $workTypes = $logisticDetailsModel->getWorkTypesDropDown();
        $availabilityTypes = $logisticDetailsModel->getAvailabilityTypesDropDown();

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
        $pageTitle = "Become Transporter";
        $breadcrumb = [
            'home' => 'Home',
            'become-driver' => $pageTitle
        ];

        $deliveryVehicleMaster = DeliveryVehicleMaster::all();
        // dd($deliveryVehicleMaster);

        $tradingArea = DB::table('trading_area')->where('status', '1')->get();

        return view('frontend.becomeDriver.form', compact('user', 'gender', 'title', 'type', 'roles', 'status', 'pageTitle', 'route', 'role', 'breadcrumb', 'countries', 'states', 'cities', 'zipcodes', 'foundingYears', 'documentTypes', 'transportTypes', 'workTypes', 'availabilityTypes', 'banner', 'deliveryVehicleMaster', 'tradingArea'));
    }

    public function store(FrontendBecomeDriverRequest $request, User $user)
    {
        $logisticDetails = $request->get('logisticDetails');
        $logisticDetails = array_merge($logisticDetails, [
            'zipcode_id' => $request->get('zipcode_id'),
            'city_id' => $request->get('city_id'),
            'state_id' => $request->get('state_id'),
            'country_id' => $request->get('country_id')
        ]);

        if ($request->get('logistic_type') == 'COMPANY') {
            $this->userRole = 'COMPANY';
        }

        $request->merge(['status' => 'INACTIVE', 'role' => $this->userRole, 'password' => bcrypt($request->get("password"))]);



        $userData = $user->create($request->all());
        $userData->logisticDetails()->create($logisticDetails);
        $userData->sendEmailVerificationNotification();

        //TODO: Send Verification email + confirmation mail that we got your request

        $role = $request->logistic_type;
        $userEmail = $request->email;
        $phone = '+88 0123 4567 890, +88 0123 4567 999';
        $facebook_url = 'https://www.facebook.com/';
        $instagram_url = 'https://www.instagram.com/';
        $twitter_url = 'https://www.twitter.com/';
        $pinterest_url = 'https://www.pinterest.com/';


        if ($role = 'INDIVIDUAL') {
            $email = EmailTemplate::where('name', '=', 'transporter_KYC_pending_notification')->first();
        } else {
            $email = EmailTemplate::where('name', '=', 'transport_company_KYC_pending_notification')->first();
        }


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
                ->subject('Transporter - KYC Pending Notification')
                ->setBody($emailContent, 'text/html'); // for HTML rich messages
        });



        return redirect('become-driver')->with(['status' => 'success', 'message' => trans('success.frontend|become-driver-request|success')]);
    }
}
