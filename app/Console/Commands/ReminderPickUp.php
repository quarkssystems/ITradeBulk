<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SalesOrder;
use App\Models\UserDevices;
use App\DeliverySchedule;
use App\User;
use Carbon\Carbon;

class ReminderPickUp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:pickup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //    $salesOrder = SalesOrder::where('order_status','PACKED')->whereNotNull('logistic_id')->where('order_lead_time',Carbon::now()->toDateString())->select('order_lead_time','order_lead_time_clock','order_id','uuid','supplier_id')->get();
        //    foreach($salesOrder as $order){
        //         if(isset($order->order_lead_time)){
        //             $order_lead_time = str_replace('00:00:00','',$order->order_lead_time);
        //             $order_lead_time = trim($order_lead_time);

        //             $full_order_lead_time = Carbon::createFromFormat('Y-m-d H:i',$order_lead_time.' '.$order->order_lead_time_clock)->format('Y-m-d H:i:s');
        //             $newDateTime = Carbon::parse($full_order_lead_time)->subHour();
        //             $result = $newDateTime->eq(Carbon::now()->toDateTimeString());
        //             if($result){

        //                 $deliveryScheduleData = DeliverySchedule::where('order_id', $order->uuid)->first(['id','driver_id', 'slot_booked']);
        //                 $userData = User::where('uuid',$order->supplier_id)->select('first_name','last_name')->first();

        //                 $driverData = User::where('uuid',$deliveryScheduleData->driver_id)->select('email')->first();
        //                 $tradeport = $userData->first_name .' '.$userData->last_name;
        //                 $messageData = 'Order '.$order->order_number.' is ready for collection at '.$tradeport.', Please Collect by '.$deliveryScheduleData->slot_booked;
        //                 sendOrderStatusEmail($messageData,$driverData->email,'Reminder Pickup Delivery');
        //                 $userdevicesModel = New UserDevices;
        //                 $get_playerid = $userdevicesModel->where('user_id', $deliveryScheduleData->driver_id)->first();
        //                  if($get_playerid){

        //                     $message['player_id'] = $get_playerid->player_id;
        //                     $message['msg'] = $messageData;

        //                      $message['order_uuid'] =  $order->uuid;
        //                      $notify_msg = $message['msg']; 
        //                      $notify = $usernotifyModel->create([ 'user_id' => $deliveryScheduleData->driver_id, 'order_id' =>  $order->uuid ,'notification' =>$notify_msg]); 

        //                     $message['notification_uuid'] = $notify->uuid;   
        //                      //send notification
        //                      sendNotification($message);

        //                 } 
        //             }
        //         }


        //    }
    }
}
