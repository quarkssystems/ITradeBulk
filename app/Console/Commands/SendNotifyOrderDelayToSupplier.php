<?php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Models\SalesOrder;
use App\Models\UserDevices;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendNotifyOrderDelayToSupplier extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SendNotify:OrderDelayToSupplier';

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
        $now = Carbon::now()->toDateTimeString();
        $oneHour = Carbon::now()->addHours(1)->toDateTimeString();
        $salesOrder = SalesOrder::orderBy('id', 'desc')->where('order_status', SalesOrder::ORDERPLACED)->select('created_at', 'supplier_id', 'order_id')->get();
        // $salesOrder = SalesOrder::orderBy('id', 'desc')->where('supplier_id', 'e22558ec-9723-439c-80d9-80fdddb9328d')->where('order_status', SalesOrder::ORDERPLACED)->select('created_at', 'supplier_id', 'order_id')->get();

        foreach ($salesOrder as $order) {
            $firstDate = Carbon::parse($order->created_at);
            $secondDate = Carbon::parse($oneHour);

            if ($secondDate->gt($firstDate)) {
                $users = User::with('company')->where('uuid', $order->supplier_id)->select('email')->first();


                if ($users->company != null) {
                    $name = $users->company->trading_name;
                } else {
                    $name = $users->first_name . ' ' . $users->last_name;
                }
                $diff = $secondDate->diff($firstDate)->format('%H');
                if ($diff > 1) {
                    $strDiff = $diff . ' hours';
                } else {
                    $strDiff = $diff . ' hour';
                }

                $messageData = 'Order ' . $order->order_number . ' is placed before ' . $strDiff . ' kindly do action accept or cancel order';
                sendOrderStatusEmail($messageData, $users->email, 'Need to take action of placed order');
                $userdevicesModel = new UserDevices();
                $get_playerid = $userdevicesModel->where('user_id', $order->supplier_id)->first();
                if ($get_playerid) {

                    $message['player_id'] = $get_playerid->player_id;
                    $message['msg'] = $messageData;

                    $message['order_uuid'] =  $order->uuid;
                    $notify_msg = $message['msg'];
                    $usernotifyModel = new Notification;
                    $notify = $usernotifyModel->create(['user_id' => $order->supplier_id, 'order_id' =>  $order->uuid, 'notification' => $notify_msg]);

                    $message['notification_uuid'] = $notify->uuid;
                    sendNotification($message);
                }
            }
        }
    }
}
