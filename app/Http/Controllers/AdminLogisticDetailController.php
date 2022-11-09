<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\DataGrid;
use App\Http\Requests\AdminLogisticDetailsRequest;
use App\Models\DeliveryVehicleMaster;
use App\Models\LocationCity;
use App\Models\LocationCountry;
use App\Models\LocationState;
use App\Models\LogisticDetails;
use App\Models\VehicleCapacity;
use App\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminLogisticDetailController extends Controller
{
    use DataGrid;

    public $userRole = 'LOGISTICS';
    public $route = 'admin.logistic-detail';
    public $redirectRoute = 'admin.users.edit';
    public $redirectBackRoute = 'admin.manage-logistic.index';
    public $navTab = 'admin.users.logistic.navTab';
    public $dataUrl = '/admin/logistic-detail';

    /**
     * @param $user_uuid
     * @return string
     */
    public function index(Request $request, LogisticDetails $logisticDetails, $user_uuid, User $userModel)
    {
        $data = LogisticDetails::where('user_id', $user_uuid)->paginate();

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

        $pageTitle = 'TRANSPORTER VEHICLE';
        $navTab = $this->navTab;
        $user = $userModel->where('uuid', $user_uuid)->first();

        // if ($request->ajax()) {
        return view('admin.logisticDetails.grid', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'route', 'pageTitle', 'navTab', 'user'));
        // } else {
        //     return view('admin.logisticDetails.index', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'pageTitle', 'route'));
        // }
        // return redirect()->route($this->redirectRoute, $user_uuid);
    }

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
    public function create(Request $request, $user_uuid, LogisticDetails $logistic_detail, User $userModel, LocationCountry $locationCountryModel, LocationState $locationStateModel, LocationCity $locationCityModel, VehicleCapacity $vehicleCapacityModel, DeliveryVehicleMaster $deliveryVehicleMaster): View
    {

        $states = [];
        $cities = [];
        $zipcodes = [];
        $bodyTypes = [];
        $vehicle_type = [];

        $transportTypes = $logistic_detail->getTransportTypesDropDown();
        $transportTypes = $logistic_detail->getTransportTypesDropDown();
        $workTypes = $logistic_detail->getWorkTypesDropDown();
        $availabilityTypes = $logistic_detail->getAvailabilityTypesDropDown();

        $logistic_detail = $logistic_detail->where('user_id', $user_uuid)->first();

        $countries = $locationCountryModel->getDropdown();
        $bodyTypes = $vehicleCapacityModel->getDropDown();



        $countryInput = $request->old('country_id');
        $stateInput = $request->old('state_id');
        $cityInput = $request->old('city_id');
        $tranportInput = $request->old('transport_type');
        $bodyTypeInput = $request->old('vehicle_capacity_id');
        $transport_capacity = $request->old('transport_capacity');


        if (!is_null($countryInput)) {
            $states = $locationCountryModel->where('uuid', $countryInput)->first()->getStateDropDown();
        }
        if (!is_null($stateInput)) {
            $cities = $locationStateModel->where('uuid', $stateInput)->first()->getCityDropDown();
        }
        if (!is_null($cityInput)) {
            $zipcodes = $locationCityModel->where('uuid', $cityInput)->first()->getZipcodeDropDown();
        }

        if (!is_null($tranportInput)) {
            $bodyTypes = $vehicleCapacityModel->getDropDown();
        }


        if (!is_null($tranportInput) && $tranportInput != 'Truck') {
            $vehicle_type = $deliveryVehicleMaster->getOtherCapacitySelect($tranportInput);
        } else if (!empty($bodyTypeInput)) {
            $vehicle_type = $deliveryVehicleMaster->where('vehicle_capacity_id', $bodyTypeInput)->select('vehicle_type', 'vehicle_type')->pluck('vehicle_type', 'vehicle_type');
        } else {
            $vehicle_type = [];
        }


        $route = $this->route;
        $redirectBackRoute = $this->redirectBackRoute;
        $role = $this->userRole;
        $pageTitle = "CREATE DETAIL";
        $user = $userModel->where('uuid', $user_uuid)->first();
        $navTab = $this->navTab;
        return view('admin.logisticDetails.form', compact('user', 'logistic_detail', 'pageTitle', 'route', 'role', 'navTab', 'redirectBackRoute', 'countries', 'states', 'cities', 'zipcodes', 'transportTypes', 'workTypes', 'availabilityTypes', 'bodyTypes', 'vehicle_type'));
    }

    /**
     * @param $user_uuid
     * @param AdminLogisticDetailsRequest $request
     * @param LogisticDetails $logistic_detail
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store($user_uuid, AdminLogisticDetailsRequest $request, LogisticDetails $logistic_detail)
    {
        $logisticDetailsData = $logistic_detail->create($request->all());
        $route = $this->route;
        $redirectRoute = route($this->redirectBackRoute);
        if ($request->has('save_continue')) {
            $redirectRoute = route("$route.edit", [$user_uuid, $logisticDetailsData->uuid]);
        }
        return redirect($redirectRoute)->with(['status' => 'success', 'message' => trans('success.admin|logisticDetails|created')]);
    }

    /**
     * @param $user_uuid
     * @param LogisticDetails $logistic_detail
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show($user_uuid, LogisticDetails $logistic_detail)
    {
        return redirect()->route($this->redirectRoute, $user_uuid);
    }

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
    public function edit(Request $request, $user_uuid, LogisticDetails $logistic_detail, User $userModel, LocationCountry $locationCountryModel, LocationState $locationStateModel, LocationCity $locationCityModel, VehicleCapacity $vehicleCapacityModel, DeliveryVehicleMaster $deliveryVehicleMaster): View
    {

        $countries = $locationCountryModel->getDropdown();
        $states = [];
        $cities = [];
        $zipcodes = [];
        $bodyTypes = [];
        $vehicle_type = [];

        if ($logistic_detail->country_id != "") {
            $countryInput = $request->has('country_id') ? $request->get('country_id') : $logistic_detail->country_id;
        } else {
            $countryInput = null;
        }
        if ($logistic_detail->state_id != "") {
            $stateInput = $request->has('state_id') ? $request->get('state_id') : $logistic_detail->state_id;
        } else {
            $stateInput = null;
        }
        if ($logistic_detail->city_id != "") {
            $cityInput = $request->has('city_id') ? $request->get('city_id') : $logistic_detail->city_id;
        } else {
            $cityInput = null;
        }

        $tranportInput = $request->has('transport_type') ? $request->get('transport_type') : $logistic_detail->transport_type;
        $bodyTypeInput = $request->has('vehicle_capacity_id') ? $request->get('vehicle_capacity_id') : $logistic_detail->vehicle_capacity_id;
        $transport_capacity = $request->has('transport_capacity') ? $request->get('transport_capacity') : $logistic_detail->transport_capacity;

        $pallet_capacity_standard = $request->has('pallet_capacity_standard') ? $request->get('pallet_capacity_standard') : $logistic_detail->pallet_capacity_standard;


        if (!is_null($countryInput)) {
            $states = $locationCountryModel->where('uuid', $countryInput)->first()->getStateDropDown();
        }
        if (!is_null($stateInput)) {
            $cities = $locationStateModel->where('uuid', $stateInput)->first()->getCityDropDown();
        }
        if (!is_null($cityInput)) {
            $zipcodes = $locationCityModel->where('uuid', $cityInput)->first()->getZipcodeDropDown();
        }

        if (!is_null($tranportInput)) {
            $bodyTypes = $vehicleCapacityModel->getDropDown();
        }

        if (!is_null($tranportInput) && $tranportInput != 'Truck') {

            $vehicle_type = $deliveryVehicleMaster->getOtherCapacitySelect($tranportInput);
        } else if (!empty($bodyTypeInput)) {


            $vehicle_type = $deliveryVehicleMaster->where('vehicle_capacity_id', $bodyTypeInput)->select('vehicle_type', 'vehicle_type')->pluck('vehicle_type', 'vehicle_type');
        } else {
            $vehicle_type = [];
        }


        $transportTypes = $logistic_detail->getTransportTypesDropDown();
        $workTypes = $logistic_detail->getWorkTypesDropDown();
        $availabilityTypes = $logistic_detail->getAvailabilityTypesDropDown();

        $route = $this->route;
        $redirectBackRoute = $this->redirectBackRoute;
        $role = $this->userRole;
        $pageTitle = "EDIT DETAIL";
        $user = $userModel->where('uuid', $user_uuid)->first();
        $navTab = $this->navTab;


        $delivery_vehicle_master = $logistic_detail->where('user_id', $user_uuid)->first();
        //dd( $capacities );

        return view('admin.logisticDetails.form', compact('user', 'logistic_detail', 'pageTitle', 'route', 'role', 'navTab', 'redirectBackRoute', 'countries', 'states', 'cities', 'zipcodes', 'transportTypes', 'workTypes', 'availabilityTypes', 'bodyTypes', 'vehicle_type', 'delivery_vehicle_master'));
    }

    /**
     * @param $user_uuid
     * @param AdminLogisticDetailsRequest $request
     * @param LogisticDetails $logistic_detail
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($user_uuid, AdminLogisticDetailsRequest $request, LogisticDetails $logistic_detail)
    {

        /*dd($logistic_detail);
        dd($request->all());*/

        $logistic_detail->update($request->all());
        $route = $this->route;
        $redirectRoute = route($this->redirectBackRoute);
        if ($request->has('save_continue')) {
            $redirectRoute = route("$route.edit", [$user_uuid, $logistic_detail->uuid]);
        }
        return redirect($redirectRoute)->with(['status' => 'success', 'message' => trans('success.admin|logisticDetails|updated')]);
    }

    /**
     * @param $user_uuid
     * @param LogisticDetails $logistic_detail
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($user_uuid, LogisticDetails $logistic_detail)
    {
        $logistic_detail->delete();
        $redirectRoute = route($this->redirectBackRoute);
        return redirect($redirectRoute)->with(['status' => 'success', 'message' => trans('success.admin|logisticDetails|deleted')]);
        // return redirect()->route($this->redirectRoute, $user_uuid);
    }
}
