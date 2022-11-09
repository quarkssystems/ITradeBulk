<?php

namespace App\Http\Controllers;

use App\Exports\DataGridExport;
use App\Http\Controllers\Helpers\DataGrid;
use App\Models\Category;
use App\Models\SalesOrder;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Excel;

class FrontendSupplierSalesOrderController extends Controller
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

//        $filters[] = [
//            'title' => 'Supplier',
//            'column' => 'supplier_id',
//            'operator' => 'LIKE',
//            'sorting' => true,
//            'search' => [
//                'type' => 'text',
//                'placeholder' => 'Search supplier'
//            ]
//        ];



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

        $tableName = $salesOrder->getTable();
        $url = route($this->route. ".index");
        $this->setGridModel($salesOrder);
        $this->setGridRequest($request);
        $this->setFilters($filters);
        $this->setScopesWithValue(["ofSupplier" => auth()->user()->uuid]);

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
            $fileName = 'ORDER_DATA';
            return $excel->download(new DataGridExport('user.salesOrder.export', $data), "$fileName.xlsx");
        }


        $route = $this->route;

        $pageTitle = "MANAGE ORDERS";

        if ($request->ajax()) {
            return view('supplier.salesOrder.grid', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'route'));
        } else {
            return view('supplier.salesOrder.index', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'pageTitle', 'route'));
        }
    }

    public function edit(SalesOrder $sales_order) : View
    {
        $statuses = $sales_order->getStatusesDropDown();
        $pageTitle = "View Order #". $sales_order->order_number;
        $route = $this->route;
        return view('user.salesOrder.view', compact('sales_order', 'pageTitle', 'route', 'role', 'statuses', 'copy'));
    }
}
