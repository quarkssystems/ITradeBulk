<?php



namespace App\Http\Controllers;



use App\Exports\DataGridExport;

use App\Http\Controllers\Helpers\DataGrid;

use App\Http\Requests\AdminShortcodeRequest;

// use App\Models\Brand;

use App\Models\Shortcode;

use Illuminate\Http\Request;

use Illuminate\View\View;

use Maatwebsite\Excel\Excel;

use Session;

class AdminShortcodeController extends Controller

{

    use DataGrid;



    public $dataUrl = '/admin/shortcode';



    public $route = 'admin.shortcode';



    /**

     * @param Request $request

     * @param Shortcode $shortcode

     * @param Excel $excel

     * @return \Illuminate\Contracts\View\Factory|View|Excel|\Symfony\Component\HttpFoundation\BinaryFileResponse

     * @throws \PhpOffice\PhpSpreadsheet\Exception

     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception

     */

    public function index(Request $request, Shortcode $shortcode, Excel $excel)

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



        // $filters[] = [

        //     'title' => 'Status',

        //     'column' => 'status',

        //     'operator' => '=',

        //     'sorting' => true,

        //     'search' => [

        //         'type' => 'select',

        //         'placeholder' => 'Show all',

        //         'data' => $shortcode->getStatusesDropDown()

        //     ]

        // ];



        $filters[] = [

            'title' => 'Action'

        ];



        $tableName = $shortcode->getTable();

        // echo $tableName;die;

        $url = $this->dataUrl;

        $this->setGridModel($shortcode);

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

            $fileName = 'Shortcode';

            return $excel->download(new DataGridExport('admin.shortcode.export', $data), "$fileName.xlsx");

        }





        $route = $this->route;



        $pageTitle = "Shortcode";

        if(Session::has('ShortcodePage')){
            Session::forget('ShortcodePage');
        }
        Session::put('ShortcodePage', $request->input('page') ?? 1);

        if ($request->ajax()) {

            return view('admin.shortcode.grid', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'route'));

        } else {

            return view('admin.shortcode.index', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'pageTitle', 'route'));

        }

    }



    /**

     * @param Shortcode $shortcode

     * @return View

     */

    public function create(Shortcode $shortcode) : View

    {

        $statuses = $shortcode->getStatusesDropDown();

        $pageTitle = "CREATE Shortcode";

        $route = $this->route;

        return view('admin.shortcode.form', compact('shortcode', 'pageTitle', 'route', 'statuses'));

    }



    /**

     * @param AdminShortcodeRequest $request

     * @param Shortcode $shortcode

     * @return \Illuminate\Http\RedirectResponse

     */

    public function store(AdminShortcodeRequest $request, Shortcode $shortcode)

    {



        $shortcode = $shortcode->create($request->all());

        // echo '<pre>';

        // print_r($shortcode);

        $route = $this->route;

        // echo "ID : ".$shortcode->id;

        // echo "Route : ".$route;

        // die;

        $redirectRoute = route("$route.index");

        if($request->has('save_continue'))

        {

            $redirectRoute = route("$route.edit", $shortcode->id);

        }

        return redirect($redirectRoute)->with(['status' => 'success', 'message' => trans('success.admin|shortcode|created')]);

    }



    /**

     */

    public function show(Shortcode $shortcode)

    {

        //

    }



    /**

     * @return View

     */

    public function edit(Shortcode $shortcode) : View

    {

        $statuses = $shortcode->getStatusesDropDown();

        $copy = request()->has('copy') ? true : false;

        $pageTitle = $copy ? "COPY Shortcode" : "EDIT Shortcode";

        $route = $this->route;

        return view('admin.shortcode.form', compact('shortcode', 'pageTitle', 'route', 'statuses', 'copy'));

    }



    /**

     * @param AdminCategoryRequest $request

     * @param Category $category

     * @return \Illuminate\Http\RedirectResponse

     */

    public function update(AdminShortcodeRequest $request, Shortcode $shortcode)

    {

        // if($request->hasFile('icon') && $request->file('icon')->isValid())

        // {

        //     $documentFile = $brand->uploadMedia($request->file('icon'));

        //     $document = $documentFile['path'].$documentFile['name'];

        //     $request->merge(['icon_file' => $document]);

        // }

        $shortcode->update($request->all());

        $route = $this->route;

        $redirectRoute = route("$route.index");

        if($request->has('save_continue'))

        {

            $redirectRoute = route("$route.edit", $shortcode->id);

        }

        elseif(Session::has('ShortcodePage')){
            // echo 11;die;
            $page = session()->get("ShortcodePage");
            // $redirectRoute = ;
            return redirect()->route("$route.index", ['page'=>$page])->with(['status' => 'success', 'message' => trans('success.admin|shortcode|updated')]);
        }

        return redirect($redirectRoute)->with(['status' => 'success', 'message' => trans('success.admin|shortcode|updated')]);

    }



    /**

     * @param Category $category

     * @return \Illuminate\Http\RedirectResponse

     */

    public function destroy(Shortcode $shortcode)

    {

        $route = $this->route;

        if($shortcode->canDelete())

        {

            try{

                $shortcode->delete();

                return redirect(route("$route.index"))->with(['status' => 'success', 'message' => trans('success.admin|shortcode|deleted')]);



            }catch (\Exception $exception)

            {

                return redirect(route("$route.index"))->with(['status' => 'warning', 'message' => trans('warning.admin|shortcode|deleteNotPossible')]);

            }

        }

        else

        {

            return redirect(route("$route.index"))->with(['status' => 'warning', 'message' => trans('warning.admin|shortcode|deleteNotPossible')]);

        }

    }

}

