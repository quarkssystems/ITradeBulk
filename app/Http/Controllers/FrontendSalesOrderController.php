<?php



namespace App\Http\Controllers;

use App\AdminDetails;
use App\Exports\DataGridExport;

use App\Http\Controllers\Helpers\DataGrid;

use App\Models\Category;

use App\Models\SalesOrder;
use App\Models\WalletTransactions;
use App\PickingDocument;

use Illuminate\Http\Request;

use Illuminate\View\View;

use Maatwebsite\Excel\Excel;

use App\User;

use DB;



class FrontendSalesOrderController extends Controller

{

    use DataGrid;



    public $route = 'user.sales-orders';



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

        $filters[] = ['title' => 'No'];



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

        if (auth()->user()->role == "COMPANY") {
            $filters[] = [
                'title' => 'Driver',
                'column' => 'logistic_id',
                'operator' => 'LIKE',
                'sorting' => true,
                'search' => [
                    'type' => 'text',
                    'placeholder' => 'Search driver'
                ]
            ];
        }

        if (auth()->user()->logistic_type == "INDIVIDUAL" && auth()->user()->role == "DRIVER" || auth()->user()->logistic_type == "COMPANY" && auth()->user()->role == "COMPANY" || auth()->user()->role == 'VENDOR' || auth()->user()->role == 'SUPPLIER') {
            $filters[] = [
                'title' => 'Amount',
                'column' => 'final_total',
                'operator' => 'LIKE',
                'sorting' => true,
                'search' => [
                    'type' => 'text',
                    'placeholder' => 'Search total'
                ]
            ];
        }


        $orderStatus = $salesOrder->getOrderStatusDropdown();

        $placeHolder = 'Select status';
        if (isset($orderStatus['--SELECT ORDER STATUS--'])) {
            $placeHolder = $orderStatus['--SELECT ORDER STATUS--'];
            unset($orderStatus['--SELECT ORDER STATUS--']);
        }
        $filters[] = [

            'title' => 'Status',

            'column' => 'order_status',

            'operator' => '=',

            'sorting' => true,

            'search' => [

                'type' => 'select',

                'placeholder' => $placeHolder,

                'data' => $orderStatus

            ]

        ];



        if (auth()->user()->logistic_type == "COMPANY" && auth()->user()->role == "COMPANY" || auth()->user()->logistic_type == "INDIVIDUAL" && auth()->user()->role == "DRIVER") {
            $filters[] = [
                'title' => 'Transporter Invoice',
                'operator' => '=',
                'sorting' => true
            ];
        }



        $filters[] = [

            'title' => 'Date',

            'column' => 'sales_orders.created_at',

            'operator' => '=',

            'sorting' => true

        ];



        $tableName = $salesOrder->getTable();

        $url = route($this->route . ".index");

        $this->setGridModel($salesOrder);

        $this->setGridRequest($request);

        $this->setFilters($filters);

        if (auth()->user()->role == "SUPPLIER") {

            $this->setScopesWithValue(["OfSupplier" => auth()->user()->uuid]);
        } else if (auth()->user()->role == "VENDOR") {

            $this->setScopesWithValue(["OfUser" => auth()->user()->uuid]);
        } else if (auth()->user()->role == "DRIVER") {

            $this->setScopesWithValue(["OfDriver" => auth()->user()->uuid]);
        } else if (auth()->user()->role == "COMPANY") {

            $this->setScopesWithValue(["OfCompany" => auth()->user()->uuid]);
        } else if (auth()->user()->role == "DISPATCHER") {

            $this->setScopesWithValue(["OfDispatcher" => auth()->user()->uuid]);
        } else if (auth()->user()->role == "PICKER") {

            $this->setScopesWithValue(["OfPicker" => auth()->user()->uuid]);
        }



        //dd($request->session()->get($this->sorting['sort']));

        $this->setSorting(['sorting_field' => $tableName . '_sorting_field', 'sort' => $tableName . '_sort', 'default_field' => $tableName . '.created_at', 'default_sort' => 'DESC']);



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

