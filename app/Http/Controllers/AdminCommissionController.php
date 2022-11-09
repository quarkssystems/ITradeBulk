<?php



namespace App\Http\Controllers;



use App\Exports\DataGridExport;

use App\Http\Controllers\Helpers\DataGrid;

use App\Http\Requests\AdminTaxRequest;

use App\Models\WalletTransactions;

use App\User;

use Illuminate\Http\Request;

use Illuminate\View\View;

use Maatwebsite\Excel\Excel;

use Session;


class AdminCommissionController extends Controller

{

    use DataGrid;



    public $dataUrl = '/admin/admin-commission';



    public $route = 'admin.admin-commission';



    /**

     * @param Request $request

     * @param wallet $walletModel

     * @param Excel $excel

     * @return \Illuminate\Contracts\View\Factory|View|Excel|\Symfony\Component\HttpFoundation\BinaryFileResponse

     * @throws \PhpOffice\PhpSpreadsheet\Exception

     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception

     */

    public function index(Request $request, WalletTransactions $walletModel, Excel $excel)

    {

        $filters = [];

        $filters[] = ['title' => 'No'];


       
        $filters[] = [

            'title' => 'Order No',

            'column' => 'order_id',

            'operator' => '=',

            'sorting' => true,

            'search' => [

                'type' => 'text',

                'placeholder' => 'Search Order No'

            ]

        ];

        $filters[] = [

            'title' => 'Credit Amount',

            'column' => 'credit_amount',

            'operator' => '=',

            'sorting' => true,


        ];


         $filters[] = [

            'title' => 'Remark',

            'column' => 'remark',

            'operator' => '=',

            'sorting' => true,

        ];


        $filters[] = [

            'title' => 'iTradeBulk™ Commission(%)',
            // 'title' => 'ITZ Commission(%)',

            'column' => 'admin_charge',

            'operator' => '=',

            'sorting' => true,

        ];





        $filters[] = [

            'title' => 'Status',

            'column' => 'status',

            'operator' => '=',

            'sorting' => true,

            'search' => [

                'type' => 'select',

                'placeholder' => 'Show all',

                // 'data' => $walletModel->getStatusesDropDown()

                'data' => $walletModel->getStatusDropDown()

            ]

        ];



        $tableName = $walletModel->getTable();

        $url = $this->dataUrl;

       
        $this->setGridModel($walletModel);

        $this->setScopesWithValue(["OfAdmin" => auth()->user()->uuid]);

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

            $fileName = 'ADMINCOMMISSION_DATA';

            return $excel->download(new DataGridExport('admin.adminCommission.export', $data), "$fileName.xlsx");

        }





        $route = $this->route;



        $pageTitle = "Admin Commission";

        if(Session::has('AdminCommissionPage')){
            Session::forget('AdminCommissionPage');
        }
        Session::put('AdminCommissionPage', $request->input('page') ?? 1);


        if ($request->ajax()) {

            return view('admin.adminCommission.grid', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'route'));

        } else {

            return view('admin.adminCommission.index', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'pageTitle', 'route'));

        }

    }



    
}