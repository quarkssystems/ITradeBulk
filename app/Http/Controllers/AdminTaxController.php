<?php



namespace App\Http\Controllers;



use App\Exports\DataGridExport;

use App\Http\Controllers\Helpers\DataGrid;

use App\Http\Requests\AdminTaxRequest;

use App\Models\Tax;

use Illuminate\Http\Request;

use Illuminate\View\View;

use Maatwebsite\Excel\Excel;

use Session;


class AdminTaxController extends Controller

{

    use DataGrid;



    public $dataUrl = '/admin/tax';



    public $route = 'admin.tax';



    /**

     * @param Request $request

     * @param Tax $taxModel

     * @param Excel $excel

     * @return \Illuminate\Contracts\View\Factory|View|Excel|\Symfony\Component\HttpFoundation\BinaryFileResponse

     * @throws \PhpOffice\PhpSpreadsheet\Exception

     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception

     */

    public function index(Request $request, Tax $taxModel, Excel $excel)

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

            'title' => 'Tax',

            'column' => 'value',

            'operator' => '=',

            'sorting' => true,

            'search' => [

                'type' => 'text',

                'placeholder' => 'Search tax'

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

                'data' => $taxModel->getStatusesDropDown()

            ]

        ];



        $filters[] = [

            'title' => 'Action'

        ];



        $tableName = $taxModel->getTable();

        $url = $this->dataUrl;

        $this->setGridModel($taxModel);

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

            $fileName = 'TAX_DATA';

            return $excel->download(new DataGridExport('admin.tax.export', $data), "$fileName.xlsx");

        }





        $route = $this->route;



        $pageTitle = "MANAGE TAX";

        if(Session::has('TaxPage')){
            Session::forget('TaxPage');
        }
        Session::put('TaxPage', $request->input('page') ?? 1);


        if ($request->ajax()) {

            return view('admin.tax.grid', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'route'));

        } else {

            return view('admin.tax.index', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'pageTitle', 'route'));

        }

    }



    /**

     * @param Tax $tax

     * @return View

     */

    public function create(Tax $tax) : View

    {

        $statuses = $tax->getStatusesDropDown();

        $pageTitle = "CREATE TAX";

        $route = $this->route;

        return view('admin.tax.form', compact('tax', 'pageTitle', 'route', 'statuses'));

    }



    /**

     * @param AdminTaxRequest $request

     * @param Tax $tax

     * @return \Illuminate\Http\RedirectResponse

     */

    public function store(AdminTaxRequest $request, Tax $tax)

    {

        $taxModel = $tax->create($request->all());

        $route = $this->route;

        $redirectRoute = route("$route.index");

        if($request->has('save_continue'))

        {

            $redirectRoute = route("$route.edit", $taxModel->uuid);

        }

        return redirect($redirectRoute)->with(['status' => 'success', 'message' => trans('success.admin|tax|created')]);

    }



    /**

     * @param Tax $tax

     */

    public function show(Tax $tax)

    {

        //

    }



    /**

     * @param Tax $tax

     * @return View

     */

    public function edit(Tax $tax) : View

    {

        $statuses = $tax->getStatusesDropDown();

        $copy = request()->has('copy') ? true : false;

        $pageTitle = $copy ? "COPY TAX" : "EDIT TAX";

        $role = \Auth::user()->role; //added

        $route = $this->route;

        return view('admin.tax.form', compact('tax', 'pageTitle', 'route', 'role', 'statuses', 'copy'));

    }



    /**

     * @param AdminTaxRequest $request

     * @param Tax $tax

     * @return \Illuminate\Http\RedirectResponse

     */

    public function update(AdminTaxRequest $request, Tax $tax)

    {

        $tax->update($request->all());

        $route = $this->route;

        $redirectRoute = route("$route.index");

        if($request->has('save_continue'))

        {

            $redirectRoute = route("$route.edit", $tax->uuid);

        }

        elseif(Session::has('TaxPage')){
            // echo 11;die;
            $page = session()->get("TaxPage");
            // $redirectRoute = ;
            return redirect()->route("$route.index", ['page'=>$page])->with(['status' => 'success', 'message' => trans('success.admin|tax|updated')]);
        }

        return redirect($redirectRoute)->with(['status' => 'success', 'message' => trans('success.admin|tax|updated')]);

    }



    /**

     * @param Tax $tax

     * @return \Illuminate\Http\RedirectResponse

     */

    public function destroy(Tax $tax)

    {

        $route = $this->route;

        if($tax->canDelete())

        {

            try{

                $tax->delete();

                return redirect(route("$route.index"))->with(['status' => 'success', 'message' => trans('success.admin|tax|deleted')]);



            }catch (\Exception $exception)

            {

                return redirect(route("$route.index"))->with(['status' => 'warning', 'message' => trans('warning.admin|tax|deleteNotPossible')]);

            }

        }

        else

        {

            return redirect(route("$route.index"))->with(['status' => 'warning', 'message' => trans('warning.admin|tax|deleteNotPossible')]);

        }

    }

}

