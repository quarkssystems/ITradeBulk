<?php



namespace App\Http\Controllers;



use App\Exports\DataGridExport;

use App\Http\Controllers\Helpers\DataGrid;

use App\Http\Requests\AdminCMSBlockRequest;

// use App\Models\Brand;

use App\Models\Category;

use App\Models\CMSBlock;

use Illuminate\Http\Request;

use Illuminate\View\View;

use Maatwebsite\Excel\Excel;

use Session;

class AdminCMSBlockController extends Controller

{

    use DataGrid;



    public $dataUrl = '/admin/cmsblock';



    public $route = 'admin.cmsblock';



    /**

     * @param Request $request

     * @param cmsblock $cmsblock

     * @param Excel $excel

     * @return \Illuminate\Contracts\View\Factory|View|Excel|\Symfony\Component\HttpFoundation\BinaryFileResponse

     * @throws \PhpOffice\PhpSpreadsheet\Exception

     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception

     */

    public function index(Request $request, CMSBlock $cmsblock, Excel $excel)

    {

        

        $filters = [];

        $filters[] = ['title' => 'No'];



       /* $filters[] = [

            'title' => 'Thumbnail',

        ];



        */



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

                'data' => $cmsblock->getStatusesDropDown()

            ]

        ];



        $filters[] = [

            'title' => 'Action'

        ];



        $tableName = $cmsblock->getTable();

       

        $url = $this->dataUrl;

        $this->setGridModel($cmsblock);

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

            $fileName = 'CMS_Block';

            return $excel->download(new DataGridExport('admin.cmsblock.export', $data), "$fileName.xlsx");

        }





        $route = $this->route;



        $pageTitle = "Cms Block";

        if(Session::has('CMSBlockPage')){
            Session::forget('CMSBlockPage');
        }
        Session::put('CMSBlockPage', $request->input('page') ?? 1);

        if ($request->ajax()) {



            return view('admin.cmsBlock.grid', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'route'));

        } else {



            return view('admin.cmsBlock.index', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'pageTitle', 'route'));

        }

    }



    /**

     * @param cmsblock $cmsblock

     * @return View

     */

    public function create(CMSBlock $cmsblock) : View

    {

        $statuses = $cmsblock->getStatusesDropDown();

        $pageTitle = "CREATE Content";

        $route = $this->route;

        return view('admin.cmsBlock.form', compact('cmsblock', 'pageTitle', 'route', 'statuses'));

    }



    /**

     * @param AdminCMSBlockRequest $request

     * @param CMSBlock $cmsblock

     * @return \Illuminate\Http\RedirectResponse

     */

    public function store(AdminCMSBlockRequest $request, CMSBlock $cmsblock)

    {



        $cmsblock = $cmsblock->create($request->all());

        $route = $this->route;

        $redirectRoute = route("$route.index");

        if($request->has('save_continue'))

        {

            $redirectRoute = route("$route.edit", $cmsblock->id);

        }

        return redirect($redirectRoute)->with(['status' => 'success', 'message' => trans('success.admin|cmsBlock|created')]);

    }



    /**

     */

    public function show(CMSBlock $cmsblock)

    {

        //

    }



    /**

     * @return View

     */

    public function edit(CMSBlock $cmsblock) : View

    {

        $statuses = $cmsblock->getStatusesDropDown();

        $copy = request()->has('copy') ? true : false;

        $pageTitle = $copy ? "COPY Cms Block" : "EDIT Cms Block";

        $route = $this->route;

        return view('admin.cmsBlock.form', compact('cmsblock', 'pageTitle', 'route', 'statuses', 'copy'));

    }



    /**

     * @param AdminCategoryRequest $request

     * @param Category $category

     * @return \Illuminate\Http\RedirectResponse

     */

    public function update(AdminCMSBlockRequest $request, CMSBlock $cmsblock)

    {

        // if($request->hasFile('icon') && $request->file('icon')->isValid())

        // {

        //     $documentFile = $brand->uploadMedia($request->file('icon'));

        //     $document = $documentFile['path'].$documentFile['name'];

        //     $request->merge(['icon_file' => $document]);

        // }

        $cmsblock->update($request->all());

        $route = $this->route;

        $redirectRoute = route("$route.index");

        if($request->has('save_continue'))

        {

            $redirectRoute = route("$route.edit", $cmsblock->id);

        }

        elseif(Session::has('CMSBlockPage')){
            // echo 11;die;
            $page = session()->get("CMSBlockPage");
            // $redirectRoute = ;
            return redirect()->route("$route.index", ['page'=>$page])->with(['status' => 'success', 'message' => trans('success.admin|cmsBlock|updated')]);
        }

        return redirect($redirectRoute)->with(['status' => 'success', 'message' => trans('success.admin|cmsBlock|updated')]);

    }



    /**

     * @param Category $category

     * @return \Illuminate\Http\RedirectResponse

     */

    public function destroy(CMSBlock $cmsblock)

    {

        $route = $this->route;

        if($cmsblock->canDelete())

        {

            try{

                $cmsblock->delete();

                return redirect(route("$route.index"))->with(['status' => 'success', 'message' => trans('success.admin|cmsBlock|deleted')]);



            }catch (\Exception $exception)

            {

                return redirect(route("$route.index"))->with(['status' => 'warning', 'message' => trans('warning.admin|cmsBlock|deleteNotPossible')]);

            }

        }

        else

        {

            return redirect(route("$route.index"))->with(['status' => 'warning', 'message' => trans('warning.admin|cmsBlock|deleteNotPossible')]);

        }

    }

}

