<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

use App\Exports\DataGridExport;

use App\Http\Controllers\Helpers\DataGrid;

use App\Models\Category;

use App\Models\SalesOrder;
use App\Models\OrderLogisticQueue;
use App\Models\LogisticDetails;
use App\Models\DeliveryVehicleMaster;


use Illuminate\View\View;

use Maatwebsite\Excel\Excel;

use App\User;

use DB;
use Auth;
use App\Repositories\UserRepository;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\DeliverySchedule;
use App\UserRejectNotification;
use App\General\ChangeOrderStatus;
use App\Models\UserCompany;
use Carbon\Carbon;

class DriverNotificationController extends Controller
{
    use DataGrid;

    public $route = 'supplier.notification';

    public function index(Request $request, Notification $notification)
    {

        // if (Auth::user()->role == 'DRIVER') {
        //     $notification = $notification
        //         ->where('notification', 'LIKE', '%New Order from iTradezon%')
        //         // ->orwhere('type',Notification::DRIVER)
        //         ->orwhere('notification', 'LIKE', '%New Order from ' . env('APP_NAME') . '%')
        //         ->where('user_id', auth()->user()->uuid)
        //         // ->whereNull('accept_or_reject')
        //         ->orderBy('id', 'desc')->paginate(10);

        //     $data = $notification;
        // } else {
        $notification = $notification
            // ->where('type',Notification::SUPPLIER)
            ->where('user_id', auth()->user()->uuid)
            // ->whereNull('accept_or_reject')
            ->orderBy('id', 'desc')->paginate(10);

        $data = $notification;
        // }

        // $data =  $notification->transform(function ($value) {
        //         $allData = [];

        //         $str = substr( $value->notification, 0, 24 );
        //         if(is_null($value->accept_or_reject)){
        //             $value->notification = $value->notification . '';
        //             // $value->notification = $value->notification . '<button type="button" class="btn btn-primary acceptDelivery" data-id="'.$value->order_id.'">Accept</button> <button type="button" class="btn btn-primary rejectDelivery" data-id="'.$value->order_id.'">Reject</button>';
        //             // <br><a class="btn btn-primary" href="/supplier/notification_accept/'.$value->order_id.'">Accept</a>
        //             //  <a class="btn btn-primary" href="/supplier/notification_reject/'.$value->order_id.'">Reject</a>

        //         } else {
        //             $value->notification = $value->notification;
        //         }

        //        $salesOrder = SalesOrder::where('uuid',$value->order_id)->first();
        //        $total_weight = ($salesOrder != null) ? $salesOrder->total_weight : '';
        //        $value->total_weight = $total_weight;
        //         $weight = $this->convertmass($total_weight);
        //        $value->weight = $weight;

        //        if($salesOrder != null){
        //            if($salesOrder->order_lead_time != null){

        //                $order_lead_time = str_replace('00:00:00','',$salesOrder->order_lead_time);
        //                $dateTime = ($salesOrder->order_lead_time_clock != null) ? $salesOrder->order_lead_time_clock : '';
        //                $updatedDateFormat =  \Carbon\Carbon::createFromFormat('H:i:s', $dateTime)->format('h:i A');
        //                $dateTime = $order_lead_time .''.$updatedDateFormat;
        //             } else {
        //                 $order_lead_time = '';
        //                 $dateTime = '';
        //            }

        //            $value->dateAndTime = $dateTime;

        //            $value->delivery_amount = $salesOrder->shipment_amount;
        //            $value->total_weight = $salesOrder->total_weight;
        //            $value->distance = $salesOrder->distance;
        //            $value->order_number = $salesOrder->order_number;
        //            $value->order_id = $salesOrder->uuid;

        //        } else {
        //             $value->dateAndTime = 'NA';
        //             $value->delivery_amount = 0;
        //             $value->total_weight = '';
        //             $value->distance = '';
        //            $value->order_number = '';
        //            $value->order_id = '';

        //        }

        //     //    Basket::where('order_id',$value->order_id)->first();

        //        $logisticDetails = LogisticDetails::where('user_id',Auth::user()->uuid)->first();
        //         if($logisticDetails != null){

        //             $weightInTon = $this->convertmass($logisticDetails->transport_capacity.'ton');
        //             if($weight <= $weightInTon){
        //             // dd($salesOrder);
        //                 if($salesOrder != null){
        //                     if($salesOrder->delivery_type != 'pickup'){
        //                         $allData = $value;
        //                     }
        //                 }
        //             }
        //         }

        //        return $allData;
        // });

        // $filterData = [];
        // foreach($data as $value){
        //     if(gettype($value) != 'array'){
        //         $filterData[] = $value;
        //     } else {
        //         if(count($value) != 0){
        //             $filterData[] = $value;
        //         }
        //     }

        // }
        // $data = $filterData;
        // $data = $this->paginate($data);

        if ($request->ajax()) {

            return view('supplier.drivers.grid_driver', compact('data'));
        } else {
            return view('supplier.drivers.notification', compact('data'));
        }
    }

