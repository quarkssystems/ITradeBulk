<?php

namespace App\General;

use App\User;
use App\Models\SalesOrder;
use App\Models\UserDevices;
use App\Models\Otpgenerate;
use App\Models\UserCompany;
use App\Models\LogisticDetails;
use App\Models\OrderstatusUpdate;
use App\Models\OrderLogisticQueue;
use App\Models\WalletTransactions;
use App\Models\Basket;
use App\Models\Notification;
use App\Models\SupplierItemInventory;
use Carbon\Carbon;
use App\Models\Setting;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Mail;
use PDF;

//Only for API Call funcation
class ChangeOrderStatuschanges
{
    /***************************************************************/
    /*order packed any delivery type pickup call from website and app 
    OTP SEND SUPPLIER and Vendor*/
    /***************************************************************/
   

    public static function orderPickupPacked(array $request) //pickup  //PACKED
    {
        $data = array();

        $orderModel = New SalesOrder;
        $orderstatusUpdateModel = New OrderstatusUpdate;
        $otpModel = New Otpgenerate;
        $userdevicesModel = New UserDevices;
        $usernotifyModel = New Notification;

        

        $sales_id = $request['order_uuid'];
        $supplier_id = $request['supplier_id'];
        $vendor_id = $request['vendor_id'];
        $status = 'PACKED';
        $todayDate = Carbon::now()->format('Y-m-d');

        $orderData = $orderModel->where('uuid',$sales_id)->first();
        
        $otpdata = $otpModel->create([ 'sales_id' => $sales_id,'sender_id' => $supplier_id,'receiver_id' =>$vendor_id ,'status' => 'pending']);
        
         $data = array("OTP" => $otpdata->otp);
         
         //Supplier 
       //  $getsupplier_playerid=$userdevicesModel->where('user_id', $supplier_id)->first(); 
       // if($getsupplier_playerid)
       // {    
       //      $message['player_id'] = $getsupplier_playerid->player_id;
       //      $message['msg'] = $otpdata->otp .' is SECRET OTP for Order #'.$orderData->order_number.' at iTradezon on '.$todayDate.'. Please do not share this OTP.';
       //      $message['order_uuid'] = $sales_id;
       //      $notify_msg = $message['msg'];
       //       //notification add on able   
       //       $notify = $usernotifyModel->create([ 'user_id' => $supplier_id, 'order_id' => $sales_id ,'notification' =>$notify_msg]);
             
       //       $message['notification_uuid'] = $notify->uuid;   
       //       //send notification
       //       sendNotification($message);
       //  } 
       
       //  $getsupplier_playerid=$userdevicesModel->where('user_id', $vendor_id)->first();  
       //  if($getsupplier_playerid){
            
       //      $message['player_id'] = $getsupplier_playerid->player_id;
       //      $notify_msg = $message['msg'];
       //      $notify = $usernotifyModel->create([ 'user_id' => $vendor_id, 'order_id' => $sales_id ,'notification' =>$notify_msg]);
       //      $message['notification_uuid'] = $notify->uuid;   
       //       //send notification
       //       sendNotification($message);

         
       //  }


      	//manan 25-09-2020
        $getsupplier_playerid=$userdevicesModel->where('user_id', $vendor_id)->first(); 
	        if($getsupplier_playerid)
	       	{    
	            $message['player_id'] = $getsupplier_playerid->player_id;
	            $message['msg'] = $otpdata->otp .' is SECRET OTP for Order #'.$orderData->order_number.' at iTradezon on '.$todayDate.'. Please do not share this OTP.';
	            $message['order_uuid'] = $sales_id;
	            $notify_msg = $message['msg'];
	             //notification add on able   
	             $notify = $usernotifyModel->create([ 'user_id' => $vendor_id, 'order_id' => $sales_id ,'notification' =>$notify_msg]);
	             
	             $message['notification_uuid'] = $notify->uuid;   
	             //send notification
	             sendNotification($message);
	        } 



    }

    

    /***************************************************************/
    /*order packed any delivery type Delivery call from website and app */
    /***************************************************************/
 public static function orderDeliveryPacked(array $request) //Delivery  //PACKED
    {

        $data = array();
        $user = New User;
        $userdevicesModel = New UserDevices;
        $orderModel = New SalesOrder;
        $orderstatusUpdateModel = New OrderstatusUpdate;
        $otpModel = New Otpgenerate;
        $orderLogisticQueueModel = New OrderLogisticQueue;
         $usernotifyModel = New Notification;


        $sales_id = $request['order_uuid'];
        $supplier_id = $request['supplier_id'];
        $vendor_id = $request['vendor_id'];
        $status = 'PACKED';

        $todayDate = Carbon::now()->format('Y-m-d');
        $orderData = $orderModel->where('uuid',$sales_id)->first();
        //Write code for nearer driver 
        $suppiers = $user->where('uuid', $supplier_id)->first();


        //$drivers = $user->getNearestDriver( $supplier->latitude, $supplier->longitude);
         $drivers = $user->select('uuid','latitude','longitude')->whereHas('documentUploaded', function($q) use ($request){
                $q->where('approved', '=' ,"YES");
            });
           $drivers =$drivers->where('status','ACTIVE');
           $drivers =$drivers->where('role','DRIVER'); 
           $drivers =$drivers->get();
          
        $nearer_driver = array();   
        foreach ($drivers as $key => $driver)
        {   
         
            $nearer_driver[$driver->uuid] = $user->getDrivingDistance($driver->latitude,$driver->longitude,$suppiers->latitude,$suppiers->longitude);  
        }      
        asort($nearer_driver);
        
        

        /* $drivers = $drivers->whereHas('driverOrder', function($q1) use ($request){
                $q1->where('order_status', '!=' ,"DELIVERED");
            });
         */        
        foreach ($nearer_driver as $driver_id => $distance_value)
        {  
            
         //temp distance is upslier with driver
            $orderLogisticQueueModel->create([
            'order_id' => $sales_id,
            'vendor_id' => $vendor_id,
            'supplier_id' => $supplier_id,
            'driver_id' => $driver_id,
            'distance' => 0,
            'status' => "ACCEPT"
            ]);

           //Notificatiom accept reject to driver
             $getsupplier_playerid=$userdevicesModel->where('user_id', $driver_id)->first();  
             if($getsupplier_playerid){

                $message['player_id'] = $getsupplier_playerid->player_id;
                $message['msg'] = '#'.$orderData->order_number.' at iTradezon on '.$todayDate.' Are you ready to deliver this order?';
                $message['order_uuid'] = $sales_id;
                $message['driver'] = $sales_id;
                
                $notify_msg = $message['msg'];
                $notify = $usernotifyModel->create([ 'user_id' => $driver_id, 'order_id' => $sales_id ,'notification' =>$notify_msg]);

                $message['notification_uuid'] = $notify->uuid;   
                 //send notification
                 sendNotification($message);
             }
        }  

    }


    public static function walletTransaction($transaction_id){
        $user = New User;
        $walletTransactionModel = New WalletTransactions;
        $userdevicesModel = New UserDevices;
        $usernotifyModel = New Notification;
        $todayDate = Carbon::now()->format('Y-m-d');

        $walletData = $walletTransactionModel->where('uuid',$transaction_id)->first();
        $userData = $user->where('uuid',$walletData->user_id)->first();

        $get_playerid = $userdevicesModel->where('user_id', $walletData->user_id)->first();

        if($get_playerid){
            $message['player_id'] = $get_playerid->player_id;

            if($walletData->status == 'APPROVED'){
                $message['msg'] = 'Your wallet transaction has been approved for W-'. $walletData->id .' at iTradezon on ' . $todayDate.'.';
            }else {
                $message['msg'] = 'Your wallet transaction has been cancelled for W-'. $walletData->id .' at iTradezon on ' . $todayDate.'.';   
            }
            // $notify_msg = $message['msg']; 
            // $notify = $usernotifyModel->create([ 'user_id' => $walletData->user_id ,'notification' =>$notify_msg]);
            // $message['notification_uuid'] = $notify->uuid;
            sendWalletNotification($message);
        }

    }

    
    public static function orderStatus(array $request)  //Notification send here
   {
        $user = New User;
        $orderModel = New SalesOrder;   
        $orderstatusUpdateModel = New OrderstatusUpdate;
        $otpModel = New Otpgenerate;
        $userCompany = New UserCompany;
        $logistic = New LogisticDetails;
        $userdevicesModel = New UserDevices;
        $walletTransactionModel = New WalletTransactions;
        $basketModel = New Basket;
        $inventary = New SupplierItemInventory;
        $usernotifyModel = New Notification;

        $status = $request['order_status'];    
        $sales_id = $request['order_uuid'];
        $user_id = $request['user_id'];
        
        //$delivery_type = $request['delivery_type'];
      
        $todayDate = Carbon::now()->format('Y-m-d');
        
        $order_uuid ='';
    if($orderstatusUpdateModel->where('sales_id' , $sales_id)->where('order_status' , $status)->count() == 0)
        {
            $p_order_status = "";    
            $prv_order_status = $orderstatusUpdateModel->where('sales_id' , $sales_id)->pluck('order_status')->toArray();
            if($prv_order_status){
            $p_order_status = $prv_order_status[0];
            }
           $order_uuid = $orderModel->where('uuid',$sales_id)->update(['order_status'=> $status ]);
            $orderstatusUpdateModel->create([ 'sales_id' => $sales_id,'user_id' => $user_id,'order_status' => $status ]);
            $orderData = $orderModel->where('uuid',$sales_id)->first();
            $userCompanyData = $userCompany->where('owner_user_id',$orderData->supplier_id)->first();
            // $userid is current login id  only order placed then  we pass suppier id //code on website and api also have
             $get_playerid = $userdevicesModel->where('user_id', $user_id)->first();
            if($get_playerid){
                $message['player_id'] = $get_playerid->player_id;
                // $message['msg'] = 'Order status Changed for Order no #'.$orderData->order_number.' is '.$status.' at iTradezon on '.$todayDate;

                if($status == 'PLACED') {
                    $message['msg'] = 'New Order from iTradezon with Order no. #'. $orderData->order_number .' on ' . $todayDate.'.';
                } elseif($status == 'PACKED') {
                    $message['msg'] = 'Order status changed: Order no. #'. $orderData->order_number .' is '. $status .' at '. $userCompanyData->trading_name .' on '. $todayDate.'.';  
                } else {
                    $message['msg'] = 'Order status changed for Order no. #'.$orderData->order_number.' is '.$status.' at iTradezon on '.$todayDate.'.';
                }

                 $message['order_uuid'] = $sales_id;
                 $notify_msg = $message['msg']; 
                 $notify = $usernotifyModel->create([ 'user_id' => $user_id, 'order_id' => $sales_id ,'notification' =>$notify_msg]); 

                $message['notification_uuid'] = $notify->uuid;   
                 //send notification
                 sendNotification($message);
            } 

             //.user_id is vendor id   
            $get_playerid = $userdevicesModel->where('user_id', $orderData->user_id)->first();
           

            if($get_playerid){
                $message['player_id'] = $get_playerid->player_id;
                // $message['msg'] = 'Order status Changed for Order no #'.$orderData->order_number.' is '.$status.' at iTradezon on '.$todayDate;

                if($status == 'PLACED') {
                    $message['msg'] = 'Your order has been successfully placed at iTradezon with Order no. #'. $orderData->order_number .' on ' . $todayDate.'.';
                } elseif($status == 'PACKED') {
                    $message['msg'] = 'Order status changed: Order no. #'. $orderData->order_number .' is '. $status .' at '. $userCompanyData->trading_name .' on '. $todayDate.'.';  
                } else {
                    $message['msg'] = 'Order status changed for Order no. #'.$orderData->order_number.' is '.$status.' at iTradezon on '.$todayDate.'.';
                }

                 $message['order_uuid'] = $sales_id;
                $notify_msg = $message['msg'];
                $notify = $usernotifyModel->create([ 'user_id' => $orderData->user_id, 'order_id' => $sales_id ,'notification' =>$notify_msg]);
                 $message['notification_uuid'] = $notify->uuid;   
                 //send notification
                 sendNotification($message);
            } 

            $req_data = array();
            $req_data['order_uuid'] = $orderData->uuid;
            $req_data['supplier_id'] = $orderData->supplier_id;
            $req_data['vendor_id'] = $orderData->user_id;

            if($orderData->delivery_type =='pickup')
             {
                switch ($status) {
                    case 'PACKED':
                        ChangeOrderStatus::orderPickupPacked($req_data);
                        # code...
                        break;
                    case 'CANCELLED':

                        if($p_order_status == "PLACED") { 
                         $order_uuid = $orderModel->where('uuid',$sales_id)->update(['payment_status'=> 'CANCELLED' ]);
                       
                        $walletTransactionModel->where('order_id',$sales_id)->update(['status'=> 'CANCELED' ]);

                         $basket = $basketModel->where("order_id", $sales_id)->first();
                         if($basket){
                            $products = $basket->products;
                             foreach ($products as $pro) {
                                $inventary->where('product_id', $pro->product_id)->where('user_id', $orderData->supplier_id)->increment('single', $pro->single_qty);
                             }
                         }
                       //send notification when order cancal (curr user is vender so need to right code)
                           $get_playerid = $userdevicesModel->where('user_id', $orderData->supplier_id)->first();
                            if($get_playerid){
                                $message['player_id'] = $get_playerid->player_id;
                                // $message['msg'] = 'Order status Changed for Order no #'.$orderData->order_number.' is '.$status.' at iTradezon on '.$todayDate;

                                if($status == 'PLACED') {
                                    $message['msg'] = 'Your order has been successfully placed at iTradezon with Order no. #'. $orderData->order_number .' on ' . $todayDate.'.';
                                } elseif($status == 'PACKED') {
                                    $message['msg'] = 'Order status changed: Order no. #'. $orderData->order_number .' is '. $status .' at '. $userCompanyData->trading_name .' on '. $todayDate.'.';  
                                } else {
                                    $message['msg'] = 'Order status changed for Order no. #'.$orderData->order_number.' is '.$status.' at iTradezon on '.$todayDate.'.';
                                }

                                 $message['order_uuid'] = $sales_id;
                                //sendNotification($message);
                                 $notify_msg = $message['msg'];
                                $notify = $usernotifyModel->create([ 'user_id' => $orderData->user_id, 'order_id' => $sales_id ,'notification' =>$notify_msg]);
                                $message['notification_uuid'] = $notify->uuid;   
                                 //send notification
                                sendNotification($message);

                         
                        } 

                     }   
                    break;
                    case 'DELIVERED':
                        $order_uuid = $orderModel->where('uuid',$sales_id)->update(['payment_status'=> 'COMPLETED' ]);
                        $walletTransactionModel->where('order_id',$sales_id)->update(['status'=> 'APPROVED' ]);
                        
                           //supplier  wallet notification 
                         $supplier_wallet = $walletTransactionModel->where('order_id',$sales_id)->where('user_id', $orderData->supplier_id)->first();
                        $get_playerid = $userdevicesModel->where('user_id', $orderData->supplier_id)->first();
                            if($get_playerid){
                                $message['player_id'] = $get_playerid->player_id;
                                $message['msg'] = 'Your wallet credited with R'. number_format($supplier_wallet->credit_amount,2) .' For Order no. #'.$orderData->order_number.' at iTradezon on '.$todayDate.'.';
                                 $message['order_uuid'] = $sales_id;
                                //sendNotification($message);
                                 $notify_msg = $message['msg'];
                                $notify = $usernotifyModel->create([ 'user_id' => $orderData->supplier_id, 'order_id' => $sales_id ,'notification' =>$notify_msg]);
                                $message['notification_uuid'] = $notify->uuid;   
                                 //send notification
                                sendNotification($message);
                            }
                           //Admin charge  wallet notification       
                          $admin_user = $user->where('role','ADMIN')->first();
                          $admin_wallet = $walletTransactionModel->where('order_id',$sales_id)->where('user_id', $admin_user->uuid)->first();
                            $get_playerid_admin = $userdevicesModel->where('user_id', $admin_user->uuid)->first();
                            if($get_playerid_admin){
                                $message['player_id'] = $get_playerid_admin->player_id;
                                $message['msg'] = 'Your wallet credited with R'. number_format($admin_wallet->credit_amount,2) .' For Order no. #'.$orderData->order_number.' at iTradezon on '.$todayDate.'.';
                                 $message['order_uuid'] = $sales_id;
                                //sendNotification($message);
                                 $notify_msg = $message['msg'];
                                $notify = $usernotifyModel->create([ 'user_id' => $admin_user->uuid, 'order_id' => $sales_id ,'notification' =>$notify_msg]);
                                $message['notification_uuid'] = $notify->uuid;   
                                 //send notification
                                sendNotification($message);
                            }


                         //vendor  wallet notification for debit 
                        $get_playerid_vendor = $userdevicesModel->where('user_id', $orderData->user_id)->first();
                        $vendor_wallet = $walletTransactionModel->where('order_id',$sales_id)->where('user_id', $orderData->user_id)->first();
                            if($get_playerid_vendor){
                                $message['player_id'] = $get_playerid_vendor->player_id;
                                $message['msg'] = 'Your wallet debited with R'. number_format($vendor_wallet->debit_amount,2) .' For Order no. #'.$orderData->order_number.'  at iTradezon on '.$todayDate.'.';
                                 $message['order_uuid'] = $sales_id;
                                //sendNotification($message);
                                 $notify_msg = $message['msg'];
                                $notify = $usernotifyModel->create([ 'user_id' => $orderData->user_id, 'order_id' => $sales_id ,'notification' =>$notify_msg]);
                                $message['notification_uuid'] = $notify->uuid;   
                                 //send notification
                                sendNotification($message);
                              }  


                        # code...
                        break;

                    default:
                        # code...
                        break;
                }
             }
             
             if($orderData->delivery_type != 'pickup')
             {

               
                    switch ($status) {
                        case 'PACKED':
                            ChangeOrderStatus::orderDeliveryPacked($req_data);
                            break;
                        case 'DELIVERED':
                         $settings = new  Setting;
                          $charge_itz = $settings->get("itz_transporter_charge");
                          $admin_user = $user->where('role','ADMIN')->first();
                        
                         $order_uuid = $orderModel->where('uuid',$sales_id)->update(['payment_status'=> 'COMPLETED' ]);
                         $walletTransactionModel->where('order_id',$sales_id)->update(['status'=> 'APPROVED' ]);


                         $admin_credit_amt = ($orderData->shipment_amount*$charge_itz)/100; 
                        $walletTransactionModel->create([
                        "credit_amount" => $admin_credit_amt,
                        "debit_amount" =>0,
                        "user_id" => $admin_user->uuid,
                        "remarks" => "ADMIN CHARGE FOR SHIPMENT",
                        "order_id" => $sales_id ,
                        "status" => "APPROVED",
                        "admin_charge" =>$charge_itz

                        ]);

                         $get_playerid_admin = $userdevicesModel->where('user_id', $admin_user->uuid)->first();
                            if($get_playerid_admin){
                                $message['player_id'] = $get_playerid_admin->player_id;
                                $message['msg'] = 'Your wallet credited with R'. number_format($admin_credit_amt,2) .' , SHIPMENT charge For Order no. #'.$orderData->order_number.' at iTradezon on '.$todayDate.'.';
                                 $message['order_uuid'] = $sales_id;
                                //sendNotification($message);
                                 $notify_msg = $message['msg'];
                                $notify = $usernotifyModel->create([ 'user_id' => $admin_user->uuid, 'order_id' => $sales_id ,'notification' =>$notify_msg]);
                                $message['notification_uuid'] = $notify->uuid;   
                                 //send notification
                                sendNotification($message);
                            }

                         $driver_credit_amt =  $orderData->shipment_amount - ($orderData->shipment_amount*$charge_itz)/100;   
                        $walletTransactionModel->create([
                        "credit_amount" => $driver_credit_amt,
                        "debit_amount" =>0,
                        "user_id" => $orderData->logistic_id,
                        "remarks" => "DELIVERED PRODUCT",
                        "order_id" => $sales_id ,
                        "status" => "APPROVED",
                         "admin_charge" =>$charge_itz
                        ]);


                         $get_playerid_logistic = $userdevicesModel->where('user_id',  $orderData->logistic_id)->first();
                            if($get_playerid_logistic){
                                $message['player_id'] = $get_playerid_logistic->player_id;
                                $message['msg'] = 'Your wallet credited with R'. number_format($driver_credit_amt,2) .' , Delivered Product For Order no. #'.$orderData->order_number.' at iTradezon on '.$todayDate.'.';
                                 $message['order_uuid'] = $sales_id;
                                //sendNotification($message);
                                 $notify_msg = $message['msg'];
                                $notify = $usernotifyModel->create([ 'user_id' =>  $orderData->logistic_id, 'order_id' => $sales_id ,'notification' =>$notify_msg]);
                                $message['notification_uuid'] = $notify->uuid;   
                                 //send notification
                                sendNotification($message);
                            }   


                        //  $walletTransactionModel->create([
                        // "credit_amount" => 0,
                        // "debit_amount" => $orderData->shipment_amount,
                        // "user_id" =>  $orderData->user_id,
                        // "remarks" => "SHIPMENT CHARGE",
                        // "status" => "APPROVED",
                        // "order_id" => $sales_id ,
                        // "admin_charge" =>$charge_itz
                        // ]);

                           //supplier  wallet notification 
                         $supplier_wallet = $walletTransactionModel->where('order_id',$sales_id)->where('user_id', $orderData->supplier_id)->first();
                        $get_playerid = $userdevicesModel->where('user_id', $orderData->supplier_id)->first();
                            if($get_playerid){
                                $message['player_id'] = $get_playerid->player_id;
                                $message['msg'] = 'Your wallet credited with R'. number_format($supplier_wallet->credit_amount,2) .' For Order no. #'.$orderData->order_number.' at iTradezon on '.$todayDate.'.';
                                 $message['order_uuid'] = $sales_id;
                                //sendNotification($message);
                                 $notify_msg = $message['msg'];
                                $notify = $usernotifyModel->create([ 'user_id' => $orderData->supplier_id, 'order_id' => $sales_id ,'notification' =>$notify_msg]);
                                $message['notification_uuid'] = $notify->uuid;   
                                 //send notification
                                sendNotification($message);
                            }
                           //Admin charge  wallet notification       
                          $admin_user = $user->where('role','ADMIN')->first();
                          $admin_wallet = $walletTransactionModel->where('order_id',$sales_id)->where('user_id', $admin_user->uuid)->first();
                            $get_playerid_admin = $userdevicesModel->where('user_id', $admin_user->uuid)->first();
                            if($get_playerid_admin){
                                $message['player_id'] = $get_playerid_admin->player_id;
                                $message['msg'] = 'Your wallet credited with R'. number_format($admin_wallet->credit_amount,2) .' For Order no. #'.$orderData->order_number.' at iTradezon on '.$todayDate.'.';
                                 $message['order_uuid'] = $sales_id;
                                //sendNotification($message);
                                 $notify_msg = $message['msg'];
                                $notify = $usernotifyModel->create([ 'user_id' => $admin_user->uuid, 'order_id' => $sales_id ,'notification' =>$notify_msg]);
                                $message['notification_uuid'] = $notify->uuid;   
                                 //send notification
                                sendNotification($message);
                            }


                         //vendor  wallet notification for debit 
                        $get_playerid_vendor = $userdevicesModel->where('user_id', $orderData->user_id)->first();
                        $vendor_wallet = $walletTransactionModel->where('order_id',$sales_id)->where('user_id', $orderData->user_id)->sum('debit_amount');
                            if($get_playerid_vendor){
                                $message['player_id'] = $get_playerid_vendor->player_id;
                                $message['msg'] = 'Your wallet debited with R'. number_format($vendor_wallet,2) .' For Order no. #'.$orderData->order_number.'  at iTradezon on '.$todayDate.'.';
                                 $message['order_uuid'] = $sales_id;
                                //sendNotification($message);
                                 $notify_msg = $message['msg'];
                                $notify = $usernotifyModel->create([ 'user_id' => $orderData->user_id, 'order_id' => $sales_id ,'notification' =>$notify_msg]);
                                $message['notification_uuid'] = $notify->uuid;   
                                 //send notification
                                sendNotification($message);
                              }  

                        //transporter invoice

                        // $phone = '+88 0123 4567 890, +88 0123 4567 999';
                        // $facebook_url = 'https://www.facebook.com/';
                        // $instagram_url = 'https://www.instagram.com/';
                        // $twitter_url = 'https://www.twitter.com/';
                        // $pinterest_url = 'https://www.pinterest.com/';

                        // $userDetail = $user->where('uuid',$user_id)->first();
                        // $orderDetail = $orderModel->where('uuid',$sales_id)->first();
                        // $transporterDetail = $user->where('uuid',$orderDetail->logistic_id)->first();
                        // // $logisticDetail = $logistic->('user_id',$orderDetail->logistic_id)->first();
                        // $transporterEmail = $transporterDetail->email;

                        // $pdf = PDF::loadView('frontend.checkout.transporterInvoice',compact('userDetail', 'orderDetail', 'transporterDetail', 'logisticDetail'))->setPaper('a4');

                        // $email = EmailTemplate::where('name','=','transporter_order_invoice')->first();

                        // if(isset($email)){
                        //     $email->description = str_replace('[CUSTOMER_NAME]', $userDetail->first_name.' '.$userDetail->last_name , $email->description);
                        //     $email->description = str_replace('[INVOICE_NO]', $orderDetail->order_number, $email->description);
                        //     $email->description = str_replace('[EMAIL]', 'support@itradezon.com', $email->description);
                        //     $email->description = str_replace('[SITE_NAME]', 'iTradezon.com ', $email->description);
                        //     $email->description = str_replace('[PHONE]', $phone, $email->description);
                        //     $email->description = str_replace('[FACEBOOK_URL]', $facebook_url, $email->description);
                        //     $email->description = str_replace('[TWITTER_URL]', $twitter_url, $email->description);
                        //     $email->description = str_replace('[INSTAGRAM_URL]', $instagram_url, $email->description);
                        //     $email->description = str_replace('[PINTEREST_URL]', $pinterest_url, $email->description);
                        //     $email->description = str_replace('[LOGO]', asset('assets/images/logo.png'), $email->description);
                        // } 

                        // $emailContent = $email->description;

                        // Mail::send([], [], function ($message) use ($transporterEmail, $pdf, $emailContent) {
                        //    $message->to($transporterEmail)
                        //     ->subject('Transporter Order Invoice')
                        //     ->setBody($emailContent, 'text/html'); // for HTML rich messages
                        //     $message->attachData($pdf->output(),'customer.pdf');
                        //     $message->from('itradezon@gmail.com','Itradezon');
                        // });


                            break;        
                        case 'DISPATCHED':
                            //Otp genrated and add  Driver and Vender
                            $otpdata = $otpModel->create([ 'sales_id' => $sales_id,'sender_id' => $orderData->logistic_id,'receiver_id' =>$orderData->user_id ,'status' => 'pending']);

                              $req['order_uuid'] = $sales_id;   
                              $req['sender_id'] = $orderData->logistic_id; 
                              $req['receiver_id'] = $orderData->user_id; 

                              ChangeOrderStatus::otpsend($req); //supplier and driver otp
                            # code...
                            break;
                        case 'CANCELLED':

                        
                         if($p_order_status == "PLACED") { 

                             $order_uuid = $orderModel->where('uuid',$sales_id)->update(['payment_status'=> 'CANCELLED' ]);
                            $walletTransactionModel->create([
                            "credit_amount" => $orderData->final_total,
                            "debit_amount" =>0,
                            "user_id" => $orderData->user_id,
                            "remarks" => "CANCELLED ORDER BY VENDER",
                            "status" => "APPROVED"
                            ]);

                         $walletTransactionModel->create([
                            "credit_amount" => 0,
                            "debit_amount" => $orderData->cart_amount,
                            "user_id" => $orderData->supplier_id,
                            "remarks" => "CANCELLED ORDER BY VENDER",
                            "status" => "APPROVED" 
                            ]);

                         $basket = $basketModel->where("order_id", $sales_id)->first();
                         if($basket){
                            $products = $basket->products;
                             foreach ($products as $pro) {
                                $inventary->where('product_id', $pro->product_id)->where('user_id', $orderData->supplier_id)->increment('single', $pro->single_qty);
                             }
                         }
                            //User mobile current is vender so notificaion for supplier and driver
                            //send notification when order cancal 
                           $get_playerid = $userdevicesModel->where('user_id', $orderData->supplier_id)->first();
                            if($get_playerid){
                                $message['player_id'] = $get_playerid->player_id;
                                // $message['msg'] = 'Order status Changed for Order no #'.$orderData->order_number.' is '.$status.' at iTradezon on '.$todayDate;

                                if($status == 'PLACED') {
                                    $message['msg'] = 'Your order has been successfully placed at iTradezon with Order no. #'. $orderData->order_number .' on ' . $todayDate.'.';
                                } elseif($status == 'PACKED') {
                                    $message['msg'] = 'Order status changed: Order no. #'. $orderData->order_number .' is '. $status .' at '. $userCompanyData->trading_name .' on '. $todayDate.'.';  
                                } else {
                                    $message['msg'] = 'Order status changed for Order no. #'.$orderData->order_number.' is '.$status.' at iTradezon on '.$todayDate.'.';
                                }

                                 $message['order_uuid'] = $sales_id;
                                //sendNotification($message);
                                $notify_msg = $message['msg'];
                                $notify = $usernotifyModel->create([ 'user_id' => $orderData->supplier_id, 'order_id' => $sales_id ,'notification' =>$notify_msg]);

                                $message['notification_uuid'] = $notify->uuid;   
                              //send notification
                                sendNotification($message);

                             
                            }
                             $get_playerid = $userdevicesModel->where('user_id', $orderData->logistic_id)->first();
                            if($get_playerid){
                                $message['player_id'] = $get_playerid->player_id;
                                // $message['msg'] = 'Order status Changed for Order no #'.$orderData->order_number.' is '.$status.' at iTradezon on '.$todayDate;

                                if($status == 'PLACED') {
                                    $message['msg'] = 'Your order has been successfully placed at iTradezon with Order no. #'. $orderData->order_number .' on ' . $todayDate.'.';
                                } elseif($status == 'PACKED') {
                                    $message['msg'] = 'Order status changed: Order no. #'. $orderData->order_number .' is '. $status .' at '. $userCompanyData->trading_name .' on '. $todayDate.'.';  
                                } else {
                                    $message['msg'] = 'Order status changed for Order no. #'.$orderData->order_number.' is '.$status.' at iTradezon on '.$todayDate.'.';
                                }

                                $message['order_uuid'] = $sales_id;
                                //sendNotification($message);
                                $notify_msg = $message['msg'];
                                $notify = $usernotifyModel->create([ 'user_id' => $orderData->logistic_id, 'order_id' => $sales_id ,'notification' =>$notify_msg]);
                                
                                 $message['notification_uuid'] = $notify->uuid;   
                                //send notification
                                sendNotification($message);

                             
                            } 
                            


                         }   
                        break;   
                        default:
                            # code...
                            break;
                    }

             }   

           
           
            return  $order_uuid; 
       
        }
       
    }


     /***************************************************************/
    /*order packed of delivery type opt genate code */
    /***************************************************************/
    public static function otpsend(array $request) //Supplier and driver
    {
        $orderModel = New SalesOrder;
       $userdevicesModel = New UserDevices;
       $userCompany = New UserCompany;
       $otpModel = New Otpgenerate;
       $orderstatusUpdateModel = New OrderstatusUpdate;
        $usernotifyModel = New Notification;
       $sales_id = $request['order_uuid'];
       $sender_id    =  $request['sender_id'];
       $receiver_id  =  $request['receiver_id']; 
       $todayDate = Carbon::now()->format('Y-m-d');
        $orderData = $orderModel->where('uuid',$sales_id)->first();
        $userCompanyData = $userCompany->where('owner_user_id',$orderData->supplier_id)->first();
        $otpdata = $otpModel->create([ 'sales_id' => $sales_id,'sender_id' => $sender_id,'receiver_id' =>$receiver_id ,'status' => 'pending']);


           //Notificatiom supplier
             // $getsupplier_playerid=$userdevicesModel ->where('user_id', $sender_id)->first(); 
             // if($getsupplier_playerid){
             //   $message['player_id'] = $getsupplier_playerid->player_id;
             //   $message['msg'] = $otpdata->otp .' is SECRET OTP for Order #'.$orderData->order_number.' at iTradezon on '.$todayDate.'. Please do not share this OTP.';
             //     $message['order_uuid'] = $sales_id;
             //    //sendNotification($message); 
             //    $notify_msg = $message['msg'];
             //    $notify = $usernotifyModel->create([ 'user_id' => $sender_id, 'order_id' => $sales_id ,'notification' => $notify_msg]);
             //    $message['notification_uuid'] = $notify->uuid;   
             //                    //send notification
             //    sendNotification($message);
                 
             // } 
            
            
            //Notificatiom Driver
             $getsupplier_playerid=$userdevicesModel ->where('user_id', $receiver_id)->first(); 
              if($getsupplier_playerid){
                
                 $message['player_id'] = $getsupplier_playerid->player_id;
                $message['msg'] = $otpdata->otp .' is SECRET OTP for Order #'.$orderData->order_number.' at '.$userCompanyData->trading_name.' on '.$todayDate.'. Please do not share this OTP.';
                   $message['order_uuid'] = $sales_id;
                    
                   // sendNotification($message);
                    $notify_msg = $message['msg'];
                    $notify = $usernotifyModel->create([ 'user_id' => $receiver_id, 'order_id' => $sales_id ,'notification' => $notify_msg]);

                    $message['notification_uuid'] = $notify->uuid;   
                                //send notification
                    sendNotification($message);  


               }  

    }  
    

}
