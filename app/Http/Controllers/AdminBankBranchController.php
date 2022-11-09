<?php



namespace App\Http\Controllers;



use App\Exports\DataGridExport;

use App\Http\Controllers\Helpers\DataGrid;

use App\Http\Requests\AdminBankBranchRequest;

use App\Models\BankBranch;

use App\Models\BankMaster;

use App\Models\LocationCity;

use App\Models\LocationCountry;

use App\Models\LocationState;

use Illuminate\Http\Request;

use Illuminate\View\View;

use Maatwebsite\Excel\Excel;

use Session;

class AdminBankBranchController extends Controller

{

    use DataGrid;



    public $dataUrl = '/admin/bank-branch';



    public $route = 'admin.bank-branch';



    /**

     * @param Request $request

     * @param BankMaster $bankMasterModel

     * @param BankBranch $bankBranchModel

     * @param Excel $excel

     * @return \Illuminate\Contracts\View\Factory|View|Excel|\Symfony\Component\HttpFoundation\BinaryFileResponse

     * @throws \PhpOffice\PhpSpreadsheet\Exception

     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception

     */

    public function index(Request $request, BankMaster $bankMasterModel, BankBranch $bankBranchModel, Excel $excel)

    {

        $filters = [];

        $filters[] = ['title' => 'No'];



        $filters[] = [

            'title' => 'Bank',

            'column' => 'bank_master_id',

            'operator' => '=',

            'sorting' => true,

            'search' => [

                'type' => 'select',

                'placeholder' => 'Show all',

                'data' => $bankMasterModel->getBankDropDown()

            ]

        ];



        $filters[] = [

            'title' => 'Name',

            'column' => 'branch_name',

            'operator' => 'LIKE',

            'sorting' => true,

            'search' => [

                'type' => 'text',

                'placeholder' => 'Search name'

            ]

        ];



        $filters[] = [

            'title' => 'Branch code',

            'column' => 'branch_code',

            'operator' => 'LIKE',

            'sorting' => true,

            'search' => [

                'type' => 'text',

                'placeholder' => 'Search code'

            ]

        ];



        $filters[] = [

            'title' => 'Swift code',

            'column' => 'swift_code',

            'operator' => 'LIKE',

            'sorting' => true,

            'search' => [

                'type' => 'text',

                'placeholder' => 'Search code'

            ]

        ];



        // $filters[] = [

        //     'title' => 'Province',

        //     'column' => 'state_id',

        //     'operator' => 'SCOPE',

        //     'search' => [

        //         'type' => 'scope',

        //         'placeholder' => 'Search province',

        //         'scope' => 'ofState'

        //     ]

        // ];



        // $filters[] = [

        //     'title' => 'City',

        //     'column' => 'city_id',

        //     'operator' => 'SCOPE',

        //     'search' => [

        //         'type' => 'scope',

        //         'placeholder' => 'Search city',

        //         'scope' => 'ofCity'

        //     ]

        // ];



        // $filters[] = [

        //     'title' => 'Postal Code',

        //     'column' => 'zipcode_id',

        //     'operator' => 'SCOPE',

        //     'search' => [

        //         'type' => 'scope',

        //         'placeholder' => 'Search postal code',

        //         'scope' => 'ofZipcode'

        //     ]

        // ];



        $filters[] = [

            'title' => 'Action'

        ];



        $tableName = $bankBranchModel->getTable();

        $url = $this->dataUrl;

        $this->setGridModel($bankBranchModel);

        $this->setGridRequest($request);

        $this->setEager(['bank', 'zipcode', 'city', 'state']);

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

            $fileName = 'BANK_BRANCH_DATA';

            return $excel->download(new DataGridExport('admin.bankBranch.export', $data), "$fileName.xlsx");

        }





        $route = $this->route;



        $pageTitle = "MANAGE BRANCHES";

        if(Session::has('BankBranchPage')){
            Session::forget('BankBranchPage');
        }
        Session::put('BankBranchPage', $request->input('page') ?? 1);

