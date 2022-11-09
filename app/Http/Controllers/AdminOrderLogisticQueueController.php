<?php

namespace App\Http\Controllers;

use App\Exports\DataGridExport;
use App\Http\Controllers\Helpers\DataGrid;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\SalesOrder;
use App\Models\OrderLogisticQueue;
use App\Models\Tax;
use App\General\ChangeOrderStatus;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Excel;
use App\User;
use DB;
use Session;

class AdminOrderLogisticQueueController extends Controller
{
    use DataGrid;

    public $route = 'admin.order-logistic-queue';

    public function index(Request $request, OrderLogisticQueue $orderLogisticQueue, User $user, Excel $excel)
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
            'title' => 'Transporter',
            'column' => 'driver_id',
            'operator' => 'LIKE',
            'sorting' => true,

        ];

        $filters[] = [
            'title' => 'Date',
            'column' => 'created_at',
            'operator' => '=',
            'sorting' => true
        ];


        $filters[] = [
            'title' => 'Status',
            'column' => 'status',
            'operator' => '=',
            'sorting' => true,
            'search' => [
                'type' => 'select',
                'placeholder' => 'Show all',
                'data' => $orderLogisticQueue->getStatusDropdown()
            ]
        ];


        $tableName = $orderLogisticQueue->getTable();
        $url = route($this->route . ".index");
        $this->setGridModel($orderLogisticQueue);
        $this->setGridRequest($request);
        $this->setFilters($filters);

        $this->setScopesWithValue(["ofOrder" => $orderLogisticQueue->getOrderId()]);

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

        // if($request->has('export_data')) {
        //     $fileName = 'ORDER_LOGISTIC_QUEUE_DATA';
        //     return $excel->download(new DataGridExport('admin.OrderLogisticQueue.export', $data), "$fileName.xlsx");
        // }

        $route = $this->route;
        $status = $orderLogisticQueue->getStatusDropdown();
        $drivers = $user->getDropDownDriver();
        $pageTitle = "MANAGE ORDER LOGISIC QUEUE";

        $driverData = $orderLogisticQueue->getDrivers('7e64abc8-7e55-4605-a1dc-09a1da754dd9');
        // dd($driverData);



        if (Session::has('OrderLogisticQueuePage')) {
            Session::forget('OrderLogisticQueuePage');
        }
        Session::put('OrderLogisticQueuePage', $request->input('page') ?? 1);

        if ($request->ajax()) {
            return view('admin.orderLogisticQueue.grid', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'route', 'status', 'drivers', 'driverData'));
        } else {
            return view('admin.orderLogisticQueue.index', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'pageTitle', 'route', 'status', 'drivers', 'driverData'));
        }
    }


    public function accept(Request $request)
    {

        // print_r($request);die();

        $input = $request->all();

        // print_r($input['driver_id']);die();

        if ($input['order_id']) {

            OrderLogisticQueue::where('order_id', $input['order_id'])->where('driver_id', $input['driver_id'])->update(['status' => $input['val']]);

            // DB::enableQueryLog(); // Enable query log
            if ($input['val'] == 'OCCUPIED') {
                SalesOrder::where('uuid', $input['order_id'])->update(['logistic_id' => $input['driver_id']]);
            } else {
                SalesOrder::where('uuid', $input['order_id'])->update(['logistic_id' => null]);
            }
            // dd(DB::getQueryLog()); // Show results of log

            $data = OrderLogisticQueue::where('order_id', $input['order_id'])->where('driver_id', $input['driver_id'])->first();

            $req['order_uuid'] = $input['order_id'];

            $req['sender_id'] = $data->supplier_id;

            $req['receiver_id'] = $input['driver_id'];
            $req['type'] = Notification::DRIVER;

            // dd($req);die();

            ChangeOrderStatus::otpsend($req);


            return 'Status Changed.';
        }
    }
}
