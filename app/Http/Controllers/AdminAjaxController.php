<?php



namespace App\Http\Controllers;



use App\User;

use App\Models\SalesOrder;

use App\Models\DeliveryVehicleMaster;

use App\Models\VehicleCapacity;

use App\Models\LocationCity;

use App\Models\LocationState;

use App\Models\LocationZipcode;
use App\Models\Category;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\General\ChangeOrderStatus;
use App\Models\Basket;

class AdminAjaxController extends Controller

{

    /**

     * @param Request $request

     * @param LocationState $locationStateModel

     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View

     */

    public function postGetStates(Request $request, LocationState $locationStateModel)

    {

        if ($request->has('country_id') && !empty($request->get('country_id'))) {

            $locationStateModel->setCountryUUID($request->country_id);

            $states = $locationStateModel->ofCountry()->orderBy('state_name')->pluck('state_name', 'uuid');
        } else {

            $states = [];
        }

        $viewFile = 'admin.helpers.ajax.locationStateDropdown';

        if ($request->has('view_file') && !empty($request->get('view_file'))) {

            $viewFile = $request->get('view_file');
        }

        return view($viewFile, compact('states'));
    }



    public function postGetCities(Request $request, LocationCity $locationCityModel)

    {

        if ($request->has('state_id') && !empty($request->get('state_id'))) {

            $locationCityModel->setStateUUID($request->state_id);

            $cities = $locationCityModel->ofState()->orderBy('city_name')->pluck('city_name', 'uuid');
        } else {

            $cities = [];
        }

        $viewFile = 'admin.helpers.ajax.locationCityDropdown';

        if ($request->has('view_file') && !empty($request->get('view_file'))) {

            $viewFile = $request->get('view_file');
        }

        return view($viewFile, compact('cities'));
    }



    public function postGetAreas(Request $request, LocationZipcode $locationAreaModel)

    {

        if ($request->has('city_id') && !empty($request->get('city_id'))) {

            $locationAreaModel->setCityUUID($request->city_id);

            $zipcodes = $locationAreaModel->ofCity()

                ->select(DB::raw("CONCAT(zipcode,'-',zipcode_name) AS zip"), 'uuid')->orderBy('zipcode_name')->pluck('zip', 'uuid');
        } else {

            $zipcodes = [];
        }



        $viewFile = 'admin.helpers.ajax.locationZipcodeDropdown';

        if ($request->has('view_file') && !empty($request->get('view_file'))) {

            $viewFile = $request->get('view_file');
        }

        return view($viewFile, compact('zipcodes'));
    }



    public function postGetCapacity(Request $request, DeliveryVehicleMaster $deliveryVehicleMasterModel)

    {

        if ($request->has('vehicle_capacity_id') && !empty($request->get('vehicle_capacity_id'))) {

            $deliveryVehicleMasterModel->setVehicleCapacityUuid($request->vehicle_capacity_id);

            $vehicle_type = $deliveryVehicleMasterModel->ofVehicleCapacity()

                ->select('capacity', 'vehicle_type')->orderBy('vehicle_type')->pluck('vehicle_type', 'vehicle_type');
        } else {

            $vehicle_type = [];
        }



        $viewFile = 'admin.helpers.ajax.CapacityDropDown';

        if ($request->has('view_file') && !empty($request->get('view_file'))) {

            $viewFile = $request->get('view_file');
        }

        return view($viewFile, compact('vehicle_type'));
    }





    public function postGetCapacityData(Request $request, DeliveryVehicleMaster $deliveryVehicleMasterModel)

    {

        if ($request->has('transport_type') && !empty($request->get('transport_type')) && $request->get('transport_type') != 'Truck') {

            $vehicle_type = $deliveryVehicleMasterModel->getOtherCapacitySelect($request->get('transport_type'));
        } else {

            $vehicle_type = [];
        }



        $viewFile = 'admin.helpers.ajax.CapacityDropDown';

        if ($request->has('view_file') && !empty($request->get('view_file'))) {

            $viewFile = $request->get('view_file');
        }

        return view($viewFile, compact('vehicle_type'));
    }







