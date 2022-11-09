<?php

namespace App\General;

use App\User;
use App\Models\SalesOrder;
use App\Models\UserDevices;
use App\Models\Otpgenerate;
use App\Models\OrderstatusUpdate;
use App\Models\OrderLogisticQueue;
use App\Models\WalletTransactions;
use App\Models\Basket;
use App\Models\Notification;
use App\Models\SupplierItemInventory;
use Carbon\Carbon;

//Only for API Call funcation
class ChangeOrderStatus9_7_2020
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
        $getsupplier_playerid=$userdevicesModel->where('user_id', $supplier_id)->first(); 
       if($getsupplier_playerid)
       {    
            $message['player_id'] = $getsupplier_playerid->player_id;
            $message['msg'] = $otpdata->otp .' is SECRET OTP for Order #'.$orderData->order_number.' at iTradezon on '.$todayDate.'. OTP valid for 5 mins. Please do not share this OTP';
            $message['order_uuid'] = $sales_id;
            $notify_msg = $message['msg'];
             //notification add on able   
             $notify = $usernotifyModel->create([ 'user_id' => $supplier_id, 'order_id' => $sales_id ,'notification' =>$notify_msg]);
             
             $message['notification_uuid'] = $notify->uuid;   
             //send notification
             sendNotification($message);
        } 
       
        $getsupplier_playerid=$userdevicesModel->where('user_id', $vendor_id)->first();  
        if($getsupplier_playerid){
            
            $message['player_id'] = $getsupplier_playerid->player_id;
            $notify_msg = $message['msg'];
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
                $message['msg'] = '#'.$orderData->order_number.' at iTradezon on '.$todayDate.' you want like to do order';
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

    
    public static function orderStatus(array $request)  //Notification send here
   {

        $orderModel = New SalesOrder;
        $orderstatusUpdateModel = New OrderstatusUpdate;
        $otpModel = New Otpgenerate;
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

            // $userid is current login id  only order placed then  we pass suppier id //code on website and api also have
             $get_playerid = $userdevicesModel->where('user_id', $user_id)->first();
            if($get_playerid){
                $message['player_id'] = $get_playerid->player_id;
                $message['msg'] = 'Order Status Changed for Order NO #'.$orderData->order_number.' is '.$status.' at iTradezon on '.$todayDate;
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
                $message['msg'] = 'Order Status Changed for Order NO #'.$orderData->order_number.' is '.$status.' at iTradezon on '.$todayDate;
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
                        $walletTransactionModel->create([
                        "credit_amount" =>  $orderData->final_total,
                        "debit_amount" =>0,
                        "user_id" => $orderData->user_id,
                        "remarks" => "BUY PRODUCT",
                        "status" => "APPROVED"
                        ]);

                     $walletTransactionModel->create([
                        "credit_amount" => 0,
                        "debit_amount" => $orderData->final_total,
                        "user_id" => $orderData->supplier_id,
                        "remarks" => "SELL PRODUCT",
                        "status" => "APPROVED" 
                        ]);

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
                            $message['msg'] = 'Order Status Changed for Order NO #'.$orderData->order_number.' is '.$status.' at iTradezon on '.$todayDate;
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
                            $walletTransactionModel->create([
                        "credit_amount" =>  $orderData->shipment_amount,
                        "debit_amount" =>0,
                        "user_id" => $orderData->logistic_id,
                        "remarks" => "DELIVERED PRODUCT",
                        "status" => "APPROVED"
                        ]);

                        $get_playerid = $userdevicesModel->where('user_id', $orderData->supplier_id)->first();
                        if($get_playerid){
                        $message['player_id'] = $get_playerid->player_id;
                        $message['msg'] = 'Order Status Changed for Order NO #'.$orderData->order_number.' is '.$status.' at iTradezon on '.$todayDate;
                        $message['order_uuid'] = $sales_id;
                        //sendNotification($message);
                        $notify_msg = $message['msg'];
                        $notify = $usernotifyModel->create([ 'user_id' => $orderData->supplier_id, 'order_id' => $sales_id ,'notification' =>$notify_msg]);
                        $message['notification_uuid'] = $notify->uuid;   
                             //send notification
                        sendNotification($message);

                        } 

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
                            "credit_amount" =>  $orderData->final_total,
                            "debit_amount" =>0,
                            "user_id" => $orderData->user_id,
                            "remarks" => "CANCELLED ORDER BY VENDER",
                            "status" => "APPROVED"
                            ]);

                         $walletTransactionModel->create([
                            "credit_amount" => 0,
                            "debit_amount" => $orderData->final_total,
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
                                $message['msg'] = 'Order Status Changed for Order NO #'.$orderData->order_number.' is '.$status.' at iTradezon on '.$todayDate;
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
                                $message['msg'] = 'Order Status Changed for Order NO #'.$orderData->order_number.' is '.$status.' at iTradezon on '.$todayDate;
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
       $otpModel = New Otpgenerate;
       $orderstatusUpdateModel = New OrderstatusUpdate;
        $usernotifyModel = New Notification;
       $sales_id = $request['order_uuid'];
       $sender_id    =  $request['sender_id'];
       $receiver_id  =  $request['receiver_id']; 
       $todayDate = Carbon::now()->format('Y-m-d');
        $orderData = $orderModel->where('uuid',$sales_id)->first();
        $otpdata = $otpModel->create([ 'sales_id' => $sales_id,'sender_id' => $sender_id,'receiver_id' =>$receiver_id ,'status' => 'pending']);


           //Notificatiom supplier
             $getsupplier_playerid=$userdevicesModel ->where('user_id', $sender_id)->first(); 
             if($getsupplier_playerid){
               $message['player_id'] = $getsupplier_playerid->player_id;
               $message['msg'] = $otpdata->otp .' is SECRET OTP for Order #'.$orderData->order_number.'at iTradezon on '.$todayDate.'. Please do not share this OTP';
                 $message['order_uuid'] = $sales_id;
                //sendNotification($message); 
                $notify_msg = $message['msg'];
                $notify = $usernotifyModel->create([ 'user_id' => $sender_id, 'order_id' => $sales_id ,'notification' => $notify_msg]);
                $message['notification_uuid'] = $notify->uuid;   
                                //send notification
                sendNotification($message);
                 
             } 
            
            
            //Notificatiom Driver
             $getsupplier_playerid=$userdevicesModel ->where('user_id', $receiver_id)->first(); 
              if($getsupplier_playerid){
                
                 $message['player_id'] = $getsupplier_playerid->player_id;
                $message['msg'] = $otpdata->otp .' is SECRET OTP for Order #'.$orderData->order_number.' at iTradezon on '.$todayDate.'. Please do not share this OTP';
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
