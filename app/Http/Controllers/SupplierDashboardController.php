<?php



namespace App\Http\Controllers;



use Illuminate\Http\Request;

use App\Models\SalesOrder;
use App\User;
use Illuminate\Support\Facades\Validator;

class SupplierDashboardController extends Controller

{

    public function index()
    {

        $pageTitle = 'Dashboard';

        $role = auth()->user()->role;

        $logistic_type = auth()->user()->logistic_type;

        $supplierOrder = SalesOrder::where('supplier_id', 'like', auth()->user()->uuid)->get()->count();

        $pickerOrder = SalesOrder::where('picker_id', 'like', auth()->user()->uuid)->get()->count();

        $dispatcherOrder = SalesOrder::where('dispatcher_id', 'like', auth()->user()->uuid)->get()->count();

        $vendorOrder = SalesOrder::where('user_id', 'like', auth()->user()->uuid)->get()->count();

        $companyTransporter = User::where('logistic_company_id', auth()->user()->uuid)->where('logistic_type', 'COMPANY')->get()->count();

        if (auth()->user()->role == 'COMPANY') {
            $orders = SalesOrder::leftjoin('users', 'sales_orders.logistic_id', '=', 'users.uuid')->where('users.logistic_company_id', '=', auth()->user()->uuid)->get();
        } else {
            $orders = SalesOrder::leftjoin('users', 'sales_orders.logistic_id', '=', 'users.uuid')->where('sales_orders.logistic_id', '=', auth()->user()->uuid)->get();
        }
        if (auth()->user()->role == 'PICKER') {
            $completedOrders = SalesOrder::where('picker_id', '=', auth()->user()->uuid)
                // ->where('order_status','CHOOSE PICKER')
                ->get();
        } else {
            $completedOrders = SalesOrder::leftjoin('users', 'sales_orders.logistic_id', '=', 'users.uuid')->where('sales_orders.logistic_id', '=', auth()->user()->uuid)->where('payment_status', 'COMPLETED')->where('order_status', 'DELIVERED')->get();
        }
        $totalOrder = $orders->count();

        if (auth()->user()->role == 'COMPANY') {
            $pendingOrders = SalesOrder::leftjoin('users', 'sales_orders.logistic_id', '=', 'users.uuid')->where('users.logistic_company_id', '=', auth()->user()->uuid)->where('payment_status', 'PENDING')->get();
        } else {
            $pendingOrders = SalesOrder::leftjoin('users', 'sales_orders.logistic_id', '=', 'users.uuid')->where('sales_orders.logistic_id', '=', auth()->user()->uuid)->where('payment_status', 'PENDING')->get();
        }
        $pendingOrder = $pendingOrders->count();

        if (auth()->user()->role == 'COMPANY') {
            $completedOrders = SalesOrder::leftjoin('users', 'sales_orders.logistic_id', '=', 'users.uuid')->where('users.logistic_company_id', '=', auth()->user()->uuid)->where('payment_status', 'COMPLETED')->where('order_status', 'DELIVERED')->get();
        } else {
            $completedOrders = SalesOrder::leftjoin('users', 'sales_orders.logistic_id', '=', 'users.uuid')->where('sales_orders.logistic_id', '=', auth()->user()->uuid)->where('payment_status', 'COMPLETED')->where('order_status', 'DELIVERED')->get();
        }



        $completedOrder = $completedOrders->count();

        // $totalCompanyOrder = SalesOrder::where('logistic_id','like',auth()->user()->uuid)->get()->count();

        // $pendingCompanyOrder = SalesOrder::where('logistic_id','like',auth()->user()->uuid)->where('payment_status','PENDING')->get()->count();

        // $completedCompanyOrder = SalesOrder::where('logistic_id','like',auth()->user()->uuid)->where('payment_status','COMPLETED')->get()->count();




        return view('supplier.dashboard.index', compact('pageTitle', 'role', 'supplierOrder', 'pickerOrder', 'dispatcherOrder', 'vendorOrder', 'logistic_type', 'companyTransporter', 'totalOrder', 'pendingOrder', 'completedOrder'));
    }

    public function supplierDelivery()
    {
        $pageTitle = 'Supplier Delivery Option';

        $role = auth()->user()->role;
        return view('supplier.dashboard.supplierDelivery', compact('pageTitle', 'role'));
    }

    public function supplierDeliveryStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'supplier_delivery' => 'required',
            'rate' => 'required_if:supplier_delivery,==,own_distributor',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        User::where('uuid', auth()->user()->uuid)->update(['supplier_delivery' => $request->supplier_delivery, 'delivery_rate' => $request->rate]);
        return redirect(route('supplier.supplier-delivery'))->with('message', 'Supplier delivery option updated successfully.');
    }

    public function adminSupplierDelivery($id)
    {
        $pageTitle = 'Supplier Delivery Option';

        $route = 'admin.supplier-delivery';

        $navTab = 'admin.users.supplier.navTab';

        $user = User::where('uuid', $id)->first();
        $role = auth()->user()->role;
        return view('supplier.dashboard.adminSupplierDelivery', compact('pageTitle', 'role', 'navTab', 'route', 'user'));
    }

    public function adminSupplierDeliveryStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'supplier_delivery' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }


        User::where('uuid', $request->user_id)->update(['supplier_delivery' => $request->supplier_delivery, 'delivery_rate' => $request->rate]);
        return redirect(route('admin.supplier-delivery', [$request->user_id]))->with('message', 'Supplier delivery option updated successfully.');
    }
}