    public function postGetPalletCapacity(Request $request, DeliveryVehicleMaster $deliveryVehicleMasterModel)

    {

        if ($request->has('vehicle_capacity_id') && !empty($request->get('vehicle_capacity_id'))) {

            $capacity_data =   $deliveryVehicleMasterModel->where('vehicle_capacity_id', $request->get('vehicle_capacity_id'))->select('capacity', 'pallet_capacity_standard')->first();

            $capacity['pallet_capacity_standard'] = $capacity_data->pallet_capacity_standard;

            $capacity['capacity'] = $capacity_data->capacity;
        } else {

            $capacity = 0;
        }





        return  json_encode($capacity);
    }



    public function postGetPallet(Request $request, DeliveryVehicleMaster $deliveryVehicleMasterModel)

    {



        if ($request->has('transport_capacity') && !empty($request->get('transport_capacity'))) {



            $capacity_data =   $deliveryVehicleMasterModel->where('vehicle_type', $request->get('transport_capacity'))->select('capacity', 'pallet_capacity_standard')->first();

            $capacity['pallet_capacity_standard'] = $capacity_data->pallet_capacity_standard;

            $capacity['capacity'] = $capacity_data->capacity;
        } else {

            $capacity = 0;
        }





        return  json_encode($capacity);
    }



    public function updateOrderStatus(Request $request, SalesOrder $salesOrderModel)

    {



        $status = $request->get('order_status');

        $order_uuid = $request->get('order_id');



        $orderData = $salesOrderModel->where('uuid', $order_uuid)->first();





        /***********************Notification to chanage status ************************/



        $req = array();

        $req['order_status'] = $status;

        $req['order_uuid'] = $order_uuid;

        $req['user_id'] = auth()->user()->uuid;

        $req['delivery_type'] = $orderData->delivery_type;


        ChangeOrderStatus::orderStatus($req);



        /***********************Notification to chanage status ************************/



        /*  if($status == 'PACKED'){

 

            if($orderData)

            {

                $req_data = array();

                $req_data['order_uuid'] = $orderData->uuid;

                $req_data['supplier_id'] = $orderData->supplier_id;

                $req_data['vendor_id'] = $orderData->user_id;



                if($orderData->delivery_type == 'pickup')

                   {

                    ChangeOrderStatus::orderPickupPacked($req_data);   

                   }

                else

                {

                    ChangeOrderStatus::orderDeliveryPacked($req_data);   

                } 

            }  

        }    

    */

        return $request->get('order_status');
    }



    public function getAddress(Request $request, User $user) //Company user same address of selected company

    {

        $response['latitude'] = '';

        $response['longitude'] = '';

        if ($request->has('company_uuid') && !empty($request->get('company_uuid'))) {

            $data =  $user->where('uuid', $request->get('company_uuid'))->select('latitude', 'longitude')->first();

            $response['latitude'] = $data->latitude;

            $response['longitude'] = $data->longitude;
        }



        return json_encode($response);
    }



    public function postGetCategory(Request $request, Category $category)

    {

        if ($request->has('category_id') && !empty($request->get('category_id'))) {

            $categoryId = [];
            $categoryData = $category->where('parent_category_id', $request->category_id)->pluck('name', 'uuid')->toArray();
            foreach ($categoryData as $key => $data) {
                // dd($data,$key);
                $categoryId[$key]['uuid'] = $key;
                $categoryId[$key]['name'] = $data;
            }
            // $locationCityModel->setStateUUID($request->state_id);

            // $categoryId = $locationCityModel->ofState()->orderBy('city_name')->pluck('city_name', 'uuid');

        } else {

            $categoryId = [];
        }

        $viewFile = 'admin.product.form';

        // if($request->has('view_file') && !empty($request->get('view_file')))

        // {

        //     $viewFile = $request->get('view_file');

        // }
        // dd($categoryId);
        return $categoryId;
        // return view($viewFile, compact('categoryId'));

    }
}
