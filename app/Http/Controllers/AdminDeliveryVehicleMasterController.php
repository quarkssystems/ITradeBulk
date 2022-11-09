<?php



namespace App\Http\Controllers;



use App\Exports\DataGridExport;

use App\Http\Controllers\Helpers\BaseController;

use App\Http\Controllers\Helpers\DataGrid;

use App\Http\Requests\AdminDeliveryVehicleMasterRequest;

use App\Models\Category;

use App\Models\DeliveryVehicleMaster;

use App\Models\VehicleCapacity;
use App\TransportType;
use Illuminate\Http\Request;

use Illuminate\View\View;

use Maatwebsite\Excel\Excel;

use Session;


class AdminDeliveryVehicleMasterController extends Controller

{

    use DataGrid;

    use BaseController;



    public $route = 'admin.delivery-vehicle-master';



    public function getQuickActionModel()

    {

        return new DeliveryVehicleMaster();
    }



    /**

     * @param Request $request

     * @param Category $categoryModel

     * @param Excel $excel

     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|Excel|\Symfony\Component\HttpFoundation\BinaryFileResponse

     * @throws \PhpOffice\PhpSpreadsheet\Exception

     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception

     */

    public function index(Request $request, DeliveryVehicleMaster $deliveryVehicleMasterModel, Excel $excel)

