<?php



namespace App\Http\Controllers;



use App\Exports\DataGridExport;

use App\Http\Controllers\Helpers\DataGrid;

use App\Http\Requests\AdminRoleRequest;

use App\Models\Permission;

use App\Models\Role;

use Illuminate\Http\Request;

use Illuminate\View\View;

use Maatwebsite\Excel\Excel;

use Session;

class AdminRoleController extends Controller

{

    use DataGrid;



    public $dataUrl = '/admin/roles';



    public $route = 'admin.roles';



    /**

     * @param Request $request

     * @param Role $roleModel

     * @param Excel $excel

     * @return \Illuminate\Contracts\View\Factory|View|Excel|\Symfony\Component\HttpFoundation\BinaryFileResponse

     * @throws \PhpOffice\PhpSpreadsheet\Exception

     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception

     */

    public function index(Request $request, Role $roleModel, Excel $excel)

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

            'title' => 'Permissions',

            'column' => 'permissions'

        ];



        $filters[] = [

            'title' => 'Action'

        ];



        $tableName = $roleModel->getTable();

        $url = $this->dataUrl;

        $this->setGridModel($roleModel);

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

            $fileName = 'ROLE_DATA';

            return $excel->download(new DataGridExport('admin.role.export', $data), "$fileName.xlsx");

        }





        $route = $this->route;



        $pageTitle = "MANAGE ROLES";

        if(Session::has('RolePage')){
            Session::forget('RolePage');
        }
        Session::put('RolePage', $request->input('page') ?? 1);

        if ($request->ajax()) {

            return view('admin.role.grid', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'route'));

        } else {

            return view('admin.role.index', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'pageTitle', 'route'));

        }

    }



    /**

     * @param Role $role

     * @param Permission $permissionModel

     * @return View

     */

    public function create(Role $role, Permission $permissionModel) : View

    {

        $permissions = $permissionModel->orderBy('module_group')->get();

        $pageTitle = "CREATE ROLE";

        $route = $this->route;

        return view('admin.role.form', compact('permission', 'pageTitle', 'route', 'role', 'permissions'));

    }



    /**

     * @param AdminRoleRequest $request

     * @param Role $role

     * @return \Illuminate\Http\RedirectResponse

     */

    public function store(AdminRoleRequest $request, Role $role)

    {

        $role->create($request->all());

        $route = $this->route;

        return redirect(route("$route.index"))->with(['status' => 'success', 'message' => trans('success.admin|role|created')]);

    }



    /**

     * Display the specified resource.

     *

     * @param  \App\Models\Role  $role

     * @return \Illuminate\Http\Response

     */

    public function show(Role $role)

    {

        //

    }



    /**

     * @param Role $role

     * @param Permission $permissionModel

     * @return View

     */

    public function edit(Role $role, Permission $permissionModel) : View

    {

        $permissions = $permissionModel->orderBy('module_group')->get();

        $copy = request()->has('copy') ? true : false;

        $pageTitle = $copy ? "COPY ROLE" : "EDIT ROLE";

        $route = $this->route;

        return view('admin.role.form', compact('permission', 'pageTitle', 'route', 'role', 'permissions', 'copy'));

    }



    /**

     * Update the specified resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  \App\Models\Role  $role

     * @return \Illuminate\Http\Response

     */

    public function update(AdminRoleRequest $request, Role $role)

    {

        $role->update($request->all());

        $route = $this->route;

        if(Session::has('RolePage')){
            // echo 11;die;
            $page = session()->get("RolePage");
            // $redirectRoute = ;
            return redirect()->route("$route.index", ['page'=>$page])->with(['status' => 'success', 'message' => trans('success.admin|role|updated')]);
        }

        return redirect(route("$route.index"))->with(['status' => 'success', 'message' => trans('success.admin|role|updated')]);

    }



    /**

     * Remove the specified resource from storage.

     *

     * @param  \App\Models\Role  $role

     * @return \Illuminate\Http\Response

     */

    public function destroy(Role $role)

    {

        $route = $this->route;

        if($role->canDelete())

        {

            try{

                $role->delete();

                return redirect(route("$route.index"))->with(['status' => 'success', 'message' => trans('success.admin|role|deleted')]);



            }catch (\Exception $exception)

            {

                return redirect(route("$route.index"))->with(['status' => 'warning', 'message' => trans('warning.admin|role|deleteNotPossible')]);

            }

        }

        else

        {

            return redirect(route("$route.index"))->with(['status' => 'warning', 'message' => trans('warning.admin|role|deleteNotPossible')]);

        }

    }

}