    public function tender(Request $request, Notification $notification)
    {


        $notification = $notification->where('notification', 'LIKE', '%New Order from iTradezon%')
            ->orwhere('notification', 'LIKE', '%New Order from ' . env('APP_NAME') . '%')
            ->whereNull('accept_or_reject')
            ->orderBy('id', 'desc')
            ->get();

        $finaldata =  $notification->transform(function ($value) {
            $allData = [];

            if ($value != null) {

                $rejectReason = UserRejectNotification::where('order_id', $value->order_id)->where('user_id', Auth::user()->uuid)->first();

                if ($rejectReason == null) {


                    $salesOrder = SalesOrder::where('uuid', $value->order_id)->whereNull('logistic_id')->first();
                    // $salesOrder = SalesOrder::where('uuid', $value->order_id)->where('order_status', SalesOrder::ACCEPTORDER)->first();
                    // dd($salesOrder->user_id);
                    // $salesOrder = SalesOrder::where('uuid',$value->order_id)->select('id','distance','total_weight','order_lead_time','order_lead_time_clock','order_lead_time_to_clock','order_status')->first();

                    if ($salesOrder != null) {
                        $trader = UserCompany::where('owner_user_id', $salesOrder->user_id)->first();
                        if (isset($trader->city) && $trader->city != null) {
                            $town = $trader->city->city_name;
                        } else {
                            $town = '';
                        }
                        $total_weight = ($salesOrder != null) ? $salesOrder->total_weight : '';
                        $weight = (int)$this->convertmass($total_weight);
                        $allData['distance'] = ($salesOrder != null) ? $salesOrder->distance : 0;
                        $allData['payload'] = $weight;
                        $allData['order_id'] = $value->order_id;
                        $allData['orderId'] = $salesOrder->order_number;
                        $allData['town'] = $town;
                        // $allData['orderId'] = $salesOrder->id;
                        $allData['uuid'] = $salesOrder->uuid;

                        // $order_lead_time = $salesOrder->order_lead_time;
                        $order_lead_time_clock = $salesOrder->order_lead_time_clock;
                        $order_lead_time_to_clock = $salesOrder->order_lead_time_to_clock;

                        // $order_lead_time_to_clock_new = (int)$order_lead_time_to_clock + 24;
                        $order_lead_time = Carbon::parse($salesOrder->order_lead_time);
                        // $order_lead_time_clock = Carbon::parse($salesOrder->order_lead_time_clock);
                        // $order_lead_time_to_clock = Carbon::parse($salesOrder->order_lead_time_to_clock);

                        // $suborder['date'] = $order_lead_time;
                        $suborder['to'] = $order_lead_time_clock;
                        $suborder['from'] = $order_lead_time_to_clock;

                        if ($order_lead_time_to_clock == '24') {
                            $suborder['date'] = $order_lead_time->addDays(1)->format('d M');
                        } else if ($order_lead_time_to_clock == '48') {
                            $suborder['date'] = $order_lead_time->addDays(2)->format('d M');
                        } else if ($order_lead_time_to_clock == '72') {
                            $suborder['date'] = $order_lead_time->addDays(3)->format('d M');
                        } else {
                            $suborder['date'] = $order_lead_time->format('d M');
                        }
                        // $suborder['date'] = $order_lead_time->format('d M');
                        // $suborder['to'] = $order_lead_time_clock->format('h:i A');
                        // $suborder['from'] = $order_lead_time_to_clock->format('h:i A');

                        $allData['scheduled_pickup'] =  $suborder['from'] . 'H ' . $suborder['date'];
                        $allData['status'] = $salesOrder->order_status;

                        $allData['payload'] = $weight;


                        $logisticDetail = new LogisticDetails;
                        $logisticData = $logisticDetail->getVehicleData($weight);

                        $userLogisticData = [];
                        if ($logisticData != null) {
                            $deliveryVehicle = DeliveryVehicleMaster::
                                // where('vehicle_type', $data->vehicle_type)
                                where('vehicle_type', 'like', '%' . $logisticData->vehicle_type . '%')
                                // ->select('price_per_km')
                                ->first();

                            if ($deliveryVehicle != null) {

                                $allData['price_per_km'] = $deliveryVehicle->price_per_km;
                                $allData['trading_area'] = $logisticData->trading_area;
                                $allData['pallets'] = $logisticData->pallet_capacity_standard;
                                $allData['body_volumn'] = $logisticData->body_volumn;
                                $allData['vehicle'] = $deliveryVehicle->vehicle_type;
                                $allData['vehicle_id'] = $logisticData->uuid;
                            } else {
                                $allData['price_per_km'] = '0';
                                $allData['trading_area'] = '';
                                $allData['pallets'] = '0';
                                $allData['body_volumn'] = '0';
                                $allData['vehicle'] = '';
                                $allData['vehicle_id'] = '';
                            }
                        } else {
                            $allData['price_per_km'] = '0';
                            $allData['trading_area'] = '';
                            $allData['pallets'] = '0';
                            $allData['body_volumn'] = '0';
                            $allData['vehicle'] = '';
                            $allData['vehicle_id'] = '';
                        }

                        // foreach ($logisticData as $data) {
                        //     // dd($data->vehicle_type);

                        //     if ($data->user_id == Auth::user()->uuid) {
                        //         // $userLogisticData[] = $data;
                        //         $deliveryVehicle = DeliveryVehicleMaster::
                        //             // where('vehicle_type', $data->vehicle_type)
                        //             where('vehicle_type', 'like', '%' . $data->vehicle_type . '%')
                        //             // ->select('price_per_km')
                        //             ->first();
                        //         // uuid
                        //         if ($deliveryVehicle != null) {

                        //             $allData['price_per_km'] = $deliveryVehicle->price_per_km;
                        //             $allData['trading_area'] = $data->trading_area;
                        //             $allData['pallets'] = $data->pallet_capacity_standard;
                        //             $allData['body_volumn'] = $data->body_volumn;
                        //         } else {
                        //             $allData['price_per_km'] = 0;
                        //             $allData['trading_area'] = '';
                        //             $allData['pallets'] = '';
                        //             $allData['body_volumn'] = '';
                        //         }

                        //         // truck_length
                        //         // truck_width
                        //         // truck_height

                        //         // trailer_length
                        //         // trailer_width
                        //         // trailer_height
                        //         // dd($data);

                        //         // $logisticDetail->where('');
                        //         // body_volumn
                        //         // combine_payload
                        //         // dd($data);
                        //     } else {
                        //         $allData['price_per_km'] = '0';
                        //         $allData['trading_area'] = '';
                        //         $allData['pallets'] = '0';
                        //         $allData['body_volumn'] = '0';
                        //     }
                        // }
                        if (isset($allData['distance'])) {

                            $allData['distance'] = $this->convertMilesToKmDistance($allData['distance']);
                        } else {
                            $allData['distance'] = 0;
                        }
                        $allData['tender_price'] =  (float)$allData['distance'] * (float)$allData['price_per_km'];

                        return $allData;
                    }
                }
            }
        });


        $data = [];
        foreach ($finaldata as $datas) {
            if ($datas != null && $datas['vehicle'] != "") {
                $data[] = $datas;
            }
        }

        $temp_array = [];
        if (count($data) != 0) {
            foreach ($data as &$v) {
                if (!isset($temp_array[$v['orderId']]))
                    $temp_array[$v['orderId']] = &$v;
            }
            $data = array_values($temp_array);
        }
        // $newData = [];
        // foreach ($data as $datas) {
        //     // dd($datas['orderId']);
        //     if ($datas != null) {
        //         $newData[] = $datas;
        //     }
        // }

        $data = $this->paginate($data);
        // allData
        // dd($data);
        return view('supplier.drivers.tender', compact('data'));
    }

