<?php



namespace App\Http\Controllers;



use App\Exports\DataGridExport;

use App\Http\Controllers\Helpers\DataGrid;

use App\Http\Requests\AdminCMSModuleRequest;

// use App\Models\Brand;

use App\Models\Category;

use App\Models\CMSModule;

use Illuminate\Http\Request;

use Illuminate\View\View;

use Maatwebsite\Excel\Excel;

use Session;

class AdminCMSModuleController extends Controller

{

    use DataGrid;



    public $dataUrl = '/admin/cmsmodule';



    public $route = 'admin.cmsmodule';



    /**

     * @param Request $request

     * @param CMSModule $cmsmodule

     * @param Excel $excel

     * @return \Illuminate\Contracts\View\Factory|View|Excel|\Symfony\Component\HttpFoundation\BinaryFileResponse

     * @throws \PhpOffice\PhpSpreadsheet\Exception

     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception

     */

    public function index(Request $request, CMSModule $cmsmodule, Excel $excel)

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

            'title' => 'Slug',

            'column' => 'slug',

            'operator' => 'LIKE',

            'sorting' => true,

            'search' => [

                'type' => 'text',

                'placeholder' => 'Search slug'

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

                'data' => $cmsmodule->getStatusesDropDown()

            ]

        ];



        $filters[] = [

            'title' => 'Action'

        ];



        $tableName = $cmsmodule->getTable();

        // echo $tableName;die;

        $url = $this->dataUrl;

        $this->setGridModel($cmsmodule);

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

            $fileName = 'CMS_Module';

            return $excel->download(new DataGridExport('admin.cmsModule.export', $data), "$fileName.xlsx");

        }





        $route = $this->route;



        $pageTitle = "Cms Module";

        if(Session::has('CMSModulePage')){
            Session::forget('CMSModulePage');
        }
        Session::put('CMSModulePage', $request->input('page') ?? 1);

        if ($request->ajax()) {

            return view('admin.cmsModule.grid', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'route'));

        } else {

            return view('admin.cmsModule.index', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'pageTitle', 'route'));

        }

    }



    /**

     * @param CMSModule $cmsmodule

     * @return View

     */

    public function create(CMSModule $cmsmodule) : View

    {

        $statuses = $cmsmodule->getStatusesDropDown();

        $pageTitle = "CREATE Content";

        $route = $this->route;

        return view('admin.cmsModule.form', compact('cmsmodule', 'pageTitle', 'route', 'statuses'));

    }



    /**

     * @param AdminCMSModuleRequest $request

     * @param CMSModule $cmsmodule

     * @return \Illuminate\Http\RedirectResponse

     */

    public function store(AdminCMSModuleRequest $request, CMSModule $cmsmodule)

    {



        $cmsmodule = $cmsmodule->create($request->all());

        // echo '<pre>';

        // print_r($cmsmodule);

        $route = $this->route;

        // echo "ID : ".$cmsmodule->id;

        // echo "Route : ".$route;

        // die;

        $redirectRoute = route("$route.index");

        if($request->has('save_continue'))

        {

            $redirectRoute = route("$route.edit", $cmsmodule->id);

        }

        return redirect($redirectRoute)->with(['status' => 'success', 'message' => trans('success.admin|cmsModule|created')]);

    }



    /**

     */

    public function show(CMSModule $cmsmodule)

    {

        //

    }



    /**

     * @return View

     */

    public function edit(CMSModule $cmsmodule) : View

    {

        $statuses = $cmsmodule->getStatusesDropDown();

        $copy = request()->has('copy') ? true : false;

        $pageTitle = $copy ? "COPY Cms Module" : "EDIT Cms Module";

        $route = $this->route;

        return view('admin.cmsModule.form', compact('cmsmodule', 'pageTitle', 'route', 'statuses', 'copy'));

    }



    /**

     * @param AdminCategoryRequest $request

     * @param Category $category

     * @return \Illuminate\Http\RedirectResponse

     */

    public function update(AdminCMSModuleRequest $request, CMSModule $cmsmodule)

    {

        // if($request->hasFile('icon') && $request->file('icon')->isValid())

        // {

        //     $documentFile = $brand->uploadMedia($request->file('icon'));

        //     $document = $documentFile['path'].$documentFile['name'];

        //     $request->merge(['icon_file' => $document]);

        // }

        $cmsmodule->update($request->all());

        $route = $this->route;

        $redirectRoute = route("$route.index");

        if($request->has('save_continue'))

        {

            $redirectRoute = route("$route.edit", $cmsmodule->id);

        }

        elseif(Session::has('CMSModulePage')){
            // echo 11;die;
            $page = session()->get("CMSModulePage");
            // $redirectRoute = ;
            return redirect()->route("$route.index", ['page'=>$page])->with(['status' => 'success', 'message' => trans('success.admin|cmsModule|updated')]);
        }

        return redirect($redirectRoute)->with(['status' => 'success', 'message' => trans('success.admin|cmsModule|updated')]);

    }



    /**

     * @param Category $category

     * @return \Illuminate\Http\RedirectResponse

     */

    public function destroy(CMSModule $cmsmodule)

    {

        $route = $this->route;

        if($cmsmodule->canDelete())

        {

            try{

                $cmsmodule->delete();

                return redirect(route("$route.index"))->with(['status' => 'success', 'message' => trans('success.admin|cmsModule|deleted')]);



            }catch (\Exception $exception)

            {

                return redirect(route("$route.index"))->with(['status' => 'warning', 'message' => trans('warning.admin|cmsModule|deleteNotPossible')]);

            }

        }

        else

        {

            return redirect(route("$route.index"))->with(['status' => 'warning', 'message' => trans('warning.admin|cmsModule|deleteNotPossible')]);

        }

    }

}

