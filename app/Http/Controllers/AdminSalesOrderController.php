<?php



namespace App\Http\Controllers;



use App\Exports\DataGridExport;

use App\Http\Controllers\Helpers\DataGrid;

use App\Models\Brand;

use App\Models\Category;

use App\Models\Product;

use App\Models\SalesOrder;

use App\Models\Tax;
use App\PickingDocument;

use Illuminate\Http\Request;

use Illuminate\View\View;

use Maatwebsite\Excel\Excel;

use App\User;

use Session;

class AdminSalesOrderController extends Controller

{

    use DataGrid;



    public $route = 'admin.sales-orders';



    /**

     * @param Request $request

     * @param Category $categoryModel

     * @param Excel $excel

     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|Excel|\Symfony\Component\HttpFoundation\BinaryFileResponse

     * @throws \PhpOffice\PhpSpreadsheet\Exception

     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception

     */

    public function index(Request $request, SalesOrder $salesOrder, Excel $excel)

    {

        $filters = [];

        $filters[] = ['title' => 'No', 'column' => 'No'];



        $filters[] = [

            'title' => 'Order Id',

            'column' => 'order_id',

            'operator' => 'LIKE',

            'sorting' => true,

            'search' => [

                'type' => 'text',

                'placeholder' => 'Search order id'

            ]

        ];

        $filters[] = [

            'title' => 'Trader',

            'column' => 'user_id',

            'operator' => 'LIKE',

            'sorting' => true,

            'search' => [

                'type' => 'text',

                'placeholder' => 'Search user'

            ]

        ];



        $filters[] = [

            'title' => 'Supplier',

            'column' => 'supplier_id',

            'operator' => 'LIKE',

            'sorting' => true,

            'search' => [

                'type' => 'text',

                'placeholder' => 'Search supplier'

            ]

        ];



        $filters[] = [

            'title' => 'Transporter',

            'column' => 'logistic_id',

            'operator' => 'LIKE',

            'sorting' => true,

            'search' => [

                'type' => 'text',

                'placeholder' => 'Search transporter'

            ]

        ];



        $filters[] = [

            'title' => 'Amount',

            'column' => 'final_total',

            'operator' => 'LIKE',

            'sorting' => true,

        ];



        $filters[] = [

            'title' => 'Status',

            'column' => 'order_status',

            'operator' => '=',

            'sorting' => true,

            'search' => [

                'type' => 'select',

                'placeholder' => 'Show all',

                'data' => $salesOrder->getOrderStatusDropdown()

            ]

        ];



        $filters[] = [

            'title' => 'Date',

            'column' => 'created_at',

            'operator' => '=',

            'sorting' => true

        ];



        $filters[] = [

            'title' => 'Action'

        ];



        $tableName = $salesOrder->getTable();

        $url = route($this->route . ".index");

        $this->setGridModel($salesOrder);

        $this->setGridRequest($request);

        $this->setFilters($filters);



        $this->setSorting(['sorting_field' => $tableName . '_sorting_field', 'sort' => $tableName . '_sort', 'default_field' => 'created_at', 'default_sort' => 'DESC']);



        $this->setGridUrl($url);



        $this->setGridVariables();



        if ($request->has('export_data')) {

            $this->setPaginationEnable(false);

            $data = $this->getGridData();
        } else {

            $data = $this->getGridData();

            $dataGridTitle = $this->gridTitles();

            $dataGridSearch = $this->gridSearch();

            $dataGridPagination = $this->gridPagination($data);
        }



        if ($request->has('export_data')) {

            $fileName = 'ORDER_DATA';

            return $excel->download(new DataGridExport('admin.salesOrder.export', $data), "$fileName.xlsx");
        }





        $route = $this->route;



        $order_status = $salesOrder->getOrderStatusDropdown();



        $pageTitle = "MANAGE ORDERS";

        if (Session::has('SalesOrderPage')) {
            Session::forget('SalesOrderPage');
        }
        Session::put('SalesOrderPage', $request->input('page') ?? 1);

        if ($request->ajax()) {

            return view('admin.salesOrder.grid', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'route', 'order_status'));
        } else {

            return view('admin.salesOrder.index', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'pageTitle', 'route', 'order_status'));
        }
    }



    public function edit(SalesOrder $sales_order, User $userModel): View

    {

        $statuses = $sales_order->getStatusesDropDown();

        $pageTitle = "View Order #" . $sales_order->order_number;

        $route = $this->route;

        $role = auth()->user()->role;



        $driversIds = $sales_order->drivers()->pluck('driver_id')->toArray();

        $Drivers = $userModel->whereIn('uuid', $driversIds)->with('company')->get();

        if (file_exists(public_path('invoice') . '/' . $sales_order->order_number . '.pdf')) {
            $proformaInvoice = url('invoice') . '/' . $sales_order->order_number . '.pdf';
        } else {
            $proformaInvoice = null;
        }


        if (file_exists(public_path('supplier_own_invoice/') . $sales_order->uuid)) {
            $files = \File::files(public_path('supplier_own_invoice/') . $sales_order->uuid);
            if (file_exists(public_path('supplier_own_invoice') . '/' . $sales_order->uuid . '/' . $files[0]->getRelativePathname())) {
                $supplierOwnInvoice = url('supplier_own_invoice') . '/' . $sales_order->uuid . '/' . $files[0]->getRelativePathname();
            } else {
                $supplierOwnInvoice = null;
            }
        } else {
            $supplierOwnInvoice = null;
        }

        if (file_exists(public_path('taxInvoice/') . $sales_order->order_number . '.pdf')) {
            $suppliertaxInvoice = url('taxInvoice') . '/' . $sales_order->order_number . '.pdf';
        } else {
            $suppliertaxInvoice = null;
        }


        $itbInvoice = route('admin.itbinvoice', [$sales_order->uuid]);

        if (isset($sales_order->basket_items)) {
            $orderId = $sales_order->uuid;
            $sales_order->basket_items = $sales_order->basket_items->transform(function ($query) use ($orderId) {

                $picking = PickingDocument::where('order_id', $orderId)->where('product_id', $query->product_id)->select('single_qty')->first();
                if ($picking != null) {
                    $query->single_qty = $picking->single_qty;
                }
                return $query;
            });
        }

        // dd($sales_order);
        return view('admin.salesOrder.view', compact('sales_order', 'pageTitle', 'route', 'role', 'Drivers', 'statuses', 'proformaInvoice', 'supplierOwnInvoice', 'itbInvoice', 'suppliertaxInvoice'));
    }



    // public function store(Request $request,SalesOrder $sales_order, User $userModel ){

    //     echo '<pre>';

    //     print_r($request->all());

    //     die;

    // }

}