        if ($request->ajax()) {

            return view('admin.bankBranch.grid', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'route'));

        } else {

            return view('admin.bankBranch.index', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'pageTitle', 'route'));

        }

    }



    /**

     * @param Request $request

     * @param BankBranch $bank_branch

     * @param BankMaster $bankMasterModel

     * @param LocationCountry $locationCountryModel

     * @param LocationState $locationStateModel

     * @param LocationCity $locationCityModel

     * @return View

     */

    public function create(Request $request, BankBranch $bank_branch, BankMaster $bankMasterModel, LocationCountry $locationCountryModel, LocationState $locationStateModel, LocationCity $locationCityModel) : View

    {

        $countries = $locationCountryModel->getDropdown();

        $states = [];

        $cities = [];

        $zipcodes = [];



        $countryInput = $request->old('country_id');

        $stateInput = $request->old('state_id');

        $cityInput = $request->old('city_id');

        if(!is_null($countryInput)) {

            $states = $locationCountryModel->where('uuid', $countryInput)->first()->getStateDropDown();

        }

        if(!is_null($stateInput)) {

            $cities = $locationStateModel->where('uuid', $stateInput)->first()->getCityDropDown();

        }

        if(!is_null($cityInput)) {

            $zipcodes = $locationCityModel->where('uuid', $cityInput)->first()->getZipcodeDropDown();

        }



        $banks = $bankMasterModel->getBankDropDown();



        $route = $this->route;

        $pageTitle = "CREATE BRANCH";



        return view('admin.bankBranch.form', compact('bank_branch', 'countries', 'states', 'cities', 'zipcodes', 'route', 'pageTitle', 'banks'));



    }



    /**

     * @param AdminBankBranchRequest $request

     * @param BankBranch $bank_branch

     * @return \Illuminate\Http\RedirectResponse

     */

    public function store(AdminBankBranchRequest $request, BankBranch $bank_branch)

    {

        $bankBranchModel = $bank_branch->create($request->all());

        $route = $this->route;

        $redirectRoute = route("$route.index");

        if($request->has('save_continue'))

        {

            $redirectRoute = route("$route.edit", $bankBranchModel->uuid);

        }



        return redirect($redirectRoute)->with(['status' => 'success', 'message' => trans('success.admin|bankBranch|created')]);

    }



    /**

     * @param BankBranch $bankBranch

     */

    public function show(BankBranch $bankBranch)

    {

        //

    }



    /**

     * @param Request $request

     * @param BankBranch $bank_branch

     * @param BankMaster $bankMasterModel

     * @param LocationCountry $locationCountryModel

     * @param LocationState $locationStateModel

     * @param LocationCity $locationCityModel

     * @return View

     */

    public function edit(Request $request, BankBranch $bank_branch, BankMaster $bankMasterModel, LocationCountry $locationCountryModel, LocationState $locationStateModel, LocationCity $locationCityModel) : View

    {

        $countries = $locationCountryModel->getDropdown();

        $states = [];

        $cities = [];

        $zipcodes = [];



        $countryInput = $request->has('country_id') ? $request->get('country_id') : $bank_branch->country_id;

        $stateInput = $request->has('state_id') ? $request->get('state_id') : $bank_branch->state_id;

        $cityInput = $request->has('city_id') ? $request->get('city_id') : $bank_branch->city_id;

        if(!is_null($countryInput)) {

            $states = $locationCountryModel->where('uuid', $countryInput)->count() > 0 ? $locationCountryModel->where('uuid', $countryInput)->first()->getStateDropDown() : [];

        }

        if(!is_null($stateInput)) {

            $cities = $locationStateModel->where('uuid', $stateInput)->count() > 0 ? $locationStateModel->where('uuid', $stateInput)->first()->getCityDropDown() : [];

        }

        if(!is_null($cityInput)) {

            $zipcodes = $locationCityModel->where('uuid', $cityInput)->count() > 0 ? $locationCityModel->where('uuid', $cityInput)->first()->getZipcodeDropDown() : [];

        }



        $banks = $bankMasterModel->getBankDropDown();



        $route = $this->route;

        $copy = request()->has('copy') ? true : false;

        $pageTitle = $copy ? "COPY BRANCH" : "EDIT BRANCH";



        return view('admin.bankBranch.form', compact('bank_branch', 'countries', 'states', 'cities', 'zipcodes', 'route', 'pageTitle', 'banks', 'copy'));

    }



    /**

     * @param AdminBankBranchRequest $request

     * @param BankBranch $bank_branch

     * @return \Illuminate\Http\RedirectResponse

     */

    public function update(AdminBankBranchRequest $request, BankBranch $bank_branch)

    {

        $bank_branch->update($request->all());

        $route = $this->route;

        $redirectRoute = route("$route.index");

        if($request->has('save_continue'))

        {

            $redirectRoute = route("$route.edit", $bank_branch->uuid);

        }

        elseif(Session::has('BankBranchPage')){
            // echo 11;die;
            $page = session()->get("BankBranchPage");
            // $redirectRoute = ;
            return redirect()->route("$route.index", ['page'=>$page])->with(['status' => 'success', 'message' => trans('success.admin|bankBranch|updated')]);
        }

        return redirect($redirectRoute)->with(['status' => 'success', 'message' => trans('success.admin|bankBranch|updated')]);

    }



    /**

     * @param BankBranch $bank_branch

     * @return \Illuminate\Http\RedirectResponse

     */

    public function destroy(BankBranch $bank_branch)

    {

        $route = $this->route;

        if($bank_branch->canDelete())

        {

            try{

                $bank_branch->delete();

                return redirect(route("$route.index"))->with(['status' => 'success', 'message' => trans('success.admin|bankBranch|deleted')]);



            }catch (\Exception $exception)

            {

                return redirect(route("$route.index"))->with(['status' => 'warning', 'message' => trans('warning.admin|bankBranch|deleteNotPossible')]);

            }

        }

        else

        {

            return redirect(route("$route.index"))->with(['status' => 'warning', 'message' => trans('warning.admin|bankBranch|deleteNotPossible')]);

        }

    }

}