    public function convertMilesToKmDistance($distance)
    {
        if (str_contains($distance, 'mi')) {
            $distance = str_replace("mi", "", $distance);
            $result = (float)$distance * 1.609;
        } else {
            $result = (float)$distance;
        }

        return number_format((float)$result, 1, '.', '');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    public function convertmass($mass)
    {
        if (str_contains($mass, 'kg')) {
            $kg = str_replace("kg", "", $mass);
            $kg = trim($kg);
            return $kg;
        } elseif (str_contains($mass, 'gm')) {
            $gm = str_replace("gm", "", $mass);
            $gm = trim($gm);
            $gm = $gm * 1000;
            return $gm;
        } elseif (str_contains($mass, 'ton')) {
            $ton = str_replace("ton", "", $mass);
            $ton = trim($ton);
            $ton = $ton * 1000;
            return $ton;
        }
    }

    public function getDeliveryData($id)
    {

        $data = SalesOrder::leftjoin('users as trader', 'trader.uuid', '=', 'sales_orders.user_id')
            ->leftjoin('users as supplier', 'supplier.uuid', '=', 'sales_orders.supplier_id')
            ->leftjoin('users as driver', 'driver.uuid', '=', 'sales_orders.logistic_id')
            ->where('sales_orders.uuid', $id)
            ->select(
                'sales_orders.id',
                'sales_orders.uuid',
                'sales_orders.order_id',
                'sales_orders.final_total',
                'supplier.first_name as suppliers_name',
                'supplier.uuid as suppliers_id',
                'trader.first_name as trader_name',
                'trader.uuid as trader_id',
                'driver.first_name as driver_name',
                'sales_orders.order_status',
                'sales_orders.shipment_amount',
                'sales_orders.total_weight',
                'sales_orders.distance',
                'sales_orders.order_lead_time',
                'sales_orders.order_lead_time_clock',
                'sales_orders.order_lead_time_to_clock',
            )
            // ->orderBy('sales_orders.id', 'desc')
            // ->groupBy('sales_orders.order_id')
            ->first();

        if ($data != null) {

            $supplier = UserCompany::where('owner_user_id', $data->suppliers_id)->first();
            if ($supplier != null) {
                $supplierAddress = $this->getAddress($supplier);
            } else {
                $supplierAddress = '';
            }
            $trader = UserCompany::where('owner_user_id', $data->trader_id)->first();
            if ($trader != null) {
                $traderAddress = $this->getAddress($trader);
            } else {
                $traderAddress = '';
            }
            $data['supplierAddress'] = $supplierAddress;
            $data['traderAddress'] = $traderAddress;

            // $order_lead_time = $data->order_lead_time;
            $order_lead_time_clock = $data->order_lead_time_clock;
            $order_lead_time_to_clock = $data->order_lead_time_to_clock;

            $order_lead_time = Carbon::parse($data->order_lead_time);
            // $order_lead_time_clock = Carbon::parse($data->order_lead_time_clock);
            // $order_lead_time_to_clock = Carbon::parse($data->order_lead_time_to_clock);

            // $suborder['date'] = $order_lead_time->format('d M');
            $suborder['to'] = $order_lead_time_clock;
            $suborder['from'] = $order_lead_time_to_clock;

            if ($order_lead_time_to_clock == '24') {
                $suborder['date'] = $order_lead_time->addDays(1)->format('d M');
            } else if ($order_lead_time_to_clock == '48') {
                $suborder['date'] = $order_lead_time->addDays(2)->format('d M');
            } else {
                $suborder['date'] = $order_lead_time->format('d M');
            }
            // $suborder['date'] = $order_lead_time->format('d M');
            // $suborder['to'] = $order_lead_time_clock->format('h:i A');
            // $suborder['from'] = $order_lead_time_to_clock->format('h:i A');

            if (isset($data['distance'])) {

                $data['distance'] = $this->convertMilesToKmDistance($data['distance']);
            } else {
                $data['distance'] = 0;
            }

            $data['scheduled_pickup'] = $suborder['from'] . 'H ' . $suborder['date'];
            // $data['scheduled_pickup'] = $suborder['date'] . ' ' . $suborder['to'] . ' - ' . $suborder['from'];
        }

        return $data;
    }

    public function getDeliverySchduleData($id)
    {

        $data = SalesOrder::leftjoin('users as trader', 'trader.uuid', '=', 'sales_orders.user_id')
            ->leftjoin('users as supplier', 'supplier.uuid', '=', 'sales_orders.supplier_id')
            ->leftjoin('users as driver', 'driver.uuid', '=', 'sales_orders.logistic_id')
            ->leftjoin('logistic_details', 'logistic_details.uuid', '=', 'sales_orders.logistic_details_id')
            ->where('sales_orders.uuid', $id)
            // ->where('sales_orders.id', $id)
            // ->where('sales_orders.logistic_details_id', $id)
            ->select(
                'sales_orders.id',
                'sales_orders.uuid',
                'sales_orders.order_id',
                'sales_orders.final_total',
                'supplier.first_name as suppliers_name',
                'supplier.uuid as suppliers_id',
                'trader.first_name as trader_name',
                'trader.uuid as trader_id',
                'driver.first_name as driver_name',
                'sales_orders.order_status',
                'sales_orders.shipment_amount',
                'sales_orders.total_weight',
                'sales_orders.distance',

                'logistic_details.phone',
                'logistic_details.driving_licence',
                // 'vehicle_type',
                'logistic_details.vehicle_model',
                'logistic_details.vin_number',
                'logistic_details.vehicle_color',
            )
            // ->orderBy('sales_orders.id', 'desc')
            // ->groupBy('sales_orders.order_id')
            ->first();

        if ($data != null) {
            $supplier = UserCompany::where('owner_user_id', $data->suppliers_id)->first();
            if ($supplier != null) {
                $supplierAddress = $this->getAddress($supplier);
            } else {
                $supplierAddress = '';
            }
            $trader = UserCompany::where('owner_user_id', $data->trader_id)->first();
            if ($trader != null) {
                $traderAddress = $this->getAddress($trader);
            } else {
                $traderAddress = '';
            }
            $data['supplierAddress'] = $supplierAddress;
            $data['traderAddress'] = $traderAddress;

            $order_lead_time = Carbon::parse($data->order_lead_time);
            $order_lead_time_clock = Carbon::parse($data->order_lead_time_clock);
            $order_lead_time_to_clock = Carbon::parse($data->order_lead_time_to_clock);

            $suborder['date'] = $order_lead_time->format('d M');
            $suborder['to'] = $order_lead_time_clock->format('h:i A');
            $suborder['from'] = $order_lead_time_to_clock->format('h:i A');

            $data['scheduled_pickup'] = $suborder['date'] . ' ' . $suborder['to'] . ' - ' . $suborder['from'];
            if (isset($data['distance'])) {
                $data['distance'] = $this->convertMilesToKmDistance($data['distance']);
            } else {
                $data['distance'] = 0;
            }
        }
        // else {
        //     $data['supplierAddress'] = '';
        //     $data['traderAddress'] = '';

        //     $suborder['date'] = '';
        //     $suborder['to'] = '';
        //     $suborder['from'] = '';

        //     $data['scheduled_pickup'] = $suborder['date'] . ' ' . $suborder['to'] . ' - ' . $suborder['from'];
        // }

        return $data;
    }

    public function getAllDeliverySchduleData($id)
    {

        $dataAll = SalesOrder::leftjoin('users as trader', 'trader.uuid', '=', 'sales_orders.user_id')
            ->leftjoin('users as supplier', 'supplier.uuid', '=', 'sales_orders.supplier_id')
            ->leftjoin('users as driver', 'driver.uuid', '=', 'sales_orders.logistic_id')
            ->leftjoin('logistic_details', 'logistic_details.uuid', '=', 'sales_orders.logistic_details_id')
            ->where('sales_orders.logistic_details_id', $id)
            ->select(
                'sales_orders.id',
                'sales_orders.uuid',
                'sales_orders.order_id',
                'sales_orders.final_total',
                'supplier.first_name as suppliers_name',
                'supplier.uuid as suppliers_id',
                'trader.first_name as trader_name',
                'trader.uuid as trader_id',
                'driver.first_name as driver_name',
                'sales_orders.order_status',
                'sales_orders.shipment_amount',
                'sales_orders.total_weight',
                'sales_orders.distance',

                'logistic_details.phone',
                'logistic_details.driving_licence',
                // 'vehicle_type',
                'logistic_details.vehicle_model',
                'logistic_details.vin_number',
                'logistic_details.vehicle_color',
            )
            // ->orderBy('sales_orders.id', 'desc')
            // ->groupBy('sales_orders.order_id')
            ->get();
        // ->first();

        $data = $dataAll->transform(function ($data) {
            if ($data != null) {
                $supplier = UserCompany::where('owner_user_id', $data->suppliers_id)->first();
                if ($supplier != null) {
                    $supplierAddress = $this->getAddress($supplier);
                } else {
                    $supplierAddress = '';
                }
                $trader = UserCompany::where('owner_user_id', $data->trader_id)->first();
                if ($trader != null) {
                    $traderAddress = $this->getAddress($trader);
                } else {
                    $traderAddress = '';
                }
                $data['supplierAddress'] = $supplierAddress;
                $data['traderAddress'] = $traderAddress;

                $order_lead_time = Carbon::parse($data->order_lead_time);
                $order_lead_time_clock = Carbon::parse($data->order_lead_time_clock);
                $order_lead_time_to_clock = Carbon::parse($data->order_lead_time_to_clock);

                $suborder['date'] = $order_lead_time->format('d M');
                $suborder['to'] = $order_lead_time_clock->format('h:i A');
                $suborder['from'] = $order_lead_time_to_clock->format('h:i A');

                $data['scheduled_pickup'] = $suborder['date'] . ' ' . $suborder['to'] . ' - ' . $suborder['from'];
                if (isset($data['distance'])) {
                    $data['distance'] = $this->convertMilesToKmDistance($data['distance']);
                } else {
                    $data['distance'] = 0;
                }
            }
            return $data;
        });

        // else {
        //     $data['supplierAddress'] = '';
        //     $data['traderAddress'] = '';

        //     $suborder['date'] = '';
        //     $suborder['to'] = '';
        //     $suborder['from'] = '';

        //     $data['scheduled_pickup'] = $suborder['date'] . ' ' . $suborder['to'] . ' - ' . $suborder['from'];
        // }

        return $data;
    }

    public function getAddress($data)
    {
        $address1 = (isset($data->address1) && $data->address1 != null) ? $data->address1 : '';
        $address2 = (isset($data->address2) && $data->address2 != null) ? $data->address2 : '';
        $country = (isset($data->country) && $data->country != null) ? $data->country->country_name : '';
        $state = (isset($data->state) && $data->state != null) ? $data->state->state_name : '';
        $city = (isset($data->city) && $data->city != null) ? $data->city->city_name : '';
        $zipcode = (isset($data->zipcode) && $data->zipcode != null) ? $data->zipcode->zipcode : '';

        $address = $address1 . ' ' . $address2 . ' ' . $city  . ' ' . $state . ' ' . $country . ' ' . $zipcode;
        return $address;
    }

    public function acceptedDelivery(Request $request, Notification $notification)
    {

        $data = SalesOrder::leftjoin('users as trader', 'trader.uuid', '=', 'sales_orders.user_id')
            ->leftjoin('users as supplier', 'supplier.uuid', '=', 'sales_orders.supplier_id')
            ->leftjoin('users as driver', 'driver.uuid', '=', 'sales_orders.logistic_id')
            ->where('sales_orders.logistic_id', Auth::user()->uuid)
            ->select(
                'sales_orders.id',
                'sales_orders.uuid',
                'sales_orders.order_id',
                'sales_orders.final_total',
                'supplier.first_name as suppliers_name',
                'trader.first_name as trader_name',
                'driver.first_name as driver_name',
                'sales_orders.order_status',
                'sales_orders.shipment_amount',
                'sales_orders.total_weight',
                'sales_orders.distance',
            )
            ->orderBy('sales_orders.id', 'desc')
            ->groupBy('sales_orders.order_id')
            ->paginate();

        $get = OrderLogisticQueue::where('driver_id', Auth::user()->uuid)->get();
        //    dd($data);
        if ($request->ajax()) {

            return view('supplier.drivers.grid_driver_accepted', compact('data'));
        } else {
            return view('supplier.drivers.notification_accepted', compact('data'));
        }
    }


    public function acceptNotification(Request $request, $order_id)
    {

        // $data = DeliverySchedule::leftjoin('sales_orders', 'sales_orders.uuid', '=', 'delivery_schedules.order_id')
        //     ->where('delivery_schedules.driver_id', Auth::user()->uuid)
        //     ->where('delivery_schedules.slot_booked', '>', \Carbon\Carbon::now()->toDateTimeString())
        //     ->select('sales_orders.order_lead_time', 'sales_orders.order_lead_time_clock', 'delivery_schedules.slot_booked')
        //     ->get();

        // if ($request->date == null) {

        //     $salesOrder = SalesOrder::where('uuid', $order_id)->first();
        //     // if ($salesOrder->order_lead_time != null) {
        //     //     $currentDate = $salesOrder->order_lead_time->toDateString() . ' ' . $salesOrder->order_lead_time_clock;
        //     //     $endcurrentDate = $salesOrder->order_lead_time->toDateString() . ' ' . $salesOrder->order_lead_time_to_clock;
        //     //     $date = $salesOrder->order_lead_time->toDateString();
        //     // } else {
        //     //     $currentDate = \Carbon\Carbon::now()->toDateString();
        //     //     $endcurrentDate = \Carbon\Carbon::now()->toDateString();
        //     //     $date = \Carbon\Carbon::now()->toDateString();
        //     // }
        //     $fromTime = $salesOrder->order_lead_time_clock;
        //     $toTime = $salesOrder->order_lead_time_to_clock;
        // } else {
        //     $currentDate = $request->date . ' ' . $request->from_time;
        //     $endcurrentDate = $request->date . ' ' . $request->to_time;
        //     $date = $request->date;
        //     $fromTime = $request->from_time;
        //     $toTime = $request->to_time;
        // }

        // foreach ($data as $value) {
        //     $LeadStartDate = date('Y-m-d H:i', strtotime($value->order_lead_time . ' ' . $value->order_lead_time_clock));
        //     $startDate = date('Y-m-d H:i', strtotime($value->slot_booked_date . ' ' . $value->slot_booked_from_time));
        //     $endDate = date('Y-m-d H:i', strtotime($value->slot_booked_date . ' ' . $value->slot_booked_to_time));
        //     if (($currentDate >= $startDate) && ($endcurrentDate <= $endDate)) {
        //         // return redirect()->back()->with('error','Slot already booked');
        //         return redirect('/supplier/notification')->with(['error_message' => 'Slot already booked <a href="/supplier/delivery_schedule" target="_blank">Please check schedule</>']);
        //     }
        // }

        $data = SalesOrder::where('uuid', $order_id)->first();
        // dd($data, $request->all());
        Notification::where('order_id', $order_id)->update(['accept_or_reject' => 'ACCEPT']);
        SalesOrder::where('uuid', $order_id)->update(['logistic_details_id' => $request->logistic_details_id]);
        LogisticDetails::where('uuid', $request->logistic_details_id)->update(['is_available' => '0']);

        $driver = new UserRepository;

        $driver_uuid = Auth::user()->uuid;
        //   $driver_uuid = $request['driver_id']; 

        $order_uuid = $request['order_uuid'];
        //   $order_uuid = $request['order_uuid']; 



        // DeliverySchedule::create([
        //     'slot_booked' => $currentDate,
        //     'slot_booked_date' => $date,
        //     'slot_booked_from_time' => $fromTime,
        //     'slot_booked_to_time' => $toTime,
        //     'driver_id' => $driver_uuid,
        //     'order_id' => $order_id
        // ]);
        $data = [
            'driver_id' => Auth::user()->uuid,
            'order_uuid' => $order_id
        ];
        $driver->acceptOrderDriver($data);



        $orderData = SalesOrder::where('uuid', $order_id)->first();

        $req = array();
        $req['order_status'] = 'ACCEPT DELIVERY';
        $req['order_uuid'] = $order_id;
        $req['user_id'] = auth()->user()->uuid;
        $req['delivery_type'] = $orderData->delivery_type;

        ChangeOrderStatus::orderStatus($req);



        return redirect('/supplier/tender')->with(['status' => 'success', 'message' => 'Delivery Accepted']);
    }

    public function rejectNotification(Request $request, $order_id)
    {

        UserRejectNotification::create([
            'order_id' => $order_id,
            'user_id' => Auth::user()->uuid,
            'reject_reason' => $request->reason
        ]);
        // Notification::where('order_id',$order_id)->update(['accept_or_reject' => 'REJECT','reject_reason' => $request->reason]);

        return redirect('/supplier/tender')->with(['status' => 'success', 'message' => 'Delivery Accepted']);
    }

    public function rejectAfterAcceptNotification(Request $request, $order_id)
    {
        UserRejectNotification::create([
            'order_id' => $order_id,
            'user_id' => Auth::user()->uuid,
            'reject_reason' => $request->reason
        ]);
        Notification::where('order_id', $order_id)->update(['accept_or_reject' => NULL, 'reject_reason' => $request->reason]);
        SalesOrder::where('uuid', $order_id)->update(['logistic_id' => NULL, 'logistic_details_id' => NULL, 'order_status' => SalesOrder::ACCEPTORDER]);

        $allDrivers = \App\User::where('users.role', 'DRIVER')
            // ->where('users.email','testdriver@mailinator.com')
            ->get();
        $orders = \App\Models\OrderLogisticQueue::where('driver_id', $allDrivers->pluck('uuid'))->where('status', '=', 'OCCUPIED')->get();
        $driverData = \App\User::whereNotIn('uuid', $orders->pluck('uuid'))
            // ->where('users.email','testdriver@mailinator.com')
            ->where('users.role', 'DRIVER')->get();

        foreach ($driverData as $driver) {
            $messageData = 'New Delivery <a href="' . env('APP_URL') . 'supplier/notification">Order Link<a>';
            // $messageData = 'New Delivery <a href="'.env('APP_URL').'user/sales-orders/'.$order_uuid.'/edit">Order Link<a>';
            sendOrderStatusEmail($messageData, $driver->email, 'New Delivery');
        }

        return redirect('/supplier/delivery_schedule')->with(['status' => 'success', 'message' => 'Delivery Accepted']);
        // return redirect('/supplier/accepted_delivery')->with(['status' => 'success', 'message' => 'Delivery Accepted']);
    }

    public function read($id)
    {
        // dd('hi', $id);
        Notification::where('uuid', $id)->update(['status' => 'READ']);
        return redirect()->back()->with(['status' => 'success', 'message' => 'Notification read']);
    }

    public function readAll()
    {
        Notification::where('user_id', auth()->user()->uuid)->update(['status' => 'READ']);
        return redirect()->back()->with(['status' => 'success', 'message' => 'Notification read']);
    }
}
