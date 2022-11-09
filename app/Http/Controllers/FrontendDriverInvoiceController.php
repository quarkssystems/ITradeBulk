<?php

namespace App\Http\Controllers;

use App\Exports\DataGridExport;
use App\Http\Controllers\Helpers\DataGrid;
use App\Models\Category;
use App\Models\SalesOrder;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Excel;
use App\User;
use DB;

class FrontendDriverInvoiceController extends Controller
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

    public function edit($sales_uuid, SalesOrder $salesorder): View
    {
        $sales_order = $salesorder->where('uuid', $sales_uuid)->first();
        $order_status  = $salesorder->getOrderStatusDropdown();
        $statuses = $salesorder->getStatusesDropDown();
        $pageTitle = "View Order #" . $sales_order->order_number;
        $route = $this->route;
        $role = auth()->user()->role;
        return view('user.salesOrder.driverinvoice', compact('sales_order', 'pageTitle', 'route', 'role', 'statuses', 'order_status'));
    }
}
