<?php



namespace App\Http\Controllers;



use App\Exports\DataGridExport;

use App\Http\Controllers\Helpers\DataGrid;

use App\Http\Requests\AdminBankMasterRequest;

use App\Models\BankMaster;

use App\Models\BankBranch;

use App\Models\UserBankDetails;

use Illuminate\Http\Request;

use Illuminate\View\View;

use Maatwebsite\Excel\Excel;

use Session;


class AdminBankMasterController extends Controller

{

    use DataGrid;



    public $dataUrl = '/admin/bank-master';



    public $route = 'admin.bank-master';



    /**

     * @param Request $request

     * @param BankMaster $bankMasterModel

     * @param Excel $excel

     * @return \Illuminate\Contracts\View\Factory|View|Excel|\Symfony\Component\HttpFoundation\BinaryFileResponse

     * @throws \PhpOffice\PhpSpreadsheet\Exception

     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception

     */

    public function index(Request $request, BankMaster $bankMasterModel, Excel $excel)

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

            'title' => 'Short name',

            'column' => 'short_name',

            'operator' => 'LIKE',

            'sorting' => true,

            'search' => [

                'type' => 'text',

                'placeholder' => 'Search short name'

            ]

        ];



        $filters[] = [

            'title' => 'Action'

        ];



        $tableName = $bankMasterModel->getTable();

        $url = $this->dataUrl;

        $this->setGridModel($bankMasterModel);

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

            $fileName = 'BANK_DATA';

            return $excel->download(new DataGridExport('admin.bankMaster.export', $data), "$fileName.xlsx");

        }



        $route = $this->route;



        $pageTitle = "MANAGE BANKS";

        if(Session::has('BankMasterPage')){
            Session::forget('BankMasterPage');
        }
        Session::put('BankMasterPage', $request->input('page') ?? 1);

        if ($request->ajax()) {

            return view('admin.bankMaster.grid', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'route'));

        } else {

            return view('admin.bankMaster.index', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'pageTitle', 'route'));

        }

    }



    /**

     * @param BankMaster $bank_master

     * @return View

     */

    public function create(BankMaster $bank_master) : view

    {

        $pageTitle = "CREATE BANK";

        $route = $this->route;

        return view('admin.bankMaster.form', compact('bank_master', 'pageTitle', 'route'));

    }



    /**

     * @param AdminBankMasterRequest $request

     * @param BankMaster $bank_master

     * @return \Illuminate\Http\RedirectResponse

     */

    public function store(AdminBankMasterRequest $request, BankMaster $bank_master)

    {

        $bankMasterModel = $bank_master->create($request->all());

        $route = $this->route;

        $redirectRoute = route("$route.index");

        if($request->has('save_continue'))

        {

            $redirectRoute = route("$route.edit", $bankMasterModel->uuid);

        }

        return redirect($redirectRoute)->with(['status' => 'success', 'message' => trans('success.admin|bankMaster|created')]);

    }



    /**

     * @param BankMaster $bankMaster

     */

    public function show(BankMaster $bankMaster)

    {

        //

    }



    /**

     * @param BankMaster $bank_master

     * @return View

     */

    public function edit(BankMaster $bank_master) : view

    {

        $copy = request()->has('copy') ? true : false;

        $pageTitle = $copy ? "COPY BANK" : "EDIT BANK";

        $route = $this->route;

        return view('admin.bankMaster.form', compact('bank_master', 'pageTitle', 'route', 'copy'));

    }



    /**

     * @param AdminBankMasterRequest $request

     * @param BankMaster $bank_master

     * @return \Illuminate\Http\RedirectResponse

     */

    public function update(AdminBankMasterRequest $request, BankMaster $bank_master)

    {

        $bank_master->update($request->all());

        $route = $this->route;

        $redirectRoute = route("$route.index");

        if($request->has('save_continue'))

        {

            $redirectRoute = route("$route.edit", $bank_master->uuid);

        }

        elseif(Session::has('BankMasterPage')){
            // echo 11;die;
            $page = session()->get("BankMasterPage");
            // $redirectRoute = ;
            return redirect()->route("$route.index", ['page'=>$page])->with(['status' => 'success', 'message' => trans('success.admin|bankMaster|updated')]);
        }

        return redirect($redirectRoute)->with(['status' => 'success', 'message' => trans('success.admin|bankMaster|updated')]);

    }



    /**

     * @param BankMaster $bank_master

     * @return \Illuminate\Http\RedirectResponse

     */

    public function destroy(BankMaster $bank_master)

    {

        $route = $this->route;

        if($bank_master->canDelete())

        {

            try{
                

               $bankbrnach = new BankBranch; 
                //$userbank = new UserBankDetails;
                $bankbrnach->where('bank_master_id',$bank_master->uuid)->delete();

                $bank_master->delete();

                return redirect(route("$route.index"))->with(['status' => 'success', 'message' => trans('success.admin|bankMaster|deleted')]);



            }catch (\Exception $exception)

            {

                return redirect(route("$route.index"))->with(['status' => 'warning', 'message' => trans('warning.admin|bankMaster|deleteNotPossible')]);

            }

        }

        else

        {

            return redirect(route("$route.index"))->with(['status' => 'warning', 'message' => trans('warning.admin|bankMaster|deleteNotPossible')]);

        }

    }

}

