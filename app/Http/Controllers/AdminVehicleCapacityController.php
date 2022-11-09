<?php



namespace App\Http\Controllers;



use App\Exports\DataGridExport;

use App\Http\Controllers\Helpers\DataGrid;

use App\Http\Requests\AdminTeamRequest;

use App\Http\Requests\AdminVehicleCapacityRequest;

use App\Models\Category;

use App\Models\Team;

use App\Models\VehicleCapacity;

use Illuminate\Http\Request;

use Illuminate\View\View;

use Maatwebsite\Excel\Excel;

use Session;


class AdminVehicleCapacityController extends Controller

{

    use DataGrid;



    public $route = 'admin.vehicle-capacity';



    /**

     * @param Request $request

     * @param Team $team

     * @param Excel $excel

     * @return \Illuminate\Contracts\View\Factory|View|Excel|\Symfony\Component\HttpFoundation\BinaryFileResponse

     * @throws \PhpOffice\PhpSpreadsheet\Exception

     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception

     */

    public function index(Request $request, VehicleCapacity $vehicleCapacity, Excel $excel)

    {



        $filters = [];

        $filters[] = ['title' => 'No'];



        $filters[] = [

            'title' => 'Name',

            'column' => 'name',

            'operator' => 'LIKE',

            'sorting' => true,

            'search' => [

                'type' => 'text',

                'placeholder' => 'Search name'

            ]

        ];



        $filters[] = [

            'title' => 'Maximum Carry Weight',

            'column' => 'max_weight',

            'operator' => 'LIKE',

            'sorting' => true,

            'search' => [

                'type' => 'text',

                'placeholder' => 'Search weight'

            ]

        ];



     /*   $filters[] = [

            'title' => 'Loadspace Volume (m3 cube)',

            'column' => 'load_space_volume',

            'operator' => 'LIKE',

            'sorting' => true,

            'search' => [

                'type' => 'text',

                'placeholder' => 'Search volume'

            ]

        ];



        $filters[] = [

            'title' => 'Loadfloor Length',

            'column' => 'load_floor_length',

            'operator' => 'LIKE',

            'sorting' => true,

            'search' => [

                'type' => 'text',

                'placeholder' => 'Search length'

            ]

        ];



        $filters[] = [

            'title' => 'Loadfloor Width',

            'column' => 'load_floor_width',

            'operator' => 'LIKE',

            'sorting' => true,

            'search' => [

                'type' => 'text',

                'placeholder' => 'Search width'

            ]

        ];*/



        $filters[] = [

            'title' => 'Action'

        ];



        $tableName = $vehicleCapacity->getTable();

        $url = route($this->route.".index");

        $this->setGridModel($vehicleCapacity);

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



        if($request->has('export_data'))

        {

            $fileName = 'VehicleCapacity';

            return $excel->download(new DataGridExport('admin.vehicleCapacity.export', $data), "$fileName.xlsx");

        }



        $route = $this->route;



        $pageTitle = "VEHICLE Body Type";

        if(Session::has('VehicleCapacityPage')){
            Session::forget('VehicleCapacityPage');
        }
        Session::put('VehicleCapacityPage', $request->input('page') ?? 1);


        if ($request->ajax()) {

            return view('admin.vehicleCapacity.grid', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'route'));

        } else {

            return view('admin.vehicleCapacity.index', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'pageTitle', 'route'));

        }

    }



    /**

     * @param Team $team

     * @return View

     */

    public function create(VehicleCapacity $vehicle_capacity) : View

    {

        $pageTitle = "CREATE VEHICLE Body Type";

        $route = $this->route;

        return view('admin.vehicleCapacity.form', compact('vehicle_capacity', 'pageTitle', 'route'));

    }



    /**

     * @param AdminTeamRequest $request

     * @param Team $team

     * @return \Illuminate\Http\RedirectResponse

     */

    public function store(AdminVehicleCapacityRequest $request, VehicleCapacity $vehicle_capacity)

    {



        

        $vehicle = $vehicle_capacity->create($request->all());

        $route = $this->route;

        $redirectRoute = route("$route.index");

        if($request->has('save_continue'))

        {

            $redirectRoute = route("$route.edit", $vehicle->id);

        }

        return redirect($redirectRoute)->with(['status' => 'success', 'message' => trans('success.admin|vehicleCapacity|created')]);

    }



    /**

     */

    public function show(Team $team)

    {

        //

    }



    /**

     * @return View

     */

    public function edit(VehicleCapacity $vehicle_capacity) : View

    {

        $copy = request()->has('copy') ? true : false;

        $pageTitle = $copy ? "COPY VEHICLE Body Type" : "EDIT VEHICLE Body Type";

        $route = $this->route;

        return view('admin.vehicleCapacity.form', compact('vehicle_capacity', 'pageTitle', 'route', 'copy'));

    }



    /**

     * @param AdminCategoryRequest $request

     * @param Category $category

     * @return \Illuminate\Http\RedirectResponse

     */

    public function update(AdminVehicleCapacityRequest $request, VehicleCapacity $vehicle_capacity)

    {

        $vehicle_capacity->update($request->all());

        $route = $this->route;

        $redirectRoute = route("$route.index");

        if($request->has('save_continue'))

        {

            $redirectRoute = route("$route.edit", $vehicle_capacity->id);

        }

        elseif(Session::has('VehicleCapacityPage')){
            // echo 11;die;
            $page = session()->get("VehicleCapacityPage");
            // $redirectRoute = ;
            return redirect()->route("$route.index", ['page'=>$page])->with(['status' => 'success', 'message' => trans('success.admin|vehicleCapacity|updated')]);
        }

        return redirect($redirectRoute)->with(['status' => 'success', 'message' => trans('success.admin|vehicleCapacity|updated')]);

    }



    /**

     * @param Category $category

     * @return \Illuminate\Http\RedirectResponse

     */

    public function destroy(VehicleCapacity $vehicle_capacity)

    {

        $route = $this->route;

        if($vehicle_capacity->canDelete())

        {

            try{

                $vehicle_capacity->delete();

                return redirect(route("$route.index"))->with(['status' => 'success', 'message' => trans('success.admin|vehicleCapacity|deleted')]);



            }catch (\Exception $exception)

            {

                return redirect(route("$route.index"))->with(['status' => 'warning', 'message' => trans('warning.admin|vehicleCapacity|deleteNotPossible')]);

            }

        }

        else

        {

            return redirect(route("$route.index"))->with(['status' => 'warning', 'message' => trans('warning.admin|vehicleCapacity|deleteNotPossible')]);

        }

    }

}

