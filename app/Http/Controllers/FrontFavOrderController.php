<?php

namespace App\Http\Controllers;

use App\Models\Basket;
use App\Models\SalesOrder;
use App\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\BasketProducts;
use DB;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Mail;
use PDF;
use App\Models\LogisticDetails;
use App\Models\Setting;

class FrontFavOrderController extends Controller
{
    public $route = 'user.fav-orders';

    public function index(SalesOrder $salesOrderModel,Basket $basketModel) : View
    {
       // $orders = $salesOrderModel->where('order_status','PENDING')->orderBy('created_at','desc')->limit(10)->get();
        //Get on basket
        // $orders = $basketModel->getLastOrders();
        // dd($orders);

        $recentOrders = SalesOrder::leftjoin('baskets','baskets.order_id','=','sales_orders.uuid')
        ->select('sales_orders.uuid as salesID',
        'sales_orders.order_id',
        'sales_orders.user_id',
        'sales_orders.supplier_id',
        'sales_orders.logistic_id',
        'baskets.uuid',
        'sales_orders.cart_amount',
        'sales_orders.created_at'
        )
        ->where('sales_orders.user_id','=',auth()->user()->uuid)->where('sales_orders.order_status','=','DELIVERED')->where('sales_orders.payment_status','=','COMPLETED')->orderBy('sales_orders.created_at', 'desc')->get();
        $route = $this->route;
        $role = auth()->user()->role;
        $pageTitle = "Recent Orders";
        $title =$pageTitle;
        return view('user.favorders.index', compact('recentOrders',  'title', 'pageTitle', 'route', 'role'));
    }
    
    /**
    * Add to cart Fav Order
    */
    public function addorder(Request $request,Basket $basketModel)
    {
        $basket_Id = session()->get('basket_id', null);
        // dd($basket_Id);
        // dd($request->basket_uuid);
        $basketId = $request->basket_uuid; //Old order basket id
        $basket = $basketModel->where('uuid', $basketId)->first();
        $products = $basket->products;
        // dd(is_null($basket_Id)); 
        if(is_null($basket_Id))
        {   
            $basketIdNew = $basketModel->createNewBasket()->uuid;
            foreach($products as $product_data)  
            {
                BasketProducts::create(['basket_id' => $basketIdNew, 'product_id' =>$product_data->product_id, 'single_qty' => $product_data->single_qty, 'shrink_qty' => $product_data->shrink_qty, 'case_qty' => $product_data->case_qty , 'pallet_qty' => $product_data->pallet_qty]);
            }
            session(['basket_id' => $basketIdNew]);
        }
        else
        {   
            foreach($products as $product_data)  
            {
                
                 $currBasket = BasketProducts::where('basket_id','=',$basket_Id)->where('product_id','=',$product_data->product_id)->first();
                 
                 if($currBasket){
                        
                    $currBasket->update(['single_qty' => $product_data->single_qty + $currBasket->single_qty, 'shrink_qty' => $product_data->shrink_qty + $currBasket->shrink_qty, 'case_qty' => $product_data->case_qty + $currBasket->case_qty, 'pallet_qty' => $product_data->pallet_qty + $currBasket->pallet_qty]);    

                 }else{

                    BasketProducts::create(['basket_id' => $basket_Id, 'product_id' =>$product_data->product_id, 'single_qty' => $product_data->single_qty, 'shrink_qty' => $product_data->shrink_qty, 'case_qty' => $product_data->case_qty , 'pallet_qty' => $product_data->pallet_qty]);
                 }
            }
             session(['basket_id' => $basket_Id]);
        }
        // dd($your_cart);
        return redirect()->route('checkout.cart');

    }
    /**
     * Purpose : Last Order
     * Since FEB,2020
     */
    public function lastorder(SalesOrder $salesOrderModel,Basket $basketModel) : View
    {
        $orders = $basketModel->getLastOrders(1);
        $route = $this->route;
        $role = auth()->user()->role;
        $pageTitle = "Last Order";
        $title =$pageTitle;
        return view('user.favorders.index', compact('orders',  'title', 'pageTitle', 'route', 'role'));
    }



    public function transporterInvoice()
    {

        $user = New User;
        $orderModel = New SalesOrder;
        $logistic = New LogisticDetails;
        $settings = New Setting;
        $charge_itz = $settings->get("itz_transporter_charge");

        $phone = '+88 0123 4567 890, +88 0123 4567 999';
        $facebook_url = 'https://www.facebook.com/';
        $instagram_url = 'https://www.instagram.com/';
        $twitter_url = 'https://www.twitter.com/';
        $pinterest_url = 'https://www.pinterest.com/';

        $orderDetail = $orderModel->where('uuid','b2eb75bf-11c0-41fe-b299-62ad80a54592')->first();
        $transporterDetail = $user->where('uuid','1e29a97a-71d7-4dfc-ab37-dac6cfc72854')->first();
        $logisticDetail = $logistic->where('user_id','1e29a97a-71d7-4dfc-ab37-dac6cfc72854')->first();
        $transporterEmail = $transporterDetail->email;
        $invoiceNO = $orderDetail->order_number;
        $shipmentAmount = $orderDetail->shipment_amount - ($orderDetail->shipment_amount*$charge_itz)/100;
        $adminCharge = ($orderDetail->shipment_amount*$charge_itz)/100;
        // dd($adminCharge);

        $pdf = PDF::loadView('frontend.checkout.transporterInvoice',compact('orderDetail', 'transporterDetail', 'logisticDetail', 'shipmentAmount', 'adminCharge'))->setPaper('a4');

        $email = EmailTemplate::where('name','=','transporter_order_invoice')->first();

        if(isset($email)){
            $email->description = str_replace('[CUSTOMER_NAME]', $transporterDetail->first_name.' '.$transporterDetail->last_name , $email->description);
            $email->description = str_replace('[INVOICE_NO]', $orderDetail->order_number, $email->description);
            $email->description = str_replace('[EMAIL]', env('SUPPORT'), $email->description);
            $email->description = str_replace('[SITE_NAME]',env('WEBSITE'), $email->description);
            $email->description = str_replace('[PHONE]', $phone, $email->description);
            $email->description = str_replace('[FACEBOOK_URL]', $facebook_url, $email->description);
            $email->description = str_replace('[TWITTER_URL]', $twitter_url, $email->description);
            $email->description = str_replace('[INSTAGRAM_URL]', $instagram_url, $email->description);
            $email->description = str_replace('[PINTEREST_URL]', $pinterest_url, $email->description);
            $email->description = str_replace('[LOGO]', asset('assets/images/logo.png'), $email->description);
        } 

        $emailContent = $email->description;
        $admin_email = env('MAIL_USERNAME');

        Mail::send([], [], function ($message) use ($transporterEmail, $pdf, $emailContent, $admin_email) {
           $message->to($transporterEmail)
            ->subject('Transporter Order Receipt')
            ->setBody($emailContent, 'text/html'); // for HTML rich messages
            $message->attachData($pdf->output(),'customer.pdf');
            $message->from($admin_email,env('APP_NAME'));
        });

        return redirect()->route('supplier.dashboard')->with(['status' => 'success', 'message' => "mail sent successfully"]);

    }

}