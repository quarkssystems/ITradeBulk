<?php

namespace App\General;

use App\User;
use App\Models\SalesOrder;
use App\Models\OfferDeals;
use App\Models\UserDevices;
use App\Models\Otpgenerate;
use App\Models\UserCompany;
use App\Models\OrderstatusUpdate;
use App\Models\OrderLogisticQueue;
use App\Models\WalletTransactions;
use App\Models\Basket;
use App\Models\Notification;
use App\Models\SupplierItemInventory;
use App\Models\LogisticDetails;
use Carbon\Carbon;
use App\Models\Setting;
use PDF;
use DB;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Mail;

//Only for API Call funcation
class ChangeOrderStatus
{
    /***************************************************************/
    /*order packed any delivery type pickup call from website and app 
    OTP SEND SUPPLIER and Vendor*/
    /***************************************************************/


    public static function orderPickupPacked(array $request) //pickup  //PACKED
    {
        $data = array();

        $orderModel = new SalesOrder;
        $orderstatusUpdateModel = new OrderstatusUpdate;
        $otpModel = new Otpgenerate;
        $userdevicesModel = new UserDevices;
        $usernotifyModel = new Notification;



        $sales_id = $request['order_uuid'];
        $supplier_id = $request['supplier_id'];
        $vendor_id = $request['vendor_id'];
        $status = 'PACKED';
        $todayDate = Carbon::now()->format('Y-m-d');

        $orderData = $orderModel->where('uuid', $sales_id)->first();

        $otpdata = $otpModel->create(['sales_id' => $sales_id, 'sender_id' => $supplier_id, 'receiver_id' => $vendor_id, 'status' => 'pending']);

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
        $getsupplier_playerid = $userdevicesModel->where('user_id', $vendor_id)->first();
        if ($getsupplier_playerid) {
            $message['player_id'] = $getsupplier_playerid->player_id;
            $message['msg'] = $otpdata->otp . ' is SECRET OTP for Order #' . $orderData->order_number . ' at ' . env("APP_NAME") . ' on ' . $todayDate . '. Please do not share this OTP.';
            // $message['msg'] = $otpdata->otp .' is SECRET OTP for Order #'.$orderData->order_number.' at iTradezon on '.$todayDate.'. Please do not share this OTP.';
            $message['order_uuid'] = $sales_id;
            $notify_msg = $message['msg'];
            //notification add on able   
            $notify = $usernotifyModel->create(['user_id' => $vendor_id, 'order_id' => $sales_id, 'notification' => $notify_msg, 'type' => Notification::TRADER]);

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
        $user = new User;
        $userCompany = new UserCompany;
        $userdevicesModel = new UserDevices;
        $orderModel = new SalesOrder;
        $orderstatusUpdateModel = new OrderstatusUpdate;
        $otpModel = new Otpgenerate;
        $orderLogisticQueueModel = new OrderLogisticQueue;
        $usernotifyModel = new Notification;


        $sales_id = $request['order_uuid'];
        $supplier_id = $request['supplier_id'];
        $vendor_id = $request['vendor_id'];
        $status = 'PACKED';

        $todayDate = Carbon::now()->format('Y-m-d');
        $orderData = $orderModel->where('uuid', $sales_id)->first();


        // dd($orderData);
        $orderWeight = 1;
        if (strpos($orderData->total_weight, "ton") !== false) {
            $orderWeight = trim(str_replace("ton", "", $orderData->total_weight));
        }

        //Write code for nearer driver 
        $suppiers = $user->where('uuid', $supplier_id)->first();
        $supplierCompanyData = $userCompany->where('owner_user_id', $orderData->supplier_id)->first();


        // DB::enableQueryLog(); // Enable query log
        $drivers = $user->select('uuid', 'latitude', 'longitude')->whereHas('documentUploaded', function ($q) use ($request) {
            $q->where('approved', '=', "YES");
        });

        $drivers = $drivers->whereHas('transport_capacity', function ($q) use ($orderWeight) {
            $q->where('transport_capacity', '>=', $orderWeight);
        });

        $drivers = $drivers->where('status', 'ACTIVE');
        $drivers = $drivers->where('role', 'DRIVER');
        $drivers = $drivers->has('userDevices');
        $drivers = $drivers->whereHas('transport_capacity', function ($q) use ($orderWeight) {
            $q->orderBy('transport_capacity', 'asc');
        });
        $drivers = $drivers->get();

        $nearer_driver = array();
        foreach ($drivers as $key => $driver) {

            $nearer_driver[$driver->uuid] = $user->getDrivingDistance($driver->latitude, $driver->longitude, $suppiers->latitude, $suppiers->longitude);
        }
        asort($nearer_driver);

        // dd($nearer_driver);
        $allDriver = [];
        foreach ($nearer_driver as $driver_id => $distance_value) {

            //temp distance is upslier with driver
            $orderLogisticQueueModel->create([
                'order_id' => $sales_id,
                'vendor_id' => $vendor_id,
                'supplier_id' => $supplier_id,
                'driver_id' => $driver_id,
                'distance' => 0,
                'status' => "ACCEPT"
            ]);

            $allDriver[] = $driver_id;
        }
        $driverData = LogisticDetails::whereIn('user_id', $allDriver)->orderBy('transport_capacity', 'asc')->first();

        // dd($allDriver,$driver_id);
        //Notificatiom accept reject to driver
        $getsupplier_playerid = $userdevicesModel->where('user_id', $driver_id)->first();
        if ($getsupplier_playerid) {

            $message['player_id'] = $getsupplier_playerid->player_id;
            $message['msg'] = '#' . $orderData->order_number . ' at ' . $supplierCompanyData->trading_name . ' on ' . $todayDate . ' Are you ready to deliver this order?';
            $message['order_uuid'] = $sales_id;
            $message['driver'] = $sales_id;

            $notify_msg = $message['msg'];
            $notify = $usernotifyModel->create(['user_id' => $driver_id, 'order_id' => $sales_id, 'notification' => $notify_msg, 'type' => Notification::DRIVER]);

            $message['notification_uuid'] = $notify->uuid;
            //send notification
            sendNotification($message);
        }
    }


    public static function walletTransaction($transaction_id)
    {
        $user = new User;
        $walletTransactionModel = new WalletTransactions;
        $userdevicesModel = new UserDevices;
        $usernotifyModel = new Notification;
        $todayDate = Carbon::now()->format('Y-m-d');

        $walletData = $walletTransactionModel->where('uuid', $transaction_id)->first();
        $userData = $user->where('uuid', $walletData->user_id)->first();

        $get_playerid = $userdevicesModel->where('user_id', $walletData->user_id)->first();

        if ($get_playerid) {
            $message['player_id'] = $get_playerid->player_id;

            if ($walletData->status == 'APPROVED') {
                $message['msg'] = 'Your wallet transaction has been approved for W-' . $walletData->id . ' at ' . env("APP_NAME") . ' on ' . $todayDate . '.';
            } else {
                $message['msg'] = 'Your wallet transaction has been cancelled for W-' . $walletData->id . ' at ' . env("APP_NAME") . ' on ' . $todayDate . '.';
            }
            // $notify_msg = $message['msg']; 
            // $notify = $usernotifyModel->create([ 'user_id' => $walletData->user_id ,'notification' =>$notify_msg]);
            // $message['notification_uuid'] = $notify->uuid;
            sendWalletNotification($message);
        }
    }


    public static function orderStatus(array $request)  //Notification send here
    {
        $user = new User;
        $orderModel = new SalesOrder;
        $orderstatusUpdateModel = new OrderstatusUpdate;
        $otpModel = new Otpgenerate;
        $userCompany = new UserCompany;
        $userdevicesModel = new UserDevices;
        $walletTransactionModel = new WalletTransactions;
        $basketModel = new Basket;
        $inventary = new SupplierItemInventory;
        $usernotifyModel = new Notification;
        $logistic = new LogisticDetails;
        $settings = new  Setting;
        $offerModel = new OfferDeals;
        $admin_email = env('MAIL_USERNAME');

        $supplier_itz_charge = $settings->get("itz_supplier_charge");

        $status = $request['order_status'];
        $sales_id = $request['order_uuid'];
        $user_id = $request['user_id'];

        //$delivery_type = $request['delivery_type'];

        $todayDate = Carbon::now()->format('Y-m-d');

        $order_uuid = '';
        // if($orderstatusUpdateModel->where('sales_id' , $sales_id)->where('order_status' , $status)->count() == 0)
        //     {

        $p_order_status = "";
        $prv_order_status = $orderstatusUpdateModel->where('sales_id', $sales_id)->pluck('order_status')->toArray();
        if ($prv_order_status) {
            $p_order_status = $prv_order_status[0];
        }

        // DB::enableQueryLog(); // Enable query log
        $order_uuid = $orderModel->where('uuid', $sales_id)->update(['order_status' => $status]);
        // dd(DB::getQueryLog()); // Show results of log
        if ($status == SalesOrder::ORDERPLACED) {
            $orderModel->where('uuid', $sales_id)->update(['placed_date' => Carbon::now()->toDateTimeString()]);
        }
        $orderstatusUpdateModel->create(['sales_id' => $sales_id, 'user_id' => $user_id, 'order_status' => $status]);
        // dd('test');
        $orderData = $orderModel->where('uuid', $sales_id)->first();
        $userCompanyData = $userCompany->where('owner_user_id', $orderData->user_id)->first();
        $supplierDetail = $user->where('uuid', $orderData->supplier_id)->first();
        $supplierCompanyData = $userCompany->where('owner_user_id', $orderData->supplier_id)->first();
        // print_r($userCompanyData->phone);die();
        // $userid is current login id  only order placed then  we pass suppier id //code on website and api also have
        $get_playerid = $userdevicesModel->where('user_id', $user_id)->first();
        if ($get_playerid) {

            $message['player_id'] = $get_playerid->player_id;
            // $message['msg'] = 'Order status Changed for Order no #'.$orderData->order_number.' is '.$status.' at iTradezon on '.$todayDate;
            if ($status == SalesOrder::ORDERPLACED) {
                // if($status == 'PLACED') {
                $message['msg'] = 'New Order from ' . env("APP_NAME") . ' with Order no. #' . $orderData->order_number . ' on ' . $todayDate . '.';
            } elseif ($status == 'PACKED') {
                $message['msg'] = 'Order status changed: Order no. #' . $orderData->order_number . ' is ' . $status . ' at ' . $supplierCompanyData->trading_name . ' on ' . $todayDate . '.';
            } else {
                $message['msg'] = 'Order status changed for Order no. #' . $orderData->order_number . ' is ' . $status . ' at ' . env("APP_NAME") . ' on ' . $todayDate . '.';
            }

            $message['order_uuid'] = $sales_id;
            $notify_msg = $message['msg'];
            $notify = $usernotifyModel->create(['user_id' => $user_id, 'order_id' => $sales_id, 'notification' => $notify_msg, 'type' => Notification::TRADER]);

            $message['notification_uuid'] = $notify->uuid;
            //send notification
            sendNotification($message);

            // $userData = User::where('uuid',$user_id)->first();
            // sendOrderStatusEmail($notify_msg,$userData->email,$status);

        }

        //.user_id is vendor id   
        $get_playerid = $userdevicesModel->where('user_id', $orderData->user_id)->first();


        if ($get_playerid) {
            $message['player_id'] = $get_playerid->player_id;
            // $message['msg'] = 'Order status Changed for Order no #'.$orderData->order_number.' is '.$status.' at iTradezon on '.$todayDate;
            if ($status == SalesOrder::ORDERPLACED) {
                // if($status == 'PLACED') {
                $message['msg'] = 'Your order has been successfully placed at ' . env("APP_NAME") . ' with Order no. #' . $orderData->order_number . ' on ' . $todayDate . '.';
            } elseif ($status == 'PACKED') {
                $message['msg'] = 'Order status changed: Order no. #' . $orderData->order_number . ' is ' . $status . ' at ' . $supplierCompanyData->trading_name . ' on ' . $todayDate . '.';
            } else {
                $message['msg'] = 'Order status changed for Order no. #' . $orderData->order_number . ' is ' . $status . ' at ' . env("APP_NAME") . ' on ' . $todayDate . '.';
            }

            $message['order_uuid'] = $sales_id;
            $notify_msg = $message['msg'];
            $notify = $usernotifyModel->create(['user_id' => $orderData->user_id, 'order_id' => $sales_id, 'notification' => $notify_msg, 'type' => Notification::SUPPLIER]);
            $message['notification_uuid'] = $notify->uuid;
            //send notification
            sendNotification($message);
        }

        $req_data = array();
        $req_data['order_uuid'] = $orderData->uuid;
        $req_data['supplier_id'] = $orderData->supplier_id;
        $req_data['vendor_id'] = $orderData->user_id;

        if ($orderData->delivery_type == 'pickup') {
            switch ($status) {
                case 'PACKED':
                    ChangeOrderStatus::orderPickupPacked($req_data);
                    $orderModel->where('uuid', $sales_id)->update(['packed_date' => Carbon::now()->toDateTimeString()]);

                    # code...
                    break;
                case 'CANCELLED':

                    $orderModel->where('uuid', $sales_id)->update(['cancelled_date' => Carbon::now()->toDateTimeString()]);

                    if ($p_order_status == SalesOrder::ORDERPLACED) {
                        // if($p_order_status == "PLACED") { 
                        $order_uuid = $orderModel->where('uuid', $sales_id)->update(['payment_status' => 'CANCELLED']);

                        $walletTransactionModel->where('order_id', $sales_id)->update(['status' => 'CANCELED']);

                        $basket = $basketModel->where("order_id", $sales_id)->first();
                        if ($basket) {
                            $products = $basket->products;
                            foreach ($products as $pro) {
                                $inventary->where('product_id', $pro->product_id)->where('user_id', $orderData->supplier_id)->increment('single', $pro->single_qty);
                            }
                        }
                        //send notification when order cancal (curr user is vender so need to right code)
                        $get_playerid = $userdevicesModel->where('user_id', $orderData->supplier_id)->first();
                        if ($get_playerid) {
                            $message['player_id'] = $get_playerid->player_id;
                            // $message['msg'] = 'Order status Changed for Order no #'.$orderData->order_number.' is '.$status.' at iTradezon on '.$todayDate;

                            if ($status == SalesOrder::ORDERPLACED) {
                                // if($status == 'PLACED') {
                                $message['msg'] = 'Your order has been successfully placed at ' . env("APP_NAME") . ' with Order no. #' . $orderData->order_number . ' on ' . $todayDate . '.';
                            } elseif ($status == 'PACKED') {
                                $message['msg'] = 'Order status changed: Order no. #' . $orderData->order_number . ' is ' . $status . ' at ' . $supplierCompanyData->trading_name . ' on ' . $todayDate . '.';
                            } else {
                                $message['msg'] = 'Order status changed for Order no. #' . $orderData->order_number . ' is ' . $status . ' at ' . env("APP_NAME") . ' on ' . $todayDate . '.';
                            }

                            $message['order_uuid'] = $sales_id;
                            //sendNotification($message);
                            $notify_msg = $message['msg'];
                            $notify = $usernotifyModel->create(['user_id' => $orderData->user_id, 'order_id' => $sales_id, 'notification' => $notify_msg, 'type' => Notification::TRADER]);
                            $message['notification_uuid'] = $notify->uuid;
                            //send notification
                            sendNotification($message);
                        }
                    }
                    break;
                case 'DELIVERED':
                    $orderModel->where('uuid', $sales_id)->update(['delivered_date' => Carbon::now()->toDateTimeString()]);

                    $order_uuid = $orderModel->where('uuid', $sales_id)->update(['payment_status' => 'COMPLETED']);
                    $walletTransactionModel->where('order_id', $sales_id)->update(['status' => 'APPROVED']);

                    $charge_itz = $settings->get("itz_transporter_charge");
                    $admin_user = $user->where('role', 'ADMIN')->first();

                    $supplier_wallet = $walletTransactionModel->where('order_id', $sales_id)->where('user_id', $orderData->supplier_id)->first();

                    $admin_credit_amt = ($orderData->shipment_amount * $charge_itz) / 100;
                    $walletTransactionModel->create([
                        "credit_amount" => $supplier_wallet->credit_amount,
                        "debit_amount" => 0,
                        "user_id" => $admin_user->uuid,
                        "remarks" => "BUY PRODUCT",
                        "order_id" => $sales_id,
                        "status" => "APPROVED",
                        "admin_charge" => 0

                    ]);

                    //supplier  wallet notification 
                    $get_playerid = $userdevicesModel->where('user_id', $orderData->supplier_id)->first();
                    if ($get_playerid) {
                        $message['player_id'] = $get_playerid->player_id;
                        $message['msg'] = 'Your wallet credited with R' . number_format($supplier_wallet->credit_amount, 2) . ', For Order no. #' . $orderData->order_number . ' at ' . env("APP_NAME") . ' on ' . $todayDate . '.';
                        $message['order_uuid'] = $sales_id;
                        //sendNotification($message);
                        $notify_msg = $message['msg'];
                        $notify = $usernotifyModel->create(['user_id' => $orderData->supplier_id, 'order_id' => $sales_id, 'notification' => $notify_msg, 'type' => Notification::SUPPLIER]);
                        $message['notification_uuid'] = $notify->uuid;
                        //send notification
                        sendNotification($message);
                    }
                    //Admin charge  wallet notification       
                    $admin_user = $user->where('role', 'ADMIN')->first();
                    $admin_wallet = $walletTransactionModel->where('order_id', $sales_id)->where('user_id', $admin_user->uuid)->first();
                    $get_playerid_admin = $userdevicesModel->where('user_id', $admin_user->uuid)->first();
                    if ($get_playerid_admin) {
                        $message['player_id'] = $get_playerid_admin->player_id;
                        $message['msg'] = 'Your wallet credited with R' . number_format($admin_wallet->credit_amount, 2) . ', For Order no. #' . $orderData->order_number . ' at ' . env("APP_NAME") . ' on ' . $todayDate . '.';
                        $message['order_uuid'] = $sales_id;
                        //sendNotification($message);
                        $notify_msg = $message['msg'];
                        $notify = $usernotifyModel->create(['user_id' => $admin_user->uuid, 'order_id' => $sales_id, 'notification' => $notify_msg, 'type' => Notification::ADMIN]);
                        $message['notification_uuid'] = $notify->uuid;
                        //send notification
                        sendNotification($message);
                    }


                    //vendor  wallet notification for debit 
                    $get_playerid_vendor = $userdevicesModel->where('user_id', $orderData->user_id)->first();
                    $vendor_wallet = $walletTransactionModel->where('order_id', $sales_id)->where('user_id', $orderData->user_id)->sum('debit_amount');
                    if ($get_playerid_vendor) {
                        $message['player_id'] = $get_playerid_vendor->player_id;
                        $message['msg'] = 'Your wallet debited with R' . number_format($vendor_wallet, 2) . ' For Order no. #' . $orderData->order_number . '  at ' . env("APP_NAME") . ' on ' . $todayDate . '.';
                        $message['order_uuid'] = $sales_id;
                        //sendNotification($message);
                        $notify_msg = $message['msg'];
                        $notify = $usernotifyModel->create(['user_id' => $orderData->user_id, 'order_id' => $sales_id, 'notification' => $notify_msg]);
                        $message['notification_uuid'] = $notify->uuid;
                        //send notification
                        sendNotification($message);
                    }




                    $phone = '+88 0123 4567 890, +88 0123 4567 999';
                    $facebook_url = 'https://www.facebook.com/';
                    $instagram_url = 'https://www.instagram.com/';
                    $twitter_url = 'https://www.twitter.com/';
                    $pinterest_url = 'https://www.pinterest.com/';

                    $orderDetail = $orderModel->where('uuid', $sales_id)->first();

                    LogisticDetails::where('uuid', $orderDetail->logistic_details_id)->update(['is_available' => '1']);

                    $userDetail = $user->where('uuid', $orderDetail->user_id)->first();
                    $userEmail = $userDetail->email;

                    $cartAmount = $orderDetail->cart_amount - ($orderDetail->cart_amount * $supplier_itz_charge) / 100;
                    $supplierAdminCharge = ($orderDetail->cart_amount * $supplier_itz_charge) / 100;

                    $basketDetail = $basketModel->where('order_id', $sales_id)->first();
                    $basketProductIds = $basketDetail->products()->pluck('product_id')->toArray();
                    $basketProducts = $basketDetail->products;
                    $supplierLoopData = [];
                    $supplierLoopData["products"] = [];
                    $totalWeight = 0;
                    $totalProducts = 0;
                    $totalAvailableProducts = 0;
                    $total = 0;
                    $productOffer = '';
                    foreach ($basketProducts as $proIndex => $basketProduct) {
                        $totalProducts++;
                        $rowTotal = 0;
                        if ($basketProduct->product()->exists()) {
                            if (SupplierItemInventory::where('product_id', $basketProduct->product_id)->where('user_id', $orderDetail->supplier_id)->count() > 0) {
                                $supplierLatestRate = SupplierItemInventory::where('product_id', $basketProduct->product_id)->where('user_id', $orderDetail->supplier_id)->orderBy('id', 'DESC')->first();
                                $singlePrice = $supplierLatestRate->single_price;
                                $productSinglePrice = $supplierLatestRate->single_price;
                                if ($offerModel->where('user_id', $orderDetail->supplier_id)->where('products_id', $basketProduct->product_id)->whereDate('end_date', '>=', $todayDate)->count() > 0) {
                                    $productOffer = $offerModel->where('user_id', $orderDetail->supplier_id)->where('products_id', $basketProduct->product_id)->whereDate('end_date', '>=', $todayDate)->orderBy('id', 'DESC')->first();
                                    if ($productOffer->offer_type == 'RENT') {
                                        $singlePrice = $singlePrice - ($productOffer->offer_value);
                                    } else {
                                        $singlePrice = $singlePrice - (($singlePrice * ($productOffer->offer_value)) / 100);
                                    }
                                }
                                $itemWeight = 0;
                                $totalAvailableProducts++;
                                $productId = $supplierLatestRate->product->uuid;
                                $productName = $supplierLatestRate->product->name;
                                $supplierProductLoopData["product_id"] = $productId;
                                $supplierProductLoopData["product_name"] = $productName;
                                if ($basketProduct->single_qty > 0) {
                                    $supplierProductLoopData["qty"] = $basketProduct->single_qty;
                                    $supplierProductLoopData["productSinglePrice"] = $productSinglePrice;
                                    $supplierProductLoopData["price"] = $singlePrice;
                                    $supplierProductLoopData["totalprice"] = ($basketProduct->single_qty * $singlePrice);
                                    $itemWeight += $basketProduct->product->getCalculatedWeight("single", $basketProduct->single_qty);
                                    $rowTotal += ($basketProduct->single_qty * $singlePrice);
                                }
                            }
                            $totalWeight += $itemWeight;
                            $total += $rowTotal;
                            $supplierProductLoopData["row_total"] = $rowTotal;
                            $supplierProductLoopData["total_weight"] = $totalWeight;
                            $supplierLoopData["products"][$proIndex] = $supplierProductLoopData;
                            $supplierLoopData["total_available_products"] = $totalAvailableProducts;
                        }
                    }

                    //trader invoice

                    // comment for backup
                    // $pdf = PDF::loadView('frontend.checkout.traderInvoice',compact('userDetail','userCompanyData','orderDetail','supplierLoopData','supplierCompanyData','supplierDetail','supplier_wallet','supplierAdminCharge','productOffer'))->setPaper('a4');

                    // $email = EmailTemplate::where('name','=','trader_order_invoice')->first();

                    // if(isset($email)){
                    //     $email->description = str_replace('[CUSTOMER_NAME]', $userDetail->first_name.' '.$userDetail->last_name , $email->description);
                    //     $email->description = str_replace('[INVOICE_NO]', $orderDetail->order_number, $email->description);
                    //     $email->description = str_replace('[EMAIL]', env('SUPPORT'), $email->description);
                    //     $email->description = str_replace('[SITE_NAME]', env('WEBSITE'), $email->description);
                    //     $email->description = str_replace('[PHONE]', $phone, $email->description);
                    //     $email->description = str_replace('[FACEBOOK_URL]', $facebook_url, $email->description);
                    //     $email->description = str_replace('[TWITTER_URL]', $twitter_url, $email->description);
                    //     $email->description = str_replace('[INSTAGRAM_URL]', $instagram_url, $email->description);
                    //     $email->description = str_replace('[PINTEREST_URL]', $pinterest_url, $email->description);
                    //     $email->description = str_replace('[LOGO]', asset('assets/images/logo.png'), $email->description);
                    // } 

                    // $emailContent = $email->description;

                    // Mail::send([], [], function ($message) use ($userEmail, $pdf, $emailContent, $admin_email) {
                    //    $message->to($userEmail)
                    //     ->subject('Trader Order Invoice')
                    //     ->setBody($emailContent, 'text/html'); // for HTML rich messages
                    //     $message->attachData($pdf->output(),'customer.pdf');
                    //     $message->from($admin_email,env("APP_NAME"));
                    // });

                    //trader invoice complete
                    // 




                    # code...
                    break;

                default:
                    # code...
                    break;
            }
        }

        if ($orderData->delivery_type != 'pickup') {


            switch ($status) {
                case 'PACKED':
                    ChangeOrderStatus::orderDeliveryPacked($req_data);
                    $orderModel->where('uuid', $sales_id)->update(['packed_date' => Carbon::now()->toDateTimeString()]);

                    break;
                case 'DELIVERED':
                    $orderModel->where('uuid', $sales_id)->update(['delivered_date' => Carbon::now()->toDateTimeString()]);



                    $charge_itz = $settings->get("itz_transporter_charge");
                    $admin_user = $user->where('role', 'ADMIN')->first();

                    $order_uuid = $orderModel->where('uuid', $sales_id)->update(['payment_status' => 'COMPLETED']);
                    $walletTransactionModel->where('order_id', $sales_id)->update(['status' => 'APPROVED']);


                    $admin_credit_amt = ($orderData->shipment_amount * $charge_itz) / 100;
                    $walletTransactionModel->create([
                        "credit_amount" => $admin_credit_amt,
                        "debit_amount" => 0,
                        "user_id" => $admin_user->uuid,
                        "remarks" => "ADMIN CHARGE FOR SHIPMENT",
                        "order_id" => $sales_id,
                        "status" => "APPROVED",
                        "admin_charge" => $charge_itz

                    ]);

                    $get_playerid_admin = $userdevicesModel->where('user_id', $admin_user->uuid)->first();
                    if ($get_playerid_admin) {
                        $message['player_id'] = $get_playerid_admin->player_id;
                        $message['msg'] = 'Your wallet credited with R' . number_format($admin_credit_amt, 2) . ' , SHIPMENT charge For Order no. #' . $orderData->order_number . ' at ' . env("APP_NAME") . ' on ' . $todayDate . '.';
                        $message['order_uuid'] = $sales_id;
                        //sendNotification($message);
                        $notify_msg = $message['msg'];
                        $notify = $usernotifyModel->create(['user_id' => $admin_user->uuid, 'order_id' => $sales_id, 'notification' => $notify_msg, 'type' => Notification::ADMIN]);
                        $message['notification_uuid'] = $notify->uuid;
                        //send notification
                        sendNotification($message);
                    }

                    $driver_credit_amt =  $orderData->shipment_amount - ($orderData->shipment_amount * $charge_itz) / 100;
                    $walletTransactionModel->create([
                        "credit_amount" => $driver_credit_amt,
                        "debit_amount" => 0,
                        "user_id" => $orderData->logistic_id,
                        "remarks" => "DELIVERED PRODUCT",
                        "order_id" => $sales_id,
                        "status" => "APPROVED",
                        "admin_charge" => $charge_itz
                    ]);


                    $get_playerid_logistic = $userdevicesModel->where('user_id',  $orderData->logistic_id)->first();
                    if ($get_playerid_logistic) {
                        $message['player_id'] = $get_playerid_logistic->player_id;
                        $message['msg'] = 'Your wallet credited with R' . number_format($driver_credit_amt, 2) . ' , For Order no. #' . $orderData->order_number . ' at ' . env("APP_NAME") . ' on ' . $todayDate . '.';
                        $message['order_uuid'] = $sales_id;
                        //sendNotification($message);
                        $notify_msg = $message['msg'];
                        $notify = $usernotifyModel->create(['user_id' =>  $orderData->logistic_id, 'order_id' => $sales_id, 'notification' => $notify_msg, 'type' => Notification::DRIVER]);
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
                    $supplier_wallet = $walletTransactionModel->where('order_id', $sales_id)->where('user_id', $orderData->supplier_id)->first();
                    $get_playerid = $userdevicesModel->where('user_id', $orderData->supplier_id)->first();
                    if ($get_playerid) {
                        $message['player_id'] = $get_playerid->player_id;
                        $message['msg'] = 'Your wallet credited with R' . number_format($supplier_wallet->credit_amount, 2) . ', For Order no. #' . $orderData->order_number . ' at ' . env("APP_NAME") . ' on ' . $todayDate . '.';
                        $message['order_uuid'] = $sales_id;
                        //sendNotification($message);
                        $notify_msg = $message['msg'];
                        $notify = $usernotifyModel->create(['user_id' => $orderData->supplier_id, 'order_id' => $sales_id, 'notification' => $notify_msg, 'type' => Notification::SUPPLIER]);
                        $message['notification_uuid'] = $notify->uuid;
                        //send notification
                        sendNotification($message);
                    }
                    //Admin charge  wallet notification       
                    $admin_user = $user->where('role', 'ADMIN')->first();
                    $admin_wallet = $walletTransactionModel->where('order_id', $sales_id)->where('user_id', $admin_user->uuid)->first();
                    $get_playerid_admin = $userdevicesModel->where('user_id', $admin_user->uuid)->first();
                    if ($get_playerid_admin) {
                        $message['player_id'] = $get_playerid_admin->player_id;
                        $message['msg'] = 'Your wallet credited with R' . number_format($admin_wallet->credit_amount, 2) . ', For Order no. #' . $orderData->order_number . ' at ' . env("APP_NAME") . ' on ' . $todayDate . '.';
                        $message['order_uuid'] = $sales_id;
                        //sendNotification($message);
                        $notify_msg = $message['msg'];
                        $notify = $usernotifyModel->create(['user_id' => $admin_user->uuid, 'order_id' => $sales_id, 'notification' => $notify_msg, 'type' => Notification::ADMIN]);
                        $message['notification_uuid'] = $notify->uuid;
                        //send notification
                        sendNotification($message);
                    }


                    //vendor  wallet notification for debit 
                    $get_playerid_vendor = $userdevicesModel->where('user_id', $orderData->user_id)->first();
                    $vendor_wallet = $walletTransactionModel->where('order_id', $sales_id)->where('user_id', $orderData->user_id)->sum('debit_amount');
                    if ($get_playerid_vendor) {
                        $message['player_id'] = $get_playerid_vendor->player_id;
                        $message['msg'] = 'Your wallet debited with R' . number_format($vendor_wallet, 2) . ', For Order no. #' . $orderData->order_number . '  at ' . env("APP_NAME") . ' on ' . $todayDate . '.';
                        $message['order_uuid'] = $sales_id;
                        //sendNotification($message);
                        $notify_msg = $message['msg'];
                        $notify = $usernotifyModel->create(['user_id' => $orderData->user_id, 'order_id' => $sales_id, 'notification' => $notify_msg, 'type' => Notification::TRADER]);
                        $message['notification_uuid'] = $notify->uuid;
                        //send notification
                        sendNotification($message);
                    }




                    $phone = '+88 0123 4567 890, +88 0123 4567 999';
                    $facebook_url = 'https://www.facebook.com/';
                    $instagram_url = 'https://www.instagram.com/';
                    $twitter_url = 'https://www.twitter.com/';
                    $pinterest_url = 'https://www.pinterest.com/';

                    $orderDetail = $orderModel->where('uuid', $sales_id)->first();
                    LogisticDetails::where('uuid', $orderDetail->logistic_details_id)->update(['is_available' => '1']);


                    $userDetail = $user->where('uuid', $orderDetail->user_id)->first();
                    $transporterDetail = $user->where('uuid', $orderDetail->logistic_id)->first();
                    if ($transporterDetail != null) {
                        $logisticDetail = $logistic->where('user_id', $transporterDetail->uuid)->first();
                    }
                    $userEmail = $userDetail->email;
                    if ($transporterDetail != null) {
                        $transporterEmail = $transporterDetail->email;
                    }
                    $invoiceNO = $orderDetail->order_number;
                    $shipmentAmount = $orderDetail->shipment_amount - ($orderDetail->shipment_amount * $charge_itz) / 100;
                    $adminCharge = ($orderDetail->shipment_amount * $charge_itz) / 100;
                    $cartAmount = $orderDetail->cart_amount - ($orderDetail->cart_amount * $supplier_itz_charge) / 100;
                    $supplierAdminCharge = ($orderDetail->cart_amount * $supplier_itz_charge) / 100;


                    $basketDetail = $basketModel->where('order_id', $sales_id)->first();
                    $basketProductIds = $basketDetail->products()->pluck('product_id')->toArray();
                    $basketProducts = $basketDetail->products;
                    $supplierLoopData = [];
                    $supplierLoopData["products"] = [];
                    $totalWeight = 0;
                    $totalProducts = 0;
                    $totalAvailableProducts = 0;
                    $total = 0;
                    $productOffer = '';
                    foreach ($basketProducts as $proIndex => $basketProduct) {
                        $totalProducts++;
                        $rowTotal = 0;
                        if ($basketProduct->product()->exists()) {
                            if (SupplierItemInventory::where('product_id', $basketProduct->product_id)->where('user_id', $orderDetail->supplier_id)->count() > 0) {
                                $supplierLatestRate = SupplierItemInventory::where('product_id', $basketProduct->product_id)->where('user_id', $orderDetail->supplier_id)->orderBy('id', 'DESC')->first();
                                $singlePrice = $supplierLatestRate->single_price;
                                $productSinglePrice = $supplierLatestRate->single_price;
                                if ($offerModel->where('user_id', $orderDetail->supplier_id)->where('products_id', $basketProduct->product_id)->whereDate('end_date', '>=', $todayDate)->count() > 0) {
                                    $productOffer = $offerModel->where('user_id', $orderDetail->supplier_id)->where('products_id', $basketProduct->product_id)->whereDate('end_date', '>=', $todayDate)->orderBy('id', 'DESC')->first();
                                    if ($productOffer->offer_type == 'RENT') {
                                        $singlePrice = $singlePrice - ($productOffer->offer_value);
                                    } else {
                                        $singlePrice = $singlePrice - (($singlePrice * ($productOffer->offer_value)) / 100);
                                    }
                                }
                                $itemWeight = 0;
                                $totalAvailableProducts++;
                                $productId = $supplierLatestRate->product->uuid;
                                $productName = $supplierLatestRate->product->name;
                                $supplierProductLoopData["product_id"] = $productId;
                                $supplierProductLoopData["product_name"] = $productName;
                                if ($basketProduct->single_qty > 0) {
                                    $supplierProductLoopData["qty"] = $basketProduct->single_qty;
                                    $supplierProductLoopData["productSinglePrice"] = $productSinglePrice;
                                    $supplierProductLoopData["price"] = $singlePrice;
                                    $supplierProductLoopData["totalprice"] = ($basketProduct->single_qty * $singlePrice);
                                    $itemWeight += $basketProduct->product->getCalculatedWeight("single", $basketProduct->single_qty);
                                    $rowTotal += ($basketProduct->single_qty * $singlePrice);
                                }
                            }
                            $totalWeight += $itemWeight;
                            $total += $rowTotal;
                            $supplierProductLoopData["row_total"] = $rowTotal;
                            $supplierProductLoopData["total_weight"] = $totalWeight;
                            $supplierLoopData["products"][$proIndex] = $supplierProductLoopData;
                            $supplierLoopData["total_available_products"] = $totalAvailableProducts;
                        }
                    }
                    // $walletBalance = auth()->user()->wallet_balance;

                    // print_r($supplierLoopData);die();



                    //trader invoice

                    // comment for backup
                    // $pdf = PDF::loadView('frontend.checkout.traderInvoice',compact('userDetail','userCompanyData','orderDetail','supplierLoopData','supplierCompanyData','supplierDetail','supplierAdminCharge','productOffer'))->setPaper('a4');

                    // if($transporterDetail != null){
                    //     $pdf1 = PDF::loadView('frontend.checkout.transporterInvoice',compact('userDetail','userCompanyData','orderDetail', 'transporterDetail', 'logisticDetail', 'shipmentAmount', 'adminCharge'))->setPaper('a4');
                    // }
                    // $email = EmailTemplate::where('name','=','trader_order_invoice')->first();

                    // if(isset($email)){
                    //     $email->description = str_replace('[CUSTOMER_NAME]', $userDetail->first_name.' '.$userDetail->last_name , $email->description);
                    //     $email->description = str_replace('[INVOICE_NO]', $orderDetail->order_number, $email->description);
                    //     $email->description = str_replace('[EMAIL]', env('SUPPORT'), $email->description);
                    //     $email->description = str_replace('[SITE_NAME]', env('WEBSITE'), $email->description);
                    //     $email->description = str_replace('[PHONE]', $phone, $email->description);
                    //     $email->description = str_replace('[FACEBOOK_URL]', $facebook_url, $email->description);
                    //     $email->description = str_replace('[TWITTER_URL]', $twitter_url, $email->description);
                    //     $email->description = str_replace('[INSTAGRAM_URL]', $instagram_url, $email->description);
                    //     $email->description = str_replace('[PINTEREST_URL]', $pinterest_url, $email->description);
                    //     $email->description = str_replace('[LOGO]', asset('assets/images/logo.png'), $email->description);
                    // } 

                    // $emailContent = $email->description;

                    // if($transporterDetail != null){
                    //     Mail::send([], [], function ($message) use ($userEmail, $pdf, $pdf1, $emailContent, $admin_email) {
                    //         $message->to($userEmail)
                    //          ->subject('Trader Order Invoice')
                    //          ->setBody($emailContent, 'text/html'); // for HTML rich messages
                    //          $message->attachData($pdf->output(),'supplier.pdf');
                    //          $message->attachData($pdf1->output(),'transporter.pdf');
                    //          $message->from($admin_email,env("APP_NAME"));
                    //      });
                    // } else {
                    //     Mail::send([], [], function ($message) use ($userEmail, $pdf, $emailContent, $admin_email) {
                    //         $message->to($userEmail)
                    //          ->subject('Trader Order Invoice')
                    //          ->setBody($emailContent, 'text/html'); // for HTML rich messages
                    //          $message->attachData($pdf->output(),'supplier.pdf');
                    //         //  $message->attachData($pdf1->output(),'transporter.pdf');
                    //          $message->from($admin_email,env("APP_NAME"));
                    //      });
                    // }


                    //trader invoice complete
                    // 

                    //transporter invoice

                    // $pdf = PDF::loadView('frontend.checkout.transporterInvoice',compact('orderDetail', 'transporterDetail', 'logisticDetail', 'shipmentAmount', 'adminCharge'))->setPaper('a4');

                    // $email = EmailTemplate::where('name','=','transporter_order_invoice')->first();

                    // if(isset($email)){
                    //     $email->description = str_replace('[CUSTOMER_NAME]', $transporterDetail->first_name.' '.$transporterDetail->last_name , $email->description);
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

                    // Mail::send([], [], function ($message) use ($transporterEmail, $pdf, $emailContent, $admin_email) {
                    //    $message->to($transporterEmail)
                    //     ->subject('Transporter Order Receipt')
                    //     ->setBody($emailContent, 'text/html'); // for HTML rich messages
                    //     $message->attachData($pdf->output(),'customer.pdf');
                    //     $message->from($admin_email,'Itradezon');
                    // });

                    // return redirect()->route('supplier.dashboard')->with(['status' => 'success', 'message' => "mail sent successfully"]);
                    ////transporter invoice complete






                    break;
                case 'DISPATCHED':
                    //Otp genrated and add  Driver and Vender
                    $otpdata = $otpModel->create(['sales_id' => $sales_id, 'sender_id' => $orderData->logistic_id, 'receiver_id' => $orderData->user_id, 'status' => 'pending']);

                    $req['order_uuid'] = $sales_id;
                    $req['sender_id'] = $orderData->logistic_id;
                    $req['receiver_id'] = $orderData->user_id;
                    $req['type'] = Notification::TRADER;

                    ChangeOrderStatus::otpsend($req); //supplier and driver otp
                    $orderModel->where('uuid', $sales_id)->update(['dispatched_date' => Carbon::now()->toDateTimeString()]);

                    # code...
                    break;
                case 'CANCELLED':

                    $orderModel->where('uuid', $sales_id)->update(['cancelled_date' => Carbon::now()->toDateTimeString()]);

                    if ($p_order_status == SalesOrder::ORDERPLACED) {
                        // if($p_order_status == "PLACED") { 

                        $order_uuid = $orderModel->where('uuid', $sales_id)->update(['payment_status' => 'CANCELLED']);
                        $walletTransactionModel->create([
                            "credit_amount" => $orderData->final_total,
                            "debit_amount" => 0,
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
                        if ($basket) {
                            $products = $basket->products;
                            foreach ($products as $pro) {
                                $inventary->where('product_id', $pro->product_id)->where('user_id', $orderData->supplier_id)->increment('single', $pro->single_qty);
                            }
                        }
                        //User mobile current is vender so notificaion for supplier and driver
                        //send notification when order cancal 
                        $get_playerid = $userdevicesModel->where('user_id', $orderData->supplier_id)->first();
                        if ($get_playerid) {
                            $message['player_id'] = $get_playerid->player_id;
                            // $message['msg'] = 'Order status Changed for Order no #'.$orderData->order_number.' is '.$status.' at iTradezon on '.$todayDate;

                            if ($status == SalesOrder::ORDERPLACED) {
                                // if($status == 'PLACED') {
                                $message['msg'] = 'Your order has been successfully placed at ' . env("APP_NAME") . ' with Order no. #' . $orderData->order_number . ' on ' . $todayDate . '.';
                            } elseif ($status == 'PACKED') {
                                $message['msg'] = 'Order status changed: Order no. #' . $orderData->order_number . ' is ' . $status . ' at ' . $supplierCompanyData->trading_name . ' on ' . $todayDate . '.';
                            } else {
                                $message['msg'] = 'Order status changed for Order no. #' . $orderData->order_number . ' is ' . $status . ' at ' . env("APP_NAME") . ' on ' . $todayDate . '.';
                            }

                            $message['order_uuid'] = $sales_id;
                            //sendNotification($message);
                            $notify_msg = $message['msg'];
                            $notify = $usernotifyModel->create(['user_id' => $orderData->supplier_id, 'order_id' => $sales_id, 'notification' => $notify_msg, 'type' => Notification::SUPPLIER]);

                            $message['notification_uuid'] = $notify->uuid;
                            //send notification
                            sendNotification($message);
                        }
                        $get_playerid = $userdevicesModel->where('user_id', $orderData->logistic_id)->first();
                        if ($get_playerid) {
                            $message['player_id'] = $get_playerid->player_id;
                            // $message['msg'] = 'Order status Changed for Order no #'.$orderData->order_number.' is '.$status.' at iTradezon on '.$todayDate;

                            if ($status == SalesOrder::ORDERPLACED) {
                                // if($status == 'PLACED') {
                                $message['msg'] = 'Your order has been successfully placed at ' . env("APP_NAME") . ' with Order no. #' . $orderData->order_number . ' on ' . $todayDate . '.';
                            } elseif ($status == 'PACKED') {
                                $message['msg'] = 'Order status changed: Order no. #' . $orderData->order_number . ' is ' . $status . ' at ' . $supplierCompanyData->trading_name . ' on ' . $todayDate . '.';
                            } else {
                                $message['msg'] = 'Order status changed for Order no. #' . $orderData->order_number . ' is ' . $status . ' at ' . env("APP_NAME") . ' on ' . $todayDate . '.';
                            }

                            $message['order_uuid'] = $sales_id;
                            //sendNotification($message);
                            $notify_msg = $message['msg'];
                            $notify = $usernotifyModel->create(['user_id' => $orderData->logistic_id, 'order_id' => $sales_id, 'notification' => $notify_msg, 'type' => Notification::DRIVER]);

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

        // }

    }


    /***************************************************************/
    /*order packed of delivery type opt genate code */
    /***************************************************************/
    public static function otpsend(array $request) //Supplier and driver
    {

        // dd('test');

        $orderModel = new SalesOrder;
        $userdevicesModel = new UserDevices;
        $userCompany = new UserCompany;
        $otpModel = new Otpgenerate;
        $orderstatusUpdateModel = new OrderstatusUpdate;
        $usernotifyModel = new Notification;
        $sales_id = $request['order_uuid'];
        $sender_id    =  $request['sender_id'];
        $receiver_id  =  $request['receiver_id'];
        $type  =  $request['type'];

        $todayDate = Carbon::now()->format('Y-m-d');
        $orderData = $orderModel->where('uuid', $sales_id)->first();
        $supplierCompanyData = $userCompany->where('owner_user_id', $orderData->supplier_id)->first();
        $otpdata = $otpModel->create(['sales_id' => $sales_id, 'sender_id' => $sender_id, 'receiver_id' => $receiver_id, 'status' => 'pending']);


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
        $getsupplier_playerid = $userdevicesModel->where('user_id', $receiver_id)->first();
        if ($getsupplier_playerid) {

            $message['player_id'] = $getsupplier_playerid->player_id;
            $message['msg'] = $otpdata->otp . ' is SECRET OTP for Order #' . $orderData->order_number . ' at ' . $supplierCompanyData->trading_name . ' on ' . $todayDate . '. Please do not share this OTP.';
            $message['order_uuid'] = $sales_id;

            // sendNotification($message);
            $notify_msg = $message['msg'];
            $notify = $usernotifyModel->create(['user_id' => $receiver_id, 'order_id' => $sales_id, 'notification' => $notify_msg, 'type' => $type]);

            $message['notification_uuid'] = $notify->uuid;
            //send notification
            sendNotification($message);

            // new added
            $userData = User::where('uuid', $receiver_id)->first();
            \Log::info($message['msg']);
            \Log::info($userData->email);
            \Log::info('OTP Verification');
            sendOrderStatusEmail($message['msg'], $userData->email, 'OTP Verification');
        }
    }



    // public static function traderInvoice($supplierLoopData, $productTotal, $shippingMethod, $shippingTotal, $walletBalance, $supplierId, $offerTotal, $offerId, $paybel_amt_input, $item_tax_input, $removebtn, $invoiceNo, $supplierCompanyData, $currentUser){

    //     $supplierLoopData = $supplierLoopData;
    //     $productTotal = $productTotal;
    //     $shippingMethod = $shippingMethod;
    //     $shippingTotal = $shippingTotal;
    //     $walletBalance = $walletBalance;
    //     $supplierId = $supplierId;
    //     $offerTotal = $offerTotal;
    //     $offerId = $offerId;
    //     $paybel_amt_input = $paybel_amt_input;
    //     $item_tax_input = $item_tax_input;
    //     $removebtn = $removebtn;
    //     $invoiceNo = $invoiceNo;
    //     $supplierCompanyData = $supplierCompanyData;
    //     $currentUser = $currentUser;

    //     // dd($supplierLoopData, $productTotal, $shippingMethod, $shippingTotal, $walletBalance, $supplierId, $offerTotal, $offerId, $paybel_amt_input, $item_tax_input, $removebtn, $invoiceNo, $supplierCompanyData, $currentUser);

    //     $phone = '+88 0123 4567 890, +88 0123 4567 999';
    //     $facebook_url = 'https://www.facebook.com/';
    //     $instagram_url = 'https://www.instagram.com/';
    //     $twitter_url = 'https://www.twitter.com/';
    //     $pinterest_url = 'https://www.pinterest.com/';
    //     dd('test');
    //     $pdf = PDF::loadView('frontend.checkout.invoiceEmail',compact('supplierLoopData', 'productTotal', 'shippingMethod', 'shippingTotal', 'walletBalance', 'supplierId','offerTotal','offerId', 'paybel_amt_input', 'item_tax_input','removebtn', 'invoiceNo', 'supplierCompanyData', 'currentUser'))->setPaper('a4');
    //     dd($pdf);
    //     $email = EmailTemplate::where('name','=','trader_order_invoice')->first();

    //        if(isset($email)){
    //             $email->description = str_replace('[CUSTOMER_NAME]', $currentUser->first_name, $email->description);
    //             $email->description = str_replace('[INVOICE_NO]', $invoiceNo, $email->description);
    //             $email->description = str_replace('[EMAIL]', 'support@itradezon.com', $email->description);
    //             $email->description = str_replace('[SITE_NAME]', 'iTradezon.com ', $email->description);
    //             $email->description = str_replace('[PHONE]', $phone, $email->description);
    //             $email->description = str_replace('[FACEBOOK_URL]', $facebook_url, $email->description);
    //             $email->description = str_replace('[TWITTER_URL]', $twitter_url, $email->description);
    //             $email->description = str_replace('[INSTAGRAM_URL]', $instagram_url, $email->description);
    //             $email->description = str_replace('[PINTEREST_URL]', $pinterest_url, $email->description);
    //             $email->description = str_replace('[LOGO]', asset('assets/images/logo.png'), $email->description);
    //         } 

    //         $emailContent = $email->description;

    //         Mail::send([], [], function ($message) use ($data, $pdf, $emailContent, $admin_email) {
    //            $message->to('mananmozar786@gmail.com')
    //             ->subject('Trader Order Invoice')
    //             ->setBody($emailContent, 'text/html'); // for HTML rich messages
    //             $message->attachData($pdf->output(),'customer.pdf');
    //             $message->from($admin_email,'Itradezon');
    //         });






    // }



}
