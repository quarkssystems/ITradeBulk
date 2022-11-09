<?php



namespace App\Http\Controllers;



use App\Exports\DataGridExport;

use App\Http\Controllers\Helpers\DataGrid;

use App\Http\Requests\AdminLocationCountryRequest;

use App\Models\LocationCountry;

use Illuminate\Http\Request;

use Illuminate\View\View;

use Maatwebsite\Excel\Excel;

use Session;

class AdminLocationCountryController extends Controller

{

    use DataGrid;



    public $dataUrl = '/admin/location-master/country';



    public $route = 'admin.country';



    /**

     * @param Request $request

     * @param LocationCountry $locationCountryModel

     * @param Excel $excel

     * @return \Illuminate\Contracts\View\Factory|View|Excel|\Symfony\Component\HttpFoundation\BinaryFileResponse

     * @throws \PhpOffice\PhpSpreadsheet\Exception

     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception

     */

    public function index(Request $request, LocationCountry $locationCountryModel, Excel $excel)

    {

        $filters = [];

        $filters[] = ['title' => 'No'];



        $filters[] = [

            'title' => 'Name',

            'column' => 'country_name',

            'operator' => 'LIKE',

            'sorting' => true,

            'search' => [

                'type' => 'text',

                'placeholder' => 'Search name'

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

                'data' => $locationCountryModel->getStatusesDropDown()

            ]

        ];



        $filters[] = [

            'title' => 'Action'

        ];



        $tableName = $locationCountryModel->getTable();

        $url = $this->dataUrl;

        $this->setGridModel($locationCountryModel);

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

            $fileName = 'COUNTRY_DATA';

            return $excel->download(new DataGridExport('admin.locationCountry.export', $data), "$fileName.xlsx");

        }





        $route = $this->route;



        $pageTitle = "MANAGE COUNTRIES";

        if(Session::has('LocationCountryPage')){
            Session::forget('LocationCountryPage');
        }
        Session::put('LocationCountryPage', $request->input('page') ?? 1);

        if ($request->ajax()) {

            return view('admin.locationCountry.grid', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'route'));

        } else {

            return view('admin.locationCountry.index', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'pageTitle', 'route'));

        }

    }



    /**

     * @param LocationCountry $country

     * @return \Illuminate\Contracts\View\Factory|View

     */

    public function create(LocationCountry $country) : View

    {

        $pageTitle = "CREATE COUNTRY";

        $status = $country->getStatusesDropDown();

        $route = $this->route;

        return view('admin.locationCountry.form', compact('country', 'status', 'pageTitle', 'route'));

    }



    /**

     * @param AdminLocationCountryRequest $request

     * @param LocationCountry $country

     * @return \Illuminate\Http\RedirectResponse

     */

    public function store(AdminLocationCountryRequest $request, LocationCountry $country)

    {

        $countryModel = $country->create($request->all());

        $route = $this->route;



        $redirectRoute = route("$route.index");

        if($request->has('save_continue'))

        {

            $redirectRoute = route("$route.edit", $countryModel->uuid);

        }

        return redirect($redirectRoute)->with(['status' => 'success', 'message' => trans('success.admin|locationCountry|created')]);

    }



    /**

     * Display the specified resource.

     *

     * @param  \App\Models\LocationCountry  $locationCountry

     * @return \Illuminate\Http\Response

     */

    public function show(LocationCountry $locationCountry)

    {

        //

    }



    /**

     * @param LocationCountry $country

     * @return View

     */

    public function edit(LocationCountry $country) : View

    {

        $status = $country->getStatusesDropDown();

        $route = $this->route;

        $copy = request()->has('copy') ? true : false;

        $pageTitle = $copy ? "COPY COUNTRY" : "EDIT COUNTRY";

        return view('admin.locationCountry.form', compact('country', 'status', 'pageTitle', 'route', 'copy'));

    }



    /**

     * @param AdminLocationCountryRequest $request

     * @param LocationCountry $country

     * @return \Illuminate\Http\RedirectResponse

     */

    public function update(AdminLocationCountryRequest $request, LocationCountry $country)

    {

        $country->update($request->all());

        $route = $this->route;



        $redirectRoute = route("$route.index");

        if($request->has('save_continue'))

        {

            $redirectRoute = route("$route.edit", $country->uuid);

        }

        elseif(Session::has('LocationCountryPage')){
            // echo 11;die;
            $page = session()->get("LocationCountryPage");
            // $redirectRoute = ;
            return redirect()->route("$route.index", ['page'=>$page])->with(['status' => 'success', 'message' => trans('success.admin|locationCountry|updated')]);
        }

        return redirect($redirectRoute)->with(['status' => 'success', 'message' => trans('success.admin|locationCountry|updated')]);

    }



    /**

     * @param LocationCountry $country

     * @return \Illuminate\Http\RedirectResponse

     */

    public function destroy(LocationCountry $country)

    {

        $route = $this->route;

        if($country->canDelete())

        {

            try{

                $country->delete();

                return redirect(route("$route.index"))->with(['status' => 'success', 'message' => trans('success.admin|locationCountry|deleted')]);



            }catch (\Exception $exception)

            {

                return redirect(route("$route.index"))->with(['status' => 'warning', 'message' => trans('warning.admin|locationCountry|deleteNotPossible')]);

            }

        }

        else

        {

            return redirect(route("$route.index"))->with(['status' => 'warning', 'message' => trans('warning.admin|locationCountry|deleteNotPossible')]);

        }

    }

}

