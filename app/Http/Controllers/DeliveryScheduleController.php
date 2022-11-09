<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DeliverySchedule;
use App\Models\LogisticDetails;
use App\Models\SalesOrder;
use App\Models\UserCompany;
use Auth;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class DeliveryScheduleController extends Controller
{
    public function index(Request $request)
    {

        $data = SalesOrder::join('users', 'users.uuid', 'sales_orders.logistic_id')
            // ->join('delivery_schedules', 'delivery_schedules.order_id', '=', 'sales_orders.uuid')
            ->leftjoin('users as trader', 'trader.uuid', '=', 'sales_orders.user_id')
            ->leftjoin('users as supplier', 'supplier.uuid', '=', 'sales_orders.supplier_id')
            ->leftjoin('users as driver', 'driver.uuid', '=', 'sales_orders.logistic_id')
            ->where('sales_orders.logistic_id', '=', auth()->user()->uuid)
            ->select(
                'sales_orders.*',
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
                'sales_orders.order_lead_time_to_clock',
            )
            ->groupBy('sales_orders.uuid')
            // ->get();
            ->paginate(10);
        // dd('hi', $data);

        $data = tap($data, function ($querys) {
            // $data = $data->tap(function ($querys) {
            return $querys->transform(function ($query) {
                // $data = $data->transform(function ($query) {
                // return $querys->transform(function ($query) {
                $logisticDetails = LogisticDetails::leftjoin('vehicle_capacities', 'vehicle_capacities.uuid', '=', 'logistic_details.vehicle_capacity_id')
                    ->where('logistic_details.uuid', $query->logistic_details_id)
                    ->select(
                        'logistic_details.uuid as vehicle_id',
                        'logistic_details.name',
                        'phone',
                        'driving_licence',
                        'vehicle_capacities.name as vehicle_type',
                        // 'vehicle_type',
                        'vehicle_model',
                        'vin_number',
                        'vehicle_color',




                    )
                    ->first();

                if ($logisticDetails != null) {

                    $query->vehicle_id = $logisticDetails->vehicle_id;
                    $query->name = $logisticDetails->name;
                    $query->phone = $logisticDetails->phone;
                    $query->driving_licence = $logisticDetails->driving_licence;
                    $query->vehicle_type = $logisticDetails->vehicle_type;
                    $query->vehicle_model = $logisticDetails->vehicle_model;
                    $query->vin_number = $logisticDetails->vin_number;
                    $query->vehicle_color = $logisticDetails->vehicle_color;

                    $supplier = UserCompany::where('owner_user_id', $query->suppliers_id)->first();
                    if ($supplier != null) {
                        $supplierAddress = $this->getAddress($supplier);
                    } else {
                        $supplierAddress = '';
                    }
                    $trader = UserCompany::where('owner_user_id', $query->trader_id)->first();
                    if ($trader != null) {
                        $traderAddress = $this->getAddress($trader);
                    } else {
                        $traderAddress = '';
                    }
                    // $logisticDetails['supplierAddress'] = $supplierAddress;
                    // $logisticDetails['traderAddress'] = $traderAddress;

                    $query->supplierAddress = $supplierAddress;
                    $query->traderAddress = $traderAddress;

                    $order_lead_time = Carbon::parse($query->order_lead_time);

                    $order_lead_time_clock = $query->order_lead_time_clock;
                    $order_lead_time_to_clock = $query->order_lead_time_to_clock;
                    // $order_lead_time_clock = Carbon::parse($query->order_lead_time_clock);
                    // $order_lead_time_to_clock = Carbon::parse($query->order_lead_time_to_clock);

                    // dd($query);
                    if ($order_lead_time_to_clock == '24') {
                        $query->date = $order_lead_time->addDays(1)->format('d M');
                    } else if ($order_lead_time_to_clock == '48') {
                        $query->date = $order_lead_time->addDays(2)->format('d M');
                    } else {
                        $query->date = $order_lead_time->format('d M');
                    }

                    $query->to = $order_lead_time_clock;
                    $query->from = $order_lead_time_to_clock;

                    // $query->to = $order_lead_time_clock->format('h:i A');
                    // $query->from = $order_lead_time_to_clock->format('h:i A');

                    // $suborder['date'] = $order_lead_time->format('d M');
                    // $suborder['to'] = $order_lead_time_clock->format('h:i A');
                    // $suborder['from'] = $order_lead_time_to_clock->format('h:i A');

                    $date = $order_lead_time->format('d M');
                    // $to = $order_lead_time_clock->format('h:i A');
                    // $from = $order_lead_time_to_clock->format('h:i A');
                    $from = $order_lead_time_to_clock;

                    if (isset($query->distance)) {

                        $query->distance = $this->convertMilesToKmDistance($query->distance);
                    } else {
                        $query->distance = 0;
                    }
                    // $logisticDetails['scheduled_pickup'] = $date . ' ' . $to . ' - ' . $from;
                    $scheduled_pickup =  $from . 'H ' . $date;
                    $query->scheduled_pickup = $scheduled_pickup;
                }
                return $query;



                // $query->order_id = $logisticDetails->order_id;
            });
        });

        // $data = $data->filter(function ($value) {
        //     return !is_null($value);
        // });
        // $data = $this->paginate($data);
        // $data = LogisticDetails::leftjoin('delivery_schedules', 'delivery_schedules.driver_id', '=', 'logistic_details.user_id')
        //     ->leftjoin('vehicle_capacities', 'vehicle_capacities.uuid', '=', 'logistic_details.vehicle_capacity_id')
        //     ->where('user_id', auth()->user()->uuid)
        //     ->where('logistic_details.status', '1')
        //     ->groupBy('logistic_details.id')
        //     ->select(
        //         'logistic_details.uuid as vehicle_id',
        //         'logistic_details.name',
        //         'phone',
        //         'driving_licence',
        //         'vehicle_capacities.name as vehicle_type',
        //         // 'vehicle_type',
        //         'vehicle_model',
        //         'vin_number',
        //         'vehicle_color',
        //         'order_id'
        //     )
        //     ->paginate(10);

        // dd($data);
        // $data = DeliverySchedule::leftjoin('sales_orders', 'sales_orders.uuid', '=', 'delivery_schedules.order_id')
        //     ->leftjoin('users as trader', 'trader.uuid', '=', 'sales_orders.user_id')
        //     ->leftjoin('users as supplier', 'supplier.uuid', '=', 'sales_orders.supplier_id')
        //     ->leftjoin('user_companies', 'user_companies.owner_user_id', '=', 'sales_orders.user_id')
        //     ->leftjoin('location_cities', 'location_cities.uuid', '=', 'user_companies.city_id')
        //     ->leftjoin('location_countries', 'location_countries.uuid', '=', 'user_companies.country_id')
        //     ->leftjoin('location_states', 'location_states.uuid', '=', 'user_companies.state_id')
        //     ->leftjoin('location_zipcodes', 'location_zipcodes.uuid', '=', 'user_companies.zipcode_id')
        //     ->where('delivery_schedules.driver_id', Auth::user()->uuid)
        //     ->select(
        //         'delivery_schedules.id',
        //         'sales_orders.order_lead_time',
        //         'sales_orders.order_lead_time_clock',
        //         'sales_orders.order_lead_time_to_clock',
        //         'delivery_schedules.slot_booked',
        //         'slot_booked_date',
        //         'slot_booked_from_time',
        //         'slot_booked_to_time',
        //         'sales_orders.order_id',
        //         'trader.first_name as trader_first_name',
        //         'trader.last_name as trader_last_name',
        //         'supplier.first_name as supplier_first_name',
        //         'supplier.last_name as supplier_last_name',
        //         'user_companies.address1',
        //         'user_companies.address2',
        //         'location_cities.city_name',
        //         'location_countries.country_name',
        //         'location_states.state_name',
        //         'location_zipcodes.zipcode',
        //         'sales_orders.order_status',
        //         'sales_orders.distance',
        //         'sales_orders.deliver_vehicle',
        //     )
        //     ->get();

        // $data = $data->transform(function ($query) {
        //     $order = SalesOrder::where('order_id', $query->order_id)->first();
        //     $query->order_number = $order->order_number;
        //     return $query;
        // });

        return view('supplier.drivers.driver_schedule', compact('data'));
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
}
