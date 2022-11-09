<?php

namespace App\Http\Controllers;

use App\Http\Requests\FrontLogisticDetailsRequest;
use App\Models\DeliveryVehicleMaster;
use App\Models\LocationCity;
use App\Models\LocationCountry;
use App\Models\LocationState;
use App\Models\LogisticDetails;
use App\Models\VehicleCapacity;
use App\Models\UserDocument;
use App\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\EmailTemplate;
use App\TransporterTradingArea;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Helpers\DataGrid;
use Validator;

class FrontDriverVehicalController extends Controller
{
    use DataGrid;

    public $dataUrl = '/user/vehicle';

    // public $route = 'admin.attributes';
    public $route = 'user.vehicle';
    public $userRole = 'COMPANY';

    public function index(Request $request, LogisticDetails $logisticDetails, User $userModel)
    {

        $userdoc = new UserDocument;
        $route_doc = 'supplier.document.create';
        $curr_id = auth()->user()->uuid;
        $user = $userModel->where('uuid', $curr_id)->first();
        $userEmail = $user->email;
        $route_err = route($route_doc, $user->uuid);

        $phone = '+88 0123 4567 890, +88 0123 4567 999';
        $facebook_url = 'https://www.facebook.com/';
        $instagram_url = 'https://www.instagram.com/';
        $twitter_url = 'https://www.twitter.com/';
        $pinterest_url = 'https://www.pinterest.com/';

        if (!$userdoc->getDocumentStatus()) {
            $message = "We would like to inform you that your KYC is not completed. please complete your KYC";

            $email = EmailTemplate::where('name', '=', 'transporter_KYC_pending_notification')->first();

            if (isset($email)) {
                $email->description = str_replace('[CUSTOMER_NAME]', $user['first_name'] . ' ' . $user['last_name'], $email->description);
                $email->description = str_replace('[PHONE]', $phone, $email->description);
                $email->description = str_replace('[FACEBOOK_URL]', $facebook_url, $email->description);
                $email->description = str_replace('[TWITTER_URL]', $twitter_url, $email->description);
                $email->description = str_replace('[INSTAGRAM_URL]', $instagram_url, $email->description);
                $email->description = str_replace('[PINTEREST_URL]', $pinterest_url, $email->description);
                $email->description = str_replace('[SITE_NAME]', env('WEBSITE'), $email->description);
                $email->description = str_replace('[LOGO]', asset('assets/images/logo.png'), $email->description);
            }

            $emailContent = $email->description;

            Mail::send([], [], function ($message) use ($userEmail, $emailContent) {
                $message->to($userEmail)
                    ->subject('Transporter - KYC Pending Notification')
                    ->setBody($emailContent, 'text/html'); // for HTML rich messages
            });



            return redirect(route($route_doc))->withErrors(['status' => 'warning', 'message' => trans($message)]);
        }

        $data = LogisticDetails::where('user_id', auth()->user()->uuid)->paginate();

        $filters = [];
        $filters[] = ['title' => 'No'];

        $filters[] = [
            'title' => 'Name',
        ];

        $filters[] = [
            'title' => 'Phone',
        ];

        $filters[] = [
            'title' => 'Driver License',
        ];

        $filters[] = [
            'title' => 'Transport Type',

        ];

        $filters[] = [
            'title' => 'Vin Number',

        ];

        $filters[] = [
            'title' => 'Vehicle Name',

        ];

        $filters[] = [
            'title' => 'Status',

        ];

        $filters[] = [
            'title' => 'Edit',

        ];


        $tableName = $logisticDetails->getTable();
        $url = $this->dataUrl;
        $this->setGridModel($logisticDetails);
        $this->setGridRequest($request);
        $this->setFilters($filters);


        $this->setSorting(['sorting_field' => $tableName . '_sorting_field', 'sort' => $tableName . '_sort', 'default_field' => 'name', 'default_sort' => 'ASC']);

        $this->setGridUrl($url);

        $this->setGridVariables();

        if ($request->has('export_data')) {
            $this->setPaginationEnable(false);
            // $data = $this->getGridData();
        } else {

            // $data = $this->getGridData();

            $dataGridTitle = $this->gridTitles();
            $dataGridSearch = $this->gridSearch();
            $dataGridPagination = $this->gridPagination($data);
        }



        $route = $this->route;
        //  $redirectBackRoute = $this->redirectBackRoute;
        $role = $this->userRole;
        $pageTitle = "ALL VEHICLES";


        $data = tap($data, function ($query) {
            return $query->getCollection()->transform(function ($value) {

                $vehicle = VehicleCapacity::where('uuid', $value->vehicle_capacity_id)->select('name')->first();
                if ($vehicle != null) {
                    $value->vehicle_type = $vehicle->name;
                } else {
                    $value->vehicle_type = '';
                }

                $check = '';
                $cvalpublished = '';
                $cval = 1;
                $cvalpublished = 1;

                // $fileExist = file_exists( public_path() . $value->icon_file) ? 1 : 0;
                $onOff = '';
                $onOffpublished = '';
                if ($value->status == 1) {
                    $onOff = 'checked';
                    $cval = 0;
                }

                $check =  '<label class="switchNew">
                        <input type="checkbox" ' . $onOff . ' class="onoff" data-id="' . $value->uuid . '" data-onoff="' . $value->status . '" data-conoff="' . $cval . '" >
                        <span class="slider round"></span>
                        </label>';

                $value->switch = $check;
                // Your code here
                return $value;
            });
        });


        // dd($data);
        return view('user.vehicle.listing', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'route', 'pageTitle'));
        // return view('user.vehicle.listing', compact('logisticDetails', 'route', 'role', 'pageTitle'));
    }

    public function create(Request $request, LogisticDetails $logisticDetails, LocationCountry $locationCountryModel, LocationState $locationStateModel, LocationCity $locationCityModel,  VehicleCapacity $vehicleCapacityModel, DeliveryVehicleMaster $deliveryVehicleMaster)
    {
        $route = $this->route;
        $pageTitle = "CREATE VEHICLES";
        $vehicle_type = [];
        $transportTypes = $logisticDetails->getTransportTypesDropDown();

        $tranportInput = $request->old('transport_type');
        $bodyTypeInput = $request->old('vehicle_capacity_id');
        if (!is_null($tranportInput) && $tranportInput != 'Truck') {
            $vehicle_type = $deliveryVehicleMaster->getOtherCapacitySelect($tranportInput);
        } else if (!empty($bodyTypeInput)) {
            $vehicle_type = $deliveryVehicleMaster->where('vehicle_capacity_id', $bodyTypeInput)->select('vehicle_type', 'vehicle_type')->pluck('vehicle_type', 'vehicle_type');
        } else {
            $vehicle_type = [];
        }
        $bodyTypes = [];
        $bodyTypes = $vehicleCapacityModel->getDropDown();

        $tradingArea = \DB::table('trading_area')->where('status', '1')->get();

        $countries = $locationCountryModel->all();
        $states = [];
        $cities = [];

        $countriesAll = $locationCountryModel->get();
        $statesAll = $locationStateModel->get();
        $citiesAll = $locationCityModel->get();
        $transporterTrading = '';

        return view('user.vehicle.create', compact('pageTitle', 'logisticDetails', 'route', 'vehicle_type', 'transportTypes', 'bodyTypes', 'countries', 'states', 'cities', 'tradingArea', 'transporterTrading', 'countriesAll', 'statesAll', 'citiesAll'));
    }

    public function store(Request $request, LogisticDetails $logistic_detail)
    {

        // dd($request->all());

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
            'driving_licence' => 'required',
            'transport_type' => 'required',
            // 'vehicle_capacity_id' => 'required',
            // 'vehicle_type' => 'required',
            'vehicle_make' => 'required',
            'vehicle_registration_number' => 'required',
            'vehicle_model' => 'required',
            'vin_number' => 'required',
            'vehicle_color' => 'required',
            'trading_area' => 'required',
            'country_id' => 'required_if:trading_area,==,country',
            'state_id' => 'required_if:trading_area,==,province',
            'city_id' => 'required_if:trading_area,==,town',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $datas = $request->all();
        $data = $request->except('_method', 'hidden', 'save_exit', '_token', 'country_id', 'state_id', 'city_id');
        $data['user_id'] = auth()->user()->uuid;

        $deliveryVehicleMaster = DeliveryVehicleMaster::where('vehicle_type', $request->vehicle_type)->first();
        if ($deliveryVehicleMaster != null) {
            $data['truck_length'] = $deliveryVehicleMaster->truck_length;
            $data['truck_width'] = $deliveryVehicleMaster->truck_width;
            $data['truck_height'] = $deliveryVehicleMaster->truck_height;
            $data['truck_payload'] = $deliveryVehicleMaster->truck_payload;
            $data['truck_max_pallets'] = $deliveryVehicleMaster->truck_max_pallets;
            $data['trailer_length'] = $deliveryVehicleMaster->trailer_length;
            $data['trailer_width'] = $deliveryVehicleMaster->trailer_width;
            $data['trailer_height'] = $deliveryVehicleMaster->trailer_height;
            $data['trailer_payload'] = $deliveryVehicleMaster->trailer_payload;
            $data['trailer_max_pallets'] = $deliveryVehicleMaster->trailer_max_pallets;
            $data['body_volumn'] = $deliveryVehicleMaster->body_volumn;
            $data['combine_payload'] = $deliveryVehicleMaster->combine_payload;
            $data['combine_pallets'] = $deliveryVehicleMaster->combine_pallets;
            $data['transport_capacity'] = $deliveryVehicleMaster->capacity;
            $data['pallet_capacity_standard'] = $deliveryVehicleMaster->pallet_capacity_standard;
        }


        $dataId =  $logistic_detail->create($data);

        $country_id = (isset($datas['country_id'])) ? $datas['country_id'] : [];
        $state_id = (isset($datas['state_id'])) ? $datas['state_id'] : [];
        $city_id = (isset($datas['city_id'])) ? $datas['city_id'] : [];
        if ($request->trading_area == 'country') {

            foreach ($country_id as $country) {
                $arr = [
                    'user_id' => auth()->user()->uuid,
                    'trading_area' => $request->trading_area,
                    'area_id' => $country,
                    'transporter_vehicle_id' => $dataId->uuid
                ];
                TransporterTradingArea::create($arr);
            }
        }

        if ($request->trading_area == 'province') {
            foreach ($state_id as $country) {
                $arr = [
                    'user_id' => auth()->user()->uuid,
                    'trading_area' => $request->trading_area,
                    'area_id' => $country,
                    'transporter_vehicle_id' => $dataId->uuid
                ];
                TransporterTradingArea::create($arr);
            }
        }

        if ($request->trading_area == 'town') {
            foreach ($city_id as $country) {
                $arr = [
                    'user_id' => auth()->user()->uuid,
                    'trading_area' => $request->trading_area,
                    'area_id' => $country,
                    'transporter_vehicle_id' => $dataId->uuid
                ];
                TransporterTradingArea::create($arr);
            }
        }
        $route = $this->route;
        return redirect(route("$route.index"))->with(['status' => 'success', 'message' => trans('success.admin|logisticDetails|created')]);
    }

    /**
     * @param User $user
     * @return View
     */
    /**
     * @param Request $request
     * @param $user_uuid
     * @param LogisticDetails $logistic_detail
     * @param User $userModel
     * @param LocationCountry $locationCountryModel
     * @param LocationState $locationStateModel
     * @param LocationCity $locationCityModel
     * @return View
     */
    public function edit(Request $request, $user_uuid, LogisticDetails $logistic_detail, User $userModel, LocationCountry $locationCountryModel, LocationState $locationStateModel, LocationCity $locationCityModel, VehicleCapacity $vehicleCapacityModel, DeliveryVehicleMaster $deliveryVehicleMaster)
    {

        $userdoc = new UserDocument;
        $route_doc = 'supplier.document.create';
        $curr_id = auth()->user()->uuid;
        $user = $userModel->where('uuid', $curr_id)->first();
        $userEmail = $user->email;
        $route_err = route($route_doc, $user_uuid);

        $phone = '+88 0123 4567 890, +88 0123 4567 999';
        $facebook_url = 'https://www.facebook.com/';
        $instagram_url = 'https://www.instagram.com/';
        $twitter_url = 'https://www.twitter.com/';
        $pinterest_url = 'https://www.pinterest.com/';

        if (!$userdoc->getDocumentStatus()) {
            $message = "We would like to inform you that your KYC is not completed. please complete your KYC";

            $email = EmailTemplate::where('name', '=', 'transporter_KYC_pending_notification')->first();

            if (isset($email)) {
                $email->description = str_replace('[CUSTOMER_NAME]', $user['first_name'] . ' ' . $user['last_name'], $email->description);
                $email->description = str_replace('[PHONE]', $phone, $email->description);
                $email->description = str_replace('[FACEBOOK_URL]', $facebook_url, $email->description);
                $email->description = str_replace('[TWITTER_URL]', $twitter_url, $email->description);
                $email->description = str_replace('[INSTAGRAM_URL]', $instagram_url, $email->description);
                $email->description = str_replace('[PINTEREST_URL]', $pinterest_url, $email->description);
                $email->description = str_replace('[SITE_NAME]', env('WEBSITE'), $email->description);
                $email->description = str_replace('[LOGO]', asset('assets/images/logo.png'), $email->description);
            }

            $emailContent = $email->description;

            Mail::send([], [], function ($message) use ($userEmail, $emailContent) {
                $message->to($userEmail)
                    ->subject('Transporter - KYC Pending Notification')
                    ->setBody($emailContent, 'text/html'); // for HTML rich messages
            });



            return redirect(route($route_doc))->withErrors(['status' => 'warning', 'message' => trans($message)]);
        }


        $logisticDetails = $logistic_detail->where('uuid', $user_uuid)->first();
        $route = $this->route;
        $pageTitle = "CREATE VEHICLES";
        $vehicle_type = [];
        $transportTypes = $logistic_detail->getTransportTypesDropDown();

        if ($logisticDetails->transport_type != null) {
            $tranportInput = $logisticDetails->transport_type;
        } else {
            $tranportInput = $request->old('transport_type');
        }
        if ($logisticDetails->vehicle_capacity_id != null) {
            $bodyTypeInput = $logisticDetails->vehicle_capacity_id;
        } else {
            $bodyTypeInput = $request->old('vehicle_capacity_id');
        }



        if (!is_null($tranportInput) && $tranportInput != 'Truck') {
            $vehicle_type = $deliveryVehicleMaster->getOtherCapacitySelect($tranportInput);
        } else if (!empty($bodyTypeInput)) {
            $vehicle_type = $deliveryVehicleMaster->where('vehicle_capacity_id', $bodyTypeInput)->select('vehicle_type', 'vehicle_type')->pluck('vehicle_type', 'vehicle_type');
        } else {
            $vehicle_type = [];
        }

        $bodyTypes = [];
        $bodyTypes = $vehicleCapacityModel->getDropDown();

        $tradingArea = \DB::table('trading_area')->where('status', '1')->get();
        $transporterTrading = TransporterTradingArea::where('transporter_vehicle_id', $logisticDetails->uuid)->get()->pluck('area_id');
        // $transporterTrading = TransporterTradingArea::where('transporter_vehicle_id', $logisticDetails->uuid)->get()->pluck('area_id');
        $details = $logisticDetails;
        // $details = LogisticDetails::where('user_id', '1')->select('trading_area')->first();
        if ($details != null) {

            // dd($details->trading_area);
            if ($details->trading_area == 'country' || strtolower($details->trading_area) == 'nationals') {
                $countries = $locationCountryModel->whereIn('uuid', $transporterTrading)->get();
                $countriesAll = $locationCountryModel->get();
                $states = [];
                $cities = [];
            }

            if ($details->trading_area == 'province') {
                $countries = [];
                $states = $locationStateModel->whereIn('uuid', $transporterTrading)->get();
                foreach ($states as $city) {
                    $countries[] =  $city->country_id;
                }
                $countries = array_unique($countries);
                $countries = $locationCountryModel->whereIn('uuid', $countries)->get();
                $countriesAll = $locationCountryModel->get();
                $statesAll = $locationStateModel->get();

                $cities = [];
            }
            if ($details->trading_area == 'town') {
                $countries = [];
                $states = [];

                $cities = $locationCityModel->whereIn('uuid', $transporterTrading)->get();
                foreach ($cities as $city) {
                    $countries[] = $city->country_id;
                    $states[] = $city->state_id;
                }
                $countries = array_unique($countries);
                $countries = $locationCountryModel->whereIn('uuid', $countries)->get();
                $states = array_unique($states);
                $states = $locationStateModel->whereIn('uuid', $states)->get();

                $countriesAll = $locationCountryModel->get();
                $statesAll = $locationStateModel->get();
                $citiesAll = $locationCityModel->get();
            }

            $countriesAll = (isset($countriesAll) && count($countriesAll) != 0) ? $countriesAll : [];
            $statesAll = (isset($statesAll) && count($statesAll) != 0) ? $statesAll : [];
            $citiesAll = (isset($citiesAll) && count($citiesAll) != 0) ? $citiesAll : [];

            if (isset($countries) && count($countries) != 0) {
                $countries = $countries->pluck('uuid')->toArray();
            } else {
                $countries = [];
            }
            if (isset($states) && count($states) != 0) {
                $states = $states->pluck('uuid')->toArray();
            } else {
                $states = [];
            }
            if (isset($cities) && count($cities) != 0) {
                $cities = $cities->pluck('uuid')->toArray();
            } else {
                $cities = [];
            }
        } else {
            $countries = $locationCountryModel->all();
            $states = [];
            $cities = [];

            $countriesAll = $locationCountryModel->get();
            $statesAll = $locationStateModel->get();
            $citiesAll = $locationCityModel->get();
        }

        return view('user.vehicle.create', compact('pageTitle', 'logisticDetails', 'route', 'vehicle_type', 'transportTypes', 'bodyTypes', 'countries', 'states', 'cities', 'tradingArea', 'transporterTrading', 'countriesAll', 'statesAll', 'citiesAll'));
    }

    /**
     * @param $user_uuid
     * @param AdminLogisticDetailsRequest $request
     * @param LogisticDetails $logistic_detail
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($user_uuid, FrontLogisticDetailsRequest $request, LogisticDetails $logistic_detail)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
            'driving_licence' => 'required',
            'transport_type' => 'required',
            // 'vehicle_capacity_id' => 'required',
            // 'vehicle_type' => 'required',
            'vehicle_make' => 'required',
            'vehicle_registration_number' => 'required',
            'vehicle_model' => 'required',
            'vin_number' => 'required',
            'vehicle_color' => 'required',
            'trading_area' => 'required',
            'country_id' => 'required_if:trading_area,==,country',
            'state_id' => 'required_if:trading_area,==,province',
            'city_id' => 'required_if:trading_area,==,town',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $datas = $request->all();
        $data = $request->except('_method', 'hidden', 'save_exit', '_token', 'country_id', 'state_id', 'city_id');

        $dataNew = $logistic_detail->where('uuid', $request->uuid)->first();
        $logistic_detail->updateOrCreate(['uuid' => $request->uuid], $data);

        $country_id = (isset($datas['country_id'])) ? $datas['country_id'] : [];
        $state_id = (isset($datas['state_id'])) ? $datas['state_id'] : [];
        $city_id = (isset($datas['city_id'])) ? $datas['city_id'] : [];

        if ($request->trading_area == 'country' || $request->trading_area == 'country') {

            TransporterTradingArea::where('transporter_vehicle_id',  $request->uuid)->delete();
            foreach ($country_id as $country) {
                $arr = [
                    'user_id' => $dataNew->user_id,
                    'trading_area' => $request->trading_area,
                    'area_id' => $country,
                    'transporter_vehicle_id' => $request->uuid
                ];
                TransporterTradingArea::create($arr);
            }
        }

        if ($request->trading_area == 'province') {
            TransporterTradingArea::where('transporter_vehicle_id',  $request->uuid)->delete();
            foreach ($state_id as $country) {
                $arr = [
                    'user_id' => $dataNew->user_id,
                    'trading_area' => $request->trading_area,
                    'area_id' => $country,
                    'transporter_vehicle_id' => $request->uuid
                ];
                TransporterTradingArea::create($arr);
            }
        }

        if ($request->trading_area == 'town') {


            TransporterTradingArea::where('transporter_vehicle_id',  $request->uuid)->delete();
            foreach ($city_id as $country) {
                $arr = [
                    'user_id' => $dataNew->user_id,
                    'trading_area' => $request->trading_area,
                    'area_id' => $country,
                    'transporter_vehicle_id' => $request->uuid
                ];
                TransporterTradingArea::create($arr);
            }
        }
        $route = $this->route;

        return redirect(route("$route.edit", $user_uuid))->with(['status' => 'success', 'message' => trans('success.admin|logisticDetails|updated')]);
    }

    public function destroy($id)
    {
        $route = $this->route;

        LogisticDetails::where('uuid', $id)->delete();
        return redirect(route("$route.index"))->with(['status' => 'success', 'message' => trans('success.admin|logisticDetails|deleted')]);
    }

    public function getProvince(Request $request, LocationCountry $locationCountryModel, LocationState $locationStateModel, LocationCity $locationCityModel)
    {


        $countryInput = $request->country_id;
        $stateInput = $request->state_id;
        $cityInput = $request->city_id;
        if (gettype($countryInput) == 'array') {

            if (isset($countryInput) && count($countryInput) != 0) {
                $states = $locationStateModel->whereIn('country_id', $countryInput)->get();
                return $states;
            }
            if (isset($stateInput) && count($stateInput) != 0) {
                $cities = $locationCityModel->whereIn('state_id', $stateInput)->get();
                return $cities;
            }
        } else {

            if (isset($countryInput)) {
                $states = $locationStateModel->where('country_id', $countryInput)->get();
                return $states;
            }
            if (isset($stateInput)) {
                $cities = $locationCityModel->where('state_id', $stateInput)->get();
                return $cities;
            }
        }
    }


    public function getTowns($id, LocationCountry $locationCountryModel, LocationState $locationStateModel, LocationCity $locationCityModel)
    {
    }

    public function vehicleStatus($id)
    {
        $data = LogisticDetails::where('uuid', $id)->select('status')->first();
        if ($data->status == 1) {
            LogisticDetails::where('uuid', $id)->update(['status' => '0']);
        } else {
            LogisticDetails::where('uuid', $id)->update(['status' => '1']);
        }
        return $data;
    }
}