            return $excel->download(new DataGridExport('user.salesOrder.export', $data), "$fileName.xlsx");
        }





        $route = $this->route;

        // dd($data[0]->basket_items);
        // dd($data,$sales_order->basket_items);

        $pageTitle = "MANAGE ORDERS";

        $role = auth()->user()->role;
        $logistic_type = auth()->user()->logistic_type;

        if ($request->ajax()) {

            return view('user.salesOrder.grid', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'route', 'role', 'logistic_type'));
        } else {

            return view('user.salesOrder.index', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'pageTitle', 'route', 'role', 'logistic_type'));
        }
    }



    public function edit(SalesOrder $sales_order): View

    {



        $order_status  = $sales_order->getOrderStatusDropdown();

        $pickerobj  = User::where('role', 'PICKER')->where('status', 'ACTIVE')->where('supplier_id', auth()->user()->uuid)->get();
        $picker = [];
        foreach ($pickerobj as $key => $pickerData) {
            $picker['select_all'] = 'Select All';
            $picker[$pickerData->uuid] = $pickerData->getNameAttribute();
        }

        $userLogin = User::where('role', 'PICKER')->where('uuid', auth()->user()->uuid)->first();
        // dd($userLogin);
        if ($userLogin != null) {
            $supplier_id = $userLogin->supplier_id;
        } else {
            $supplier_id = auth()->user()->uuid;
        }


        $dispatcherobj  = User::where('role', 'DISPATCHER')->where('status', 'ACTIVE')->where('supplier_id', $supplier_id)->get();
        $dispatcher = [];
        foreach ($dispatcherobj as $key => $dispatcherData) {
            $dispatcher['select_all'] = 'Select All';
            $dispatcher[$dispatcherData->uuid] = $dispatcherData->getNameAttribute();
        }



        $statuses = $sales_order->getStatusesDropDown();

        $pageTitle = "View Order #" . $sales_order->order_number;

        $curr_order_status = $sales_order->order_status;

        $route = $this->route;

        $role = auth()->user()->role;

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

        return view('user.salesOrder.view', compact('sales_order', 'pageTitle', 'route', 'role', 'statuses', 'order_status', 'curr_order_status', 'proformaInvoice', 'supplierOwnInvoice', 'picker', 'dispatcher'));
    }


    public function pendingOrder(SalesOrder $salesOrderModel): View
    {
        $orders = $salesOrderModel->getPendingOrders();
        // dd($orders);
        $route = $this->route;
        $role = auth()->user()->role;
        $pageTitle = "Pending Order";
        $title = $pageTitle;
        return view('user.salesOrder.pendingOrder', compact('orders',  'title', 'pageTitle', 'route', 'role'));
    }

    public function companyPendingOrder(SalesOrder $salesOrderModel): View
    {
        $orders = $salesOrderModel->getCompanyPendingOrders();
        // dd($orders);
        $route = $this->route;
        $role = auth()->user()->role;
        $pageTitle = "Pending Order";
        $title = $pageTitle;
        return view('user.salesOrder.pendingOrder', compact('orders',  'title', 'pageTitle', 'route', 'role'));
    }

    public function completedOrder(SalesOrder $salesOrderModel): View
    {
        $orders = $salesOrderModel->getCompletedOrders();
        // dd($orders);
        $route = $this->route;
        $role = auth()->user()->role;
        $pageTitle = "Completed Order";
        $title = $pageTitle;
        return view('user.salesOrder.completedOrder', compact('orders',  'title', 'pageTitle', 'route', 'role'));
    }

    public function companyCompletedOrder(SalesOrder $salesOrderModel): View
    {
        $orders = $salesOrderModel->getCompanyCompletedOrders();
        // dd($orders);
        $route = $this->route;
        $role = auth()->user()->role;
        $pageTitle = "Completed Order";
        $title = $pageTitle;
        return view('user.salesOrder.completedOrder', compact('orders',  'title', 'pageTitle', 'route', 'role'));
    }

    public function Itbinvoice($id)
    {
        $salesData = SalesOrder::leftjoin('baskets', 'baskets.order_id', 'sales_orders.uuid')
            ->where('sales_orders.uuid', $id)
            ->select('sales_orders.*', 'baskets.uuid as basket_id')
            ->first();
        $supplierData = User::where('uuid', $salesData->supplier_id)->with('company')->first();

        $user = User::with('company')->where('role', 'ADMIN')->first();
        // $tax = WalletTransactions::where('order_id', $id)->get();
        $tax = WalletTransactions::where('order_id', $id)->where('user_id', $user->uuid)->first();
        // dd($tax);

        $adminDetails = AdminDetails::orderBy('id', 'asc')->first();

        if (auth()->user()->role == 'ADMIN') {
            $pageTitle = 'ITB Invoice';
            return view('admin.invoice.ITBinvoice', compact('supplierData', 'tax', 'user', 'salesData', 'pageTitle', 'adminDetails'));
        } else {

            return view('supplier.document.ITBinvoice', compact('supplierData', 'tax', 'user', 'salesData', 'adminDetails'));
        }
        // dd('hi');

    }
}