    {

        $filters = [];

        $filters[] = ['title' => 'No'];



        $filters[] = [

            'title' => 'Vehicle type',

            'column' => 'vehicle_type',

            'operator' => 'LIKE',

            'sorting' => true,

            'search' => [

                'type' => 'text',

                'placeholder' => 'Search vehicle type'

            ]

        ];

        $filters[] = [

            'title' => 'Capacity',

            'column' => 'capacity',

            'operator' => 'LIKE',

            'sorting' => true,

            'search' => [

                'type' => 'text',

                'placeholder' => 'Search capacity'

            ]

        ];



        $filters[] = [

            'title' => 'Pallet Capacity',

            'column' => 'pallet_capacity_standard',

            'operator' => 'LIKE',

            'sorting' => true,

            'search' => [

                'type' => 'text',

                'placeholder' => 'Search capacity'

            ]

        ];



        $filters[] = [

            'title' => 'Price per KM',

            'column' => 'price_per_km',

            'operator' => '=',

            'sorting' => true,

            'search' => [

                'type' => 'text',

                'placeholder' => 'Search price',

            ]

        ];



        $filters[] = [

            'title' => 'Action'

        ];



        $tableName = $deliveryVehicleMasterModel->getTable();

        $url = route($this->route . ".index");

        $this->setGridModel($deliveryVehicleMasterModel);

        $this->setGridRequest($request);

        $this->setFilters($filters);



        $this->setSorting(['sorting_field' => $tableName . '_sorting_field', 'sort' => $tableName . '_sort', 'default_field' => 'created_at', 'default_sort' => 'DESC']);



        $this->setGridUrl($url);



        $this->setGridVariables();



        if ($request->has('export_data')) {

            $this->setPaginationEnable(false);

            $data = $this->getGridData();
        } else {

            $data = $this->getGridData();

            $dataGridTitle = $this->gridTitles();

            $dataGridSearch = $this->gridSearch();

            $dataGridPagination = $this->gridPagination($data);
        }



        if ($request->has('export_data')) {

            $fileName = 'CATEGORY_DATA';

            return $excel->download(new DataGridExport('admin.category.export', $data), "$fileName.xlsx");
        }





        $route = $this->route;





        $pageTitle = "MANAGE Delivery Vehicle";

        if (Session::has('AdminDeliveryVehicleMasterPage')) {
            Session::forget('AdminDeliveryVehicleMasterPage');
        }
        Session::put('AdminDeliveryVehicleMasterPage', $request->input('page') ?? 1);

        if ($request->ajax()) {

            return view('admin.deliveryVehicleMaster.grid', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'route'));
        } else {

            return view('admin.deliveryVehicleMaster.index', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'pageTitle', 'route'));
        }
    }



    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function create(DeliveryVehicleMaster $delivery_vehicle_master, VehicleCapacity $vehicle_capacity_master): View

    {

        $pageTitle = "CREATE VEHICLE MASTER";

        $route = $this->route;

        $bodyTypes = $vehicle_capacity_master->getDropDown();

        $transportTypes = $delivery_vehicle_master->getTransportTypesDropDown();

        return view('admin.deliveryVehicleMaster.form', compact('delivery_vehicle_master', 'pageTitle', 'route', 'bodyTypes', 'transportTypes'));
    }



    /**

     * Store a newly created resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\Response

     */

    public function store(AdminDeliveryVehicleMasterRequest $request, DeliveryVehicleMaster $delivery_vehicle_master)

    {



        if ($request->transport_type != 'Truck') {

            // unset($request->vehicle_capacity_id);

            $request->request->remove('vehicle_capacity_id');
        }



        $deliveryVehicleMasterModel = $delivery_vehicle_master->create($request->all());

        $route = $this->route;



        $redirectRoute = route("$route.index");

        if ($request->has('save_continue')) {

            $redirectRoute = route("$route.edit", $deliveryVehicleMasterModel->uuid);
        }

        return redirect($redirectRoute)->with(['status' => 'success', 'message' => trans('success.admin|deliveryVehicleMaster|created')]);
    }



    /**

     * Display the specified resource.

     *

     * @param  \App\Models\DeliveryVehicleMaster  $deliveryVehicleMaster

     * @return \Illuminate\Http\Response

     */

    public function show(DeliveryVehicleMaster $deliveryVehicleMaster)

    {

        //

    }



    /**

     * Show the form for editing the specified resource.

     *

     * @param  \App\Models\DeliveryVehicleMaster  $deliveryVehicleMaster

     * @return \Illuminate\Http\Response

     */

    public function edit(DeliveryVehicleMaster $delivery_vehicle_master, VehicleCapacity $vehicle_capacity_master): View

    {

        $route = $this->route;

        $copy = request()->has('copy') ? true : false;

        $pageTitle = $copy ? "COPY VEHICLE MASTER" : "EDIT VEHICLE MASTER";

        $bodyTypes = $vehicle_capacity_master->getDropDown();

        $transportTypes = $delivery_vehicle_master->getTransportTypesDropDown();

        return view('admin.deliveryVehicleMaster.form', compact('delivery_vehicle_master', 'pageTitle', 'route', 'copy', 'bodyTypes', 'transportTypes'));
    }



    /**

     * Update the specified resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  \App\Models\DeliveryVehicleMaster  $deliveryVehicleMaster

     * @return \Illuminate\Http\Response

     */

    public function update(AdminDeliveryVehicleMasterRequest $request, DeliveryVehicleMaster $delivery_vehicle_master)

    {

        $delivery_vehicle_master->update($request->all());

        $route = $this->route;



        $redirectRoute = route("$route.index");

        if ($request->has('save_continue')) {

            $redirectRoute = route("$route.edit", $delivery_vehicle_master->uuid);
        } elseif (Session::has('AdminDeliveryVehicleMasterPage')) {
            // echo 11;die;
            $page = session()->get("AdminDeliveryVehicleMasterPage");
            // $redirectRoute = ;
            return redirect()->route("$route.index", ['page' => $page])->with(['status' => 'success', 'message' => trans('success.admin|deliveryVehicleMaster|updated')]);
        }

        return redirect($redirectRoute)->with(['status' => 'success', 'message' => trans('success.admin|deliveryVehicleMaster|updated')]);
    }



    /**

     * Remove the specified resource from storage.

     *

     * @param  \App\Models\DeliveryVehicleMaster  $deliveryVehicleMaster

     * @return \Illuminate\Http\Response

     */

    public function destroy(DeliveryVehicleMaster $delivery_vehicle_master)

    {

        $route = $this->route;

        if ($delivery_vehicle_master->canDelete()) {

            try {

                $delivery_vehicle_master->delete();

                return redirect(route("$route.index"))->with(['status' => 'success', 'message' => trans('success.admin|deliveryVehicleMaster|deleted')]);
            } catch (\Exception $exception) {

                return redirect(route("$route.index"))->with(['status' => 'warning', 'message' => trans('warning.admin|deliveryVehicleMaster|deleteNotPossible')]);
            }
        } else {

            return redirect(route("$route.index"))->with(['status' => 'warning', 'message' => trans('warning.admin|deliveryVehicleMaster|deleteNotPossible')]);
        }
    }
}
