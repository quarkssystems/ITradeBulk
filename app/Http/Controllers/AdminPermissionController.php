<?php



namespace App\Http\Controllers;



use App\Exports\DataGridExport;

use App\Http\Controllers\Helpers\DataGrid;

use App\Http\Requests\AdminPermissionRequest;

use App\Models\Permission;

use Illuminate\Http\Request;

use Illuminate\View\View;

use Maatwebsite\Excel\Excel;

use Session;

class AdminPermissionController extends Controller

{

    use DataGrid;



    public $dataUrl = '/admin/permissions';



    public $route = 'admin.permissions';



    /**

     * @param Request $request

     * @param Permission $permissionModel

     * @param Excel $excel

     * @return \Illuminate\Contracts\View\Factory|View|Excel|\Symfony\Component\HttpFoundation\BinaryFileResponse

     * @throws \PhpOffice\PhpSpreadsheet\Exception

     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception

     */

    public function index(Request $request, Permission $permissionModel, Excel $excel)

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

            'title' => 'Routes',

            'column' => 'routes'

        ];



        $filters[] = [

            'title' => 'Module group',

            'column' => 'module_group',

            'operator' => 'LIKE',

            'sorting' => true,

            'search' => [

                'type' => 'text',

                'placeholder' => 'Search group'

            ]

        ];



        $filters[] = [

            'title' => 'Module',

            'column' => 'module',

            'operator' => 'LIKE',

            'sorting' => true,

            'search' => [

                'type' => 'text',

                'placeholder' => 'Search module'

            ]

        ];



        $filters[] = [

            'title' => 'Status',

            'column' => 'status',

            'operator' => '=',

            'sorting' => true,

            'search' => [

                'type' => 'select',

                'placeholder' => 'Show all',

                'data' => $permissionModel->getStatusesDropDown()

            ]

        ];



        $filters[] = [

            'title' => 'Action'

        ];



        $tableName = $permissionModel->getTable();

        $url = $this->dataUrl;

        $this->setGridModel($permissionModel);

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

            $fileName = 'PERMISSION_DATA';

            return $excel->download(new DataGridExport('admin.permission.export', $data), "$fileName.xlsx");

        }

        $route = $this->route;

        $pageTitle = "MANAGE PERMISSIONS";

        if(Session::has('PermissionPage')){
            Session::forget('PermissionPage');
        }
        Session::put('PermissionPage', $request->input('page') ?? 1);

        if ($request->ajax()) {

            return view('admin.permission.grid', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'route'));

        } else {

            return view('admin.permission.index', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'pageTitle', 'route'));

        }

    }



    /**

     * @param Permission $permission

     * @return View

     */

    public function create(Permission $permission) : View

    {

        $pageTitle = "CREATE PERMISSION";

        $status = $permission->getStatusesDropDown();

        $route = $this->route;

        return view('admin.permission.form', compact('permission', 'status', 'pageTitle', 'route', 'role'));

    }



    /**

     * Store a newly created resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\Response

     */

    public function store(AdminPermissionRequest $request, Permission $permission)

    {

        $routes = $request->get('routes');

        $routesArray = explode(PHP_EOL,$routes);

        $routesArray = array_map('ltrim', $routesArray);

        $routesArray = array_map('rtrim', $routesArray);

        $request->merge(['routes' => $routesArray]);

        $permissionModel = $permission->create($request->all());

        $route = $this->route;

        $redirectRoute = route("$route.index");

        if($request->has('save_continue'))

        {

            $redirectRoute = route("$route.edit", $permissionModel->uuid);

        }

        return redirect($redirectRoute)->with(['status' => 'success', 'message' => trans('success.admin|permission|created')]);

    }



    /**

     * Display the specified resource.

     *

     * @param  \App\Models\Permission  $permission

     * @return \Illuminate\Http\Response

     */

    public function show(Permission $permission)

    {

        //

    }



    /**

     * Show the form for editing the specified resource.

     *

     * @param  \App\Models\Permission  $permission

     * @return \Illuminate\Http\Response

     */

    public function edit(Permission $permission) : View

    {

        $status = $permission->getStatusesDropDown();

        $route = $this->route;

        $copy = request()->has('copy') ? true : false;

        $pageTitle = $copy ? "COPY PERMISSION" : "EDIT PERMISSION";

        return view('admin.permission.form', compact('permission', 'status', 'pageTitle', 'route', 'role', 'copy'));

    }



    /**

     * Update the specified resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  \App\Models\Permission  $permission

     * @return \Illuminate\Http\Response

     */

    public function update(AdminPermissionRequest $request, Permission $permission)

    {

        $routes = $request->get('routes');

        $routesArray = explode(PHP_EOL,$routes);

        $routesArray = array_map('ltrim', $routesArray);

        $routesArray = array_map('rtrim', $routesArray);

        $request->merge(['routes' => $routesArray]);

        $permission->update($request->all());

        $route = $this->route;

        if(Session::has('PermissionPage')){
            // echo 11;die;
            $page = session()->get("PermissionPage");
            // $redirectRoute = ;
            return redirect()->route("$route.index", ['page'=>$page])->with(['status' => 'success', 'message' => trans('success.admin|permission|updated')]);
        }

        return redirect(route("$route.index"))->with(['status' => 'success', 'message' => trans('success.admin|permission|updated')]);

    }



    /**

     * Remove the specified resource from storage.

     *

     * @param  \App\Models\Permission  $permission

     * @return \Illuminate\Http\Response

     */

    public function destroy(Permission $permission)

    {

        $route = $this->route;

        if($permission->canDelete())

        {

            try{

                $permission->delete();

                return redirect(route("$route.index"))->with(['status' => 'success', 'message' => trans('success.admin|permission|deleted')]);



            }catch (\Exception $exception)

            {

                return redirect(route("$route.index"))->with(['status' => 'warning', 'message' => trans('warning.admin|permission|deleteNotPossible')]);

            }

        }

        else

        {

            return redirect(route("$route.index"))->with(['status' => 'warning', 'message' => trans('warning.admin|permission|deleteNotPossible')]);

        }

    }

}

