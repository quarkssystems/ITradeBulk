<?php

namespace App\Http\Controllers;

use App\TransportType;
use Illuminate\Http\Request;
use App\Exports\DataGridExport;
use App\Http\Controllers\Helpers\BaseController;
use App\Http\Controllers\Helpers\DataGrid;
use App\Http\Requests\AdminDeliveryVehicleMasterRequest;
use App\Models\Category;
use App\Models\DeliveryVehicleMaster;
use App\Models\VehicleCapacity;
use Illuminate\View\View;
use Maatwebsite\Excel\Excel;
use Session;
use Illuminate\Support\Facades\Validator;

class TransportTypeController extends Controller
{
    use DataGrid;

    use BaseController;



    public $route = 'admin.transport-type';



    public function getQuickActionModel()

    {

        return new TransportType();

    }



    /**

     * @param Request $request

     * @param Category $categoryModel

     * @param Excel $excel

     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|Excel|\Symfony\Component\HttpFoundation\BinaryFileResponse

     * @throws \PhpOffice\PhpSpreadsheet\Exception

     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception

     */

    public function index(Request $request, TransportType $transportTypeModel, Excel $excel)

    {

        $filters = [];

        $filters[] = ['title' => 'No'];



        $filters[] = [

            'title' => 'type',

            'column' => 'type',

            'operator' => 'LIKE',

            'sorting' => true,

            'search' => [

                'type' => 'text',

                'placeholder' => 'Search type'

            ]

        ];


        $filters[] = [

            'title' => 'Action'

        ];



        $tableName = $transportTypeModel->getTable();

        $url = route($this->route. ".index");

        $this->setGridModel($transportTypeModel);

        $this->setGridRequest($request);

        $this->setFilters($filters);



        $this->setSorting(['sorting_field' => $tableName.'_sorting_field', 'sort' => $tableName.'_sort', 'default_field' => 'created_at', 'default_sort' => 'DESC']);



        $this->setGridUrl($url);



        $this->setGridVariables();



        if($request->has('export_data'))

        {

            $this->setPaginationEnable(false);

            $data = $this->getGridData();

        }

        else

        {

            $data = $this->getGridData();

            $dataGridTitle = $this->gridTitles();

            $dataGridSearch = $this->gridSearch();

            $dataGridPagination = $this->gridPagination($data);

        }


        $route = $this->route;

        $pageTitle = "MANAGE VEHICLE TYPE";
        // $pageTitle = "MANAGE TRANSPORT TYPE";

        if(Session::has('AdminTransportTypePage')){
            Session::forget('AdminTransportTypePage');
        }
        Session::put('AdminTransportTypePage', $request->input('page') ?? 1);

        if ($request->ajax()) {

            return view('admin.transportType.grid', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'route'));

        } else {

            return view('admin.transportType.index', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'pageTitle', 'route'));

        }

    }



    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function create(TransportType $transportTypeModel) : View

    {

        $pageTitle = "CREATE VEHICLE TYPE";

        $route = $this->route;

        $transportTypes = $transportTypeModel->get();

        return view('admin.transportType.form', compact('transportTypes', 'pageTitle', 'route'));

    }



    /**

     * Store a newly created resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\Response

     */

    public function store(Request $request, TransportType $transportTypeModel)

    {

        $validator = Validator::make($request->all(), [
            'type' => 'required|unique:transport_types,type|max:255',
        ]);
 
        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }
 
        $transportType = $transportTypeModel->create($request->all());

        $route = $this->route;

        $redirectRoute = route("$route.index");

        if($request->has('save_continue'))

        {

            $redirectRoute = route("$route.edit", $transportType->uuid);

        }

        return redirect($redirectRoute)->with(['status' => 'success', 'message' => 'Vehicle type created successfully']);

    }



    /**

     * Display the specified resource.

     *

     * @param  \App\Models\DeliveryVehicleMaster  $deliveryVehicleMaster

     * @return \Illuminate\Http\Response

     */

    public function show(TransportType $deliveryVehicleMaster)

    {

        //

    }



    /**

     * Show the form for editing the specified resource.

     *

     * @param  \App\Models\TransportType  $transportType

     * @return \Illuminate\Http\Response

     */

    public function edit(TransportType $transportTypeModel) : View

    {
        
        $route = $this->route;

        $copy = request()->has('copy') ? true : false;

        $pageTitle = $copy ? "COPY VEHICLE TYPE" : "EDIT VEHICLE TYPE";

        $transportTypes = $transportTypeModel->first();

        return view('admin.transportType.form', compact('transportTypes', 'pageTitle', 'route', 'copy'));

    }



    /**

     * Update the specified resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  \App\Models\TransportType  $deliveryVehicleMaster

     * @return \Illuminate\Http\Response

     */

    public function update(Request $request, TransportType $transportType)

    {
        $validator = Validator::make($request->all(), [
            'type' => 'required',
        ]);
 
        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $transportType->update($request->all());

        $route = $this->route;



        $redirectRoute = route("$route.index");

        if($request->has('save_continue'))

        {

            $redirectRoute = route("$route.edit", $transportType->uuid);

        }

        return redirect($redirectRoute)->with(['status' => 'success', 'message' => 'Vehicle type updated successfully']);

    }



    /**

     * Remove the specified resource from storage.

     *

     * @param  \App\Models\TransportType  $transportType

     * @return \Illuminate\Http\Response

     */

    public function destroy(TransportType $transportType)

    {

        $route = $this->route;

            try{

                $transportType->delete();

                return redirect(route("$route.index"))->with(['status' => 'success', 'message' => 'Vehicle type deleted successfully']);



            }catch (\Exception $exception)

            {

                return redirect(route("$route.index"))->with(['status' => 'warning', 'message' => 'You can not delete this Vehicle type']);

            }

    }

}