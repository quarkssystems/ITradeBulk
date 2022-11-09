<?php



namespace App\Http\Controllers;



use App\Http\Controllers\Helpers\BaseController;

use App\Http\Controllers\Helpers\DataGrid;

use App\Models\SalesOrder;

use App\Models\DeliveryVehicleMaster;

use App\Models\VehicleCapacity;

use App\Models\LocationCity;

use App\Models\LocationState;

use App\Models\LocationZipcode;

use App\Models\Category;

use App\Models\Product;

use App\Models\Brand;

use App\Models\OfferDeals;

use App\Models\OffercodeUsedby;

use App\Models\UserBankDetails;
use App\Models\SupplierItemInventory;
use App\Models\WalletTransactions;
use App\Models\BasketProducts;
use App\Models\UserDevices;
use App\Models\Notification;




use App\User;
use App\PickingDocument;
use App\DispatchedDocument;
use App\Models\Basket;

use Carbon\Carbon;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\General\ChangeOrderStatus;

use App\Models\BankBranch;



use App\Models\BankMaster;
use App\DeliverySchedule;
use App\Models\LogisticDetails;
use App\Models\UserCompany;
use Validator;
use PDF;
use Illuminate\Support\Facades\Mail;




class FrontendAjaxController extends Controller

{

    use DataGrid, BaseController;

    public function postGetStates(Request $request, LocationState $locationStateModel)

    {

        if ($request->has('country_id') && !empty($request->get('country_id'))) {

            $locationStateModel->setCountryUUID($request->country_id);

            $states = $locationStateModel->ofCountry()->orderBy('state_name')->pluck('state_name', 'uuid');
        } else {

            $states = [];
        }

        $viewFile = 'frontend.helpers.ajax.locationStateDropdown';

        if ($request->has('view_file') && !empty($request->get('view_file'))) {

            $viewFile = $request->get('view_file');
        }

        return view($viewFile, compact('states'));
    }



    public function postGetCities(Request $request, LocationCity $locationCityModel)

    {

        if ($request->has('state_id') && !empty($request->get('state_id'))) {

            $locationCityModel->setStateUUID($request->state_id);

            $cities = $locationCityModel->ofState()->orderBy('city_name')->pluck('city_name', 'uuid');
        } else {

            $cities = [];
        }

        $viewFile = 'frontend.helpers.ajax.locationCityDropdown';

        if ($request->has('view_file') && !empty($request->get('view_file'))) {

            $viewFile = $request->get('view_file');
        }

        return view($viewFile, compact('cities'));
    }



    public function postGetAreas(Request $request, LocationZipcode $locationAreaModel)

    {

        if ($request->has('city_id') && !empty($request->get('city_id'))) {

            $locationAreaModel->setCityUUID($request->city_id);

            $zipcodes = $locationAreaModel->ofCity()

                ->select(DB::raw("CONCAT(zipcode,'-',zipcode_name) AS zip"), 'uuid')->orderBy('zipcode_name')->pluck('zip', 'uuid');
        } else {

            $zipcodes = [];
        }



        $viewFile = 'frontend.helpers.ajax.locationZipcodeDropdown';

        if ($request->has('view_file') && !empty($request->get('view_file'))) {

            $viewFile = $request->get('view_file');
        }

        return view($viewFile, compact('zipcodes'));
    }



    public function postGetProduct(Request $request, Product $productModel)

    {



        $products = $productModel->whereHas('supplierStock', function ($q) {

            $q->where('single', '>', 0);

            $q->orWhere('shrink', '>', 0);

            $q->orWhere('case', '>', 0);

            $q->orWhere('pallet', '>', 0);
        });



        if ($request->has('name') && !empty($request->get('name'))) {

            $products = $products->where("name", "LIKE", "{$request->get('name')}%")->pluck('name');
        } else {

            $products = [];
        }

        $search_product = '';



        foreach ($products as $key => $value) {

            # code..



            $search_product .= '<a style="cursor:pointer;" class ="linkval" data-id="' . $value . '" >' . $value . '</a>';



            // $search_product .= '<br/>';

        }



        return $search_product;
    }



    public function postGetCategories(Request $request, Category $categoryModel)

    {

        if ($request->has('name') && !empty($request->get('name'))) {

            $list = $categoryModel->where("name", "LIKE", "{$request->get('name')}%")->get();
        } else {

            $list = [];
        }



        $param = 'category';

        $viewFile = 'frontend.helpers.ajax.list';

        if ($request->has('view_file') && !empty($request->get('view_file'))) {

            $viewFile = $request->get('view_file');
        }



        return view($viewFile, compact('list', 'param'));
    }



    public function postGetBrand(Request $request, Brand $brandModel)

    {



        if ($request->has('name') && !empty($request->get('name'))) {

            $list = $brandModel->where("name", "LIKE", "{$request->get('name')}%")->get();
        } else {

            $list = [];
        }





        $param = 'brand';

        $viewFile = 'frontend.helpers.ajax.list';

        if ($request->has('view_file') && !empty($request->get('view_file'))) {

            $viewFile = $request->get('view_file');
        }



        return view($viewFile, compact('list', 'param'));
    }



    public function verifyPromoCode(Request $request,  OfferDeals $offerModel)

    {

        $offer_flag = 0;

        $listr = '';

        $arrOffersSupplier = [];

        $offerAmount = 0;

        $amountAfterDiscount = $request->get('total_amount');

        $response = [];

        if ($request->has('promocode') && !empty($request->get('promocode'))) {

            $codeModel = new OffercodeUsedby;

            $todayDate = Carbon::now()->format('Y-m-d');

            $offers = $offerModel->where("user_id", $request->get('supplierid'))->where("status", "active")->where("offercode", $request->get('promocode'))->where(DB::raw("date_format(start_date, '%Y-%m-%d %H:%i:%s')"), '<=', date('Y-m-d H:i:s', strtotime($todayDate)))->where(DB::raw("date_format(end_date, '%Y-%m-%d %H:%i:%s')"), '>=', date('Y-m-d H:i:s', strtotime($todayDate)))->first();

            $arrOffersSupplier = $offerModel->where("user_id", $request->get('supplierid'))->where("status", "active")->where(DB::raw("date_format(start_date, '%Y-%m-%d %H:%i:%s')"), '<=', date('Y-m-d H:i:s', strtotime($todayDate)))->where(DB::raw("date_format(end_date, '%Y-%m-%d %H:%i:%s')"), '>=', date('Y-m-d H:i:s', strtotime($todayDate)))->pluck("offercode")->toArray();



            if ($offers) {

                $is_useBy = $codeModel->where('user_id', auth()->user()->uuid)->where('offer_id',  $offers->uuid)->get();

                if (count($is_useBy) > 0) {

                    $offer_flag = 1;
                } else {

                    $offer_flag = $offers->uuid;

                    if ($offers->offer_type == 'RENT') {

                        $offerAmount = $offers->offer_value;
                    } else {

                        $offerAmount = $request->get('total_amount') * $offers->offer_value / 100;
                    }

                    $amountAfterDiscount = $request->get('total_amount') - $offerAmount;
                }
            }
        }



        if (count($arrOffersSupplier)) {

            $listr = '<br>Promocode available for you : ';

            $length = strlen($listr);

            foreach ($arrOffersSupplier as $offerCode) {

                if ($offerCode != $request->get('promocode')) {

                    $listr .= $offerCode . ', ';
                }
            }

            if ($length < strlen($listr)) {

                $listr = substr($listr, 0, strlen($listr) - 2);
            } else {

                $listr .= 'None';
            }
        }

        $response['status'] = $offer_flag;

        $response['data'] = $listr;

        $response['offerAmount'] = $offerAmount;

        $response['amountAfterDiscount'] = $amountAfterDiscount;



        return json_encode($response);
    }



    public function postGetSupplier(Request $request, User $userModel)

    {

        if ($request->has('name') && !empty($request->get('name'))) {

            $name = $request->get('name');

            $listUser = $userModel->whereNull('deleted_at')->where([['status', 'Active'], ['role', 'SUPPLIER']]);



            $listUser->whereHas('company', function ($q) use ($name) {

                $q->where('legal_name', 'like', '%' . $name . '%');
            });

            $list = $listUser->get();
        } else {

            $list = [];
        }





        $param = 'supplier';

        $viewFile = 'frontend.helpers.ajax.list';

        if ($request->has('view_file') && !empty($request->get('view_file'))) {

            $viewFile = $request->get('view_file');
        }



        return view($viewFile, compact('list', 'param'));
    }







    function getSubCategoryByCategory(Request $request)

    {

        $parentId = $request->parentId;

        $data = [];

        $subCat = [];



        if (!empty($parentId)) {

            $subCat = Category::where('parent_category_id', $parentId)->get();

            $data['cats'] = $subCat;

            $data['is_parent'] = 1;

            $data['current_cat_detail'] = $current_cat_detail = Category::where('uuid', $parentId)->first();
        } else {

            $subCat = Category::where('parent_category_id', null)->get();

            $data['cats'] = $subCat;

            $data['is_parent'] = 0;

            $data['current_cat_detail'] = Category::where('uuid', $parentId)->first();
        }

        // dd($data);



        $viewFile = 'frontend.helpers.ajax.category_menu';



        return view($viewFile, compact('data'));
    }



    public function postGetCapacity(Request $request, DeliveryVehicleMaster $deliveryVehicleMasterModel)

    {

        if ($request->has('vehicle_capacity_id') && !empty($request->get('vehicle_capacity_id'))) {

            $deliveryVehicleMasterModel->setVehicleCapacityUuid($request->vehicle_capacity_id);

            $vehicle_type = $deliveryVehicleMasterModel->ofVehicleCapacity()

                ->select('capacity', 'vehicle_type')->orderBy('vehicle_type')->pluck('vehicle_type', 'vehicle_type');
        } else {

            $vehicle_type = [];
        }



        $viewFile = 'frontend.helpers.ajax.CapacityDropDown';

        if ($request->has('view_file') && !empty($request->get('view_file'))) {

            $viewFile = $request->get('view_file');
        }

        return view($viewFile, compact('vehicle_type'));
    }





    public function postGetCapacityData(Request $request, DeliveryVehicleMaster $deliveryVehicleMasterModel)

    {

        if ($request->has('transport_type') && !empty($request->get('transport_type')) && $request->get('transport_type') != 'Truck') {

            $vehicle_type = $deliveryVehicleMasterModel->getOtherCapacitySelect($request->get('transport_type'));
        } else {

            $vehicle_type = [];
        }



        $viewFile = 'frontend.helpers.ajax.CapacityDropDown';

        if ($request->has('view_file') && !empty($request->get('view_file'))) {

            $viewFile = $request->get('view_file');
        }

        return view($viewFile, compact('vehicle_type'));
    }







    public function postGetPalletCapacity(Request $request, DeliveryVehicleMaster $deliveryVehicleMasterModel)

    {

        if ($request->has('vehicle_capacity_id') && !empty($request->get('vehicle_capacity_id'))) {

            $capacity_data =   $deliveryVehicleMasterModel->where('vehicle_capacity_id', $request->get('vehicle_capacity_id'))->select('capacity', 'pallet_capacity_standard')->first();

            $capacity['pallet_capacity_standard'] = $capacity_data->pallet_capacity_standard;

            $capacity['capacity'] = $capacity_data->capacity;
        } else {

            $capacity = 0;
        }





        return  json_encode($capacity);
    }



    public function postGetPallet(Request $request, DeliveryVehicleMaster $deliveryVehicleMasterModel)

    {



        if ($request->has('transport_capacity') && !empty($request->get('transport_capacity'))) {



            $capacity_data =   $deliveryVehicleMasterModel->where('vehicle_type', $request->get('transport_capacity'))->select('capacity', 'pallet_capacity_standard')->first();

            $capacity['pallet_capacity_standard'] = $capacity_data->pallet_capacity_standard;

            $capacity['capacity'] = $capacity_data->capacity;
        } else {

            $capacity = 0;
        }





        return  json_encode($capacity);
    }





    public function updateOrderStatus(Request $request, SalesOrder $salesOrderModel)

    {

        // dd($request->all());

        $status = $request->get('order_status');
        $picker_name = $request->get('picker_name');
        $dispatcher_name = $request->get('dispatcher_name');
        $delivery_requested = $request->get('delivery_requested');

        $order_uuid = $request->get('order_id');

        // $salesOrderModel->where('uuid',$order_uuid)->update([
        //     'order_lead_time' => $request->order_lead_time,
        //     'order_lead_time_clock' => $request->order_lead_time_clock
        // ]);

        $orderData = $salesOrderModel->where('uuid', $order_uuid)->first();





        /***********************Notification to chanage status ************************/



        $req = array();

        $req['order_status'] = $status;

        $req['order_uuid'] = $order_uuid;

        $req['user_id'] = auth()->user()->uuid;

        $req['delivery_type'] = $orderData->delivery_type;

        // echo "req :";
        // var_dump($req);
        // exit;
        ChangeOrderStatus::orderStatus($req);



        /***********************Notification to chanage status ************************/



        /* if($status == 'PACKED'){

 

            if($orderData)

            {

                $req_data = array();

                $req_data['order_uuid'] = $orderData->uuid;

                $req_data['supplier_id'] = $orderData->supplier_id;

                $req_data['vendor_id'] = $orderData->user_id;



                if($orderData->delivery_type == 'pickup')

                   {

                    ChangeOrderStatus::orderPickupPacked($req_data);   

                   }

                else

                {

                    ChangeOrderStatus::orderDeliveryPacked($req_data);   

                } 

            }  

        }    

*/
        // if ($request->order_status == 'CHOOSE PICKER') {

        //     $salesOrderModel->where('uuid', $order_uuid)->update([
        //         'picker_id' => $picker_name,
        //     ]);

        //     return redirect()->back();
        // }

        if ($request->order_status == 'CANCELLED') {
            // dd($request->order_status);

            $basketData = \App\Models\Basket::where('order_id', $order_uuid)->select('uuid')->first();
            $basketProduct = \App\Models\BasketProducts::where('basket_id', $basketData->uuid)->get();

            foreach ($basketProduct as $id) {

                $request->merge([
                    'single_qty' => $id['single_qty'],
                    'product_id' => $id['product_id'],
                    'color' => $id['color'],
                    'size' => $id['size'],

                ]);

                $userId = $orderData->user_id;
                $curr_id = $userId;
                $supplier_id = $orderData->supplier_id;
                $salesOrderData = $orderData;
                $shippingMethod = $orderData->delivery_type;

                $basketModel = new Basket;
                $basketId = $basketModel->createNewBasketWithUserId($curr_id)->uuid;

                $data = $this->addToCartAfterSupplierAction($request, $userId, $supplier_id, $salesOrderData, $basketId, $shippingMethod);
                $basketIdNew['basket_id'] = $data['basket_id'];
                $basketIdData = $data;
            }

            $messageData = 'Your Order No. ' . $orderData->order_number . ' has been cancelled by supplier and amount credited to your wallet';
            // sendOrderStatusEmail($messageData,$driver->email,'New Delivery');
            $traderData = User::where('uuid', $userId)->select('email')->first();
            sendOrderStatusEmail($messageData, $traderData->email, 'Order Cancelled');
        }

        if ($request->order_status == 'ACCEPT ORDER') {

            $salesOrderModel->where('uuid', $order_uuid)->update([
                'order_lead_time' => $request->order_lead_time,
                'order_lead_time_clock' => $request->order_lead_time_clock,
                'order_lead_time_to_clock' => $request->order_lead_time_to_clock,
                'picker_id' => $request->picker_name,
                'delivery_requested' => $delivery_requested
            ]);


            $allDrivers = \App\User::where('users.role', 'DRIVER')
                // ->where('users.email','testdriver@mailinator.com')
                ->get();
            $orders = \App\Models\OrderLogisticQueue::whereIn('driver_id', $allDrivers->pluck('uuid'))->where('status', '=', 'OCCUPIED')->select('uuid')->get();
            $driverData = \App\User::whereNotIn('uuid', $orders->pluck('uuid'))
                // ->where('users.email','testdriver@mailinator.com')
                ->where('users.role', 'DRIVER')->get();

            foreach ($driverData as $driver) {
                $messageData = 'New Delivery <a href="' . env('APP_URL') . 'supplier/notification">Order Link<a>';
                // $messageData = 'New Delivery <a href="'.env('APP_URL').'user/sales-orders/'.$order_uuid.'/edit">Order Link<a>';
                sendOrderStatusEmail($messageData, $driver->email, 'New Delivery');
            }

            // return redirect()->back();
        }


        // if ($request->order_status == 'DISPATCH') {

        // }
        if ($request->order_status == 'DELIVERED') {

            $order_uuid = $order_uuid;



            $basketData = \App\Models\Basket::where('order_id', $order_uuid)->select('uuid')->first();
            if ($basketData != null) {

                $basketProduct = \App\Models\BasketProducts::where('basket_id', $basketData->uuid)->get();
                $pickingData = \App\DispatchedDocument::where('order_id', $order_uuid)->get();
                // $pickingData = \App\PickingDocument::where('order_id', $order_uuid)->get();

                $bproductId = $basketProduct->pluck('product_id');
                $pickingId = $pickingData->pluck('product_id');

                $pid = [];

                foreach ($pickingData as $key => $pproduct) {

                    if ($pproduct->single_qty != $pproduct->old_qnty) {
                        $diff = $pproduct->old_qnty - $pproduct->single_qty;
                        $pid[$pproduct->product_id]['single_qty'] =  $diff;
                        $pid[$pproduct->product_id]['product_id'] =  $pproduct->product_id;
                        $pid[$pproduct->product_id]['color'] =  $pproduct->color;
                        $pid[$pproduct->product_id]['size'] =  $pproduct->size;
                    }
                }

                $bproductIdArr = $bproductId->toArray();
                $pickingIdArr = $pickingId->toArray();
                $pendingCartProduct = array_diff($bproductIdArr, $pickingIdArr);
                foreach ($pendingCartProduct as $cardPId) {

                    $basketProduct = \App\Models\BasketProducts::where('basket_id', $basketData->uuid)->where('product_id', $cardPId)->select('single_qty', 'color', 'size')->first();
                    $pid[$cardPId]['single_qty'] = $basketProduct->single_qty;
                    $pid[$cardPId]['product_id'] = $cardPId;
                    $pid[$cardPId]['color'] = $basketProduct->color;
                    $pid[$cardPId]['size'] = $basketProduct->size;
                }

                $orderData = $salesOrderModel->where('uuid', $order_uuid)->first();

                if (count($pickingData) != 0) {

                    if (count($pid) != 0) {
                        $basketIdNew = [];
                        $basketIdData = [];

                        // if($salesOrder->where('uuid',session()->get('sales_order_id'))->first() == 0){

                        $cartAmount = $request->get("order_amount", 0);
                        $discountAmount = $request->get("discount_amount", 0);
                        $offer_id = $request->get("offer_id", 0);
                        $shippingMethod = $request->get("delivery_type", "pickup");
                        // if ($shippingMethod == 'delivery') {
                        $shipmentAmount = $request->get("shipping_amount", 0);
                        // } else {
                        //     $shipmentAmount = 0;
                        // }
                        $itemTax = $request->get("itemTax", 0);
                        $amtPayble = $request->get("amtPayble", 0);
                        $totalAmount = $amtPayble; //+ $shipmentAmount;
                        $supplierId = $request->get("supplier_id", 0);
                        $distance = $request->get("distance", 0);
                        $weight = $request->get("weight", 0);

                        $userId = $orderData->user_id;
                        $curr_id = $userId;
                        $supplier_id = $orderData->supplier_id;

                        $salesOrder = new SalesOrder;
                        $salesOrderData = '';
                        // $salesOrderData = $salesOrder->create([
                        //     'user_id' => $curr_id,
                        //     'supplier_id' => $supplier_id,
                        //     'cart_amount' => $cartAmount,
                        //     'shipment_amount' => $shipmentAmount,
                        //     'discount_amount' => $discountAmount,
                        //     'tax_amount' => $itemTax,
                        //     'final_total' => $totalAmount,
                        //     'order_status' => SalesOrder::ORDERPLACED,
                        //     'payment_status' => "PENDING",
                        //     'delivery_type' => $shippingMethod,
                        //     'total_weight' => $weight,
                        //     'distance' => $distance,
                        // ]);

                        \Log::info('salesOrderData');
                        \Log::info($salesOrderData);
                        // session()->put('sales_order_id',$salesOrderData); 

                        // $basketCheck = $basketModel->where('uuid', session()->get('basket_id',null))->first();
                        // if(session()->get('basket_id',null) == null || $basketCheck == null){
                        \Log::info('ifelse');
                        $basketModel = new Basket;
                        $basketId = $basketModel->createNewBasketWithUserId($curr_id)->uuid;
                        // session(['basket_id' => $basketId]);


                        // } else {
                        //     $basketId = session()->get('basket_id',null);
                        // }

                        // } else {
                        //     $salesOrderData = $salesOrder->where('uuid',session()->get('sales_order_id'))->first();
                        // }

                        foreach ($pid as $id) {


                            $request->merge([
                                'single_qty' => $id['single_qty'],
                                'product_id' => $id['product_id'],
                                'color' => $id['color'],
                                'size' => $id['size'],

                            ]);
                            $data = $this->addToCartAfterSupplierAction($request, $userId, $supplier_id, $salesOrderData, $basketId, $shippingMethod);
                            $basketIdNew['basket_id'] = $data['basket_id'];
                            //    $basketIdNew['order_uuid'] = $data['order_uuid'];
                            $basketIdData = $data;
                        }
                        // added
                        //  ChangeOrderStatus::orderStatus($basketIdData);
                        $basketProductChanges = \App\Models\BasketProducts::where('basket_id', $basketIdNew['basket_id'])->get();

                        $supplierLoopData = [];
                        $supplierLoopData["products"] = [];
                        $total = 0;
                        $totalWeight = 0;
                        $totalProducts = 0;
                        $offerAmount = 0;
                        $totalAvailableProducts = 0;
                        $itemTotalTax = 0;

                        $basketModel = new Basket;
                        $productModel = new Product;
                        $userModel = new User;
                        $supplierItemInventoryModel = new SupplierItemInventory;
                        $salesOrder = new SalesOrder;

                        $walletTransactionModel = new WalletTransactions;
                        $user = new User;
                        $codeModel = new OffercodeUsedby;
                        $offerModel = new OfferDeals;
                        $basketProductModel = new basketProducts;
                        $deliveryVehicleMasterModel = new DeliveryVehicleMaster;
                        $logisticModel = new DeliveryVehicleMaster;
                        $productModel = new Product;
                        $offerModel = new OfferDeals;

                        $changesBasket = $this->getSalesData(
                            $basketProductChanges,
                            $orderData->supplier_id,
                            $orderData->user_id,
                            $supplierLoopData,
                            $total,
                            $totalWeight,
                            $totalProducts,
                            $offerAmount,
                            $totalAvailableProducts,
                            $itemTotalTax,
                            $basketModel,
                            $productModel,
                            $userModel,
                            $supplierItemInventoryModel,
                            $salesOrder,
                            $walletTransactionModel,
                            $user,
                            $codeModel,
                            $offerModel,
                            $basketProductModel,
                            $deliveryVehicleMasterModel,
                            $logisticModel
                        );

                        $pickingDataNew = PickingDocument::leftjoin('sales_orders', 'sales_orders.uuid', '=', 'picking_documents.order_id')
                            ->leftjoin('dispatched_documents', 'dispatched_documents.order_id', '=', 'sales_orders.uuid')
                            ->leftjoin('products', 'products.uuid', '=', 'picking_documents.product_id')
                            ->leftjoin('promotions', 'promotions.product_id', '=', 'products.uuid')
                            ->where('picking_documents.order_id', $order_uuid)
                            ->select(
                                'picking_documents.product_id',
                                'picking_documents.single_qty',
                                'picking_documents.old_qnty',
                                'picking_documents.final_total',
                                'picking_documents.old_final_total',
                                'picking_documents.product_price',
                                'products.name as product_name',
                                'dispatched_documents.single_qty as dispatched_single_qty',
                                'dispatched_documents.old_qnty as dispatched_old_qnty',
                                'dispatched_documents.final_total as dispatched_final_total',
                                'dispatched_documents.old_final_total as dispatched_old_final_total',
                                'dispatched_documents.product_price as dispatched_product_price',
                                'promotions.promotion_price'
                            )
                            ->groupBy('products.uuid')
                            ->get();

                        $pickingDataNew = $pickingDataNew->transform(function ($query) {
                            if ($query->dispatched_single_qty != null) {
                                // $qnty = $query->old_qnty - $query->dispatched_single_qty;
                                // $price = $qnty * $query->product_price;
                                // $refundPrice = $price;
                                $qnty = $query->old_qnty - $query->dispatched_single_qty;
                                if ($query->promotion_price != null) {
                                    $price = $qnty * $query->promotion_price;
                                } else {

                                    $price = $qnty * $query->product_price;
                                }
                                $refundPrice = $price;
                            } else {
                                // $qnty = $query->old_qnty - $query->single_qty;
                                // $price = $qnty * $query->product_price;
                                // $refundPrice = $query->old_final_total - $query->final_total;
                                $qnty = $query->old_qnty - $query->single_qty;

                                if ($query->promotion_price != null) {
                                    $price = $qnty * $query->promotion_price;
                                    $old = $query->old_qnty * $query->promotion_price;
                                    $refundPrice = $price;
                                    // dd($refundPrice, $old, $price);
                                    $query->product_price = $query->promotion_price;
                                } else {
                                    $price = $qnty * $query->product_price;
                                    $refundPrice = $query->old_final_total - $query->final_total;
                                    $old = $price;
                                    $query->product_price = $query->product_price;
                                }
                            }
                            // $query->qnty = $qnty;
                            // $query->price = $price;
                            $query->refundPrice = $refundPrice;
                            return $query;
                        });

                        $refundPrice = $pickingDataNew[0]['refundPrice'];
                        if ($refundPrice < 0) {
                            $pendingPrice = $refundPrice * -1;
                        }

                        if (count($pickingDataNew) != 0) {
                            $messageData = 'Your Wallet has been Credited with R' . $refundPrice;
                            // sendOrderStatusEmail($messageData,$driver->email,'New Delivery');
                            $traderData = User::where('uuid', $orderData->user_id)->select('email')->first();
                            sendOrderStatusEmail($messageData, $traderData->email, 'Order Changes');
                        }
                        //  SalesOrder::where('uuid',$basketIdNew['order_uuid'])->update([
                        //      'cart_amount' => $changesBasket['product_price'],
                        //      'shipment_amount' => $changesBasket['distance_value'],
                        //      'discount_amount' => 0,
                        //      'tax_amount' => $changesBasket['total_tax'],
                        //      'final_total' => $changesBasket['product_price'],
                        //      'total_weight' => $changesBasket['total_weight_unit']['weight'] .''.$changesBasket['total_weight_unit']['unit'],
                        //      'distance' => $changesBasket['distance']['distance'],
                        //  ]);
                    }
                }

                if ($orderData->delivery_type == 'delivery') {

                    // $orderData->logistic_details_id
                    LogisticDetails::where('uuid', $orderData->logistic_details_id)->update(['is_available' => '1']);

                    $deliveryScheduleData = DeliverySchedule::where('order_id', $order_uuid)->first(['id', 'driver_id', 'slot_booked', 'slot_booked_date', 'slot_booked_from_time', 'slot_booked_to_time']);
                    if ($deliveryScheduleData != null) {

                        $userData = User::where('uuid', $orderData->supplier_id)->select('first_name', 'last_name')->first();
                        $driverData = User::where('uuid', $orderData->logistic_id)->select('email')->first();
                        $tradeport = $userData->first_name . ' ' . $userData->last_name;
                        $messageData = 'Order ' . $orderData->order_number . ' is ready for collection at ' . $tradeport . ', Please Collect';
                        // $messageData = 'Order ' . $orderData->order_number . ' is ready for collection at ' . $tradeport . ', Please Collect by ' . $deliveryScheduleData->slot_booked_date . '' . $deliveryScheduleData->slot_booked_from_time . '-' . $deliveryScheduleData->slot_booked_to_time;
                        // $messageData = 'Order '.$orderData->order_number.' is ready for collection at '.$tradeport.', Please Collect by '.$deliveryScheduleData->slot_booked;
                        sendOrderStatusEmail($messageData, $driverData->email, 'Pickup Delivery');
                        $userdevicesModel = new UserDevices;
                        $get_playerid = $userdevicesModel->where('user_id', $orderData->logistic_id)->first();
                        if ($get_playerid) {

                            $message['player_id'] = $get_playerid->player_id;
                            $message['msg'] = $messageData;

                            $message['order_uuid'] = $order_uuid;
                            $notify_msg = $message['msg'];

                            $usernotifyModel = new Notification;
                            $notify = $usernotifyModel->create(['user_id' => $orderData->logistic_id, 'order_id' => $order_uuid, 'notification' => $notify_msg]);

                            $message['notification_uuid'] = $notify->uuid;
                            sendNotification($message);
                        }
                    }
                }


                // $bascketProducts = BasketProducts::where('basket_id', $basketData->uuid)->get();

                // $pickingData = \App\PickingDocument::where('order_id', $order_uuid)->get();
                // if (count($pickingData) == 0) {
                //     foreach ($bascketProducts as $product) {
                //         $salesData = SalesOrder::where('uuid', $order_uuid)->first();
                //         PickingDocument::create([
                //             'order_id' => $order_uuid,
                //             'product_id' => $product->product_id,
                //             'basket_products_id' => '',
                //             'basket_id' => $product->basket_id,
                //             'product_price' => $product->product_price,
                //             'single_qty' => $product->single_qty,
                //             'shipment_amount' => $salesData->shipment_amount,
                //             'discount_amount' => $salesData->discount_amount,
                //             'tax_amount' => $salesData->tax_amount,
                //             'cart_amount' => $product->product_price * $product->single_qty,
                //             'final_total' => ($product->product_price * $product->single_qty - $salesData->discount_amount) + $salesData->shipment_amount + $salesData->tax_amount,
                //             'old_qnty' => $product->single_qty,
                //             'old_final_total' => ($product->product_price * $product->single_qty - $salesData->discount_amount) + $salesData->shipment_amount + $salesData->tax_amount,
                //             'color' => $product->color,
                //             'size' => $product->size,

                //         ]);
                //     }
                // }

                // \Log::info($request->order_status);
                // $pickingData = \App\PickingDocument::where('order_id', $order_uuid)->get();
                // foreach ($pickingData as $picking) {
                //     $dispatchedData = \App\DispatchedDocument::where('order_id', $order_uuid)->get();
                //     if (count($dispatchedData) == 0) {
                //         \App\DispatchedDocument::create([
                //             'order_id' => $picking->order_id,
                //             'product_id' => $picking->product_id,
                //             'basket_id' => $picking->basket_id,
                //             'basket_products_id' => $picking->basket_products_id,
                //             'single_qty' => $picking->single_qty,
                //             'old_qnty' => $picking->old_qnty,
                //             'product_price' => $picking->product_price,
                //             'cart_amount' => $picking->cart_amount,
                //             'shipment_amount' => $picking->shipment_amount,
                //             'discount_amount' => $picking->discount_amount,
                //             'tax_amount' => $picking->tax_amount,
                //             'final_total' => $picking->final_total,
                //             'old_final_total' => $picking->final_total,
                //             'color' => $picking->color,
                //             'size' => $picking->size,
                //             'offer_price' => $picking->offer_price,
                //             'offer_id' => $picking->offer_id
                //         ]);
                //     }
                // }
            }

            // return $request->get('order_status');
            // return redirect()->back();
        }

        if ($request->order_status == 'ORDER COLLECTED') {
            $userData = User::where('uuid', $orderData->supplier_id)->select('first_name', 'last_name')->first();
            $traderData = User::where('uuid', $orderData->user_id)->select('email')->first();
            $tradeport = $userData->first_name . ' ' . $userData->last_name;

            $url = '/user/sales-orders/' . $order_uuid . '/edit';
            $link = '<a href="' . url($url) . '">Order Link</a>';
            $messageData = 'Your order from ' . $tradeport . ' is on the way. Track your Order Here:' . $link;
            \Log::info($messageData);
            \Log::info($traderData->email);
            sendOrderStatusEmail($messageData, $traderData->email, 'Order Collected');
            $userdevicesModel = new UserDevices;
            $get_playerid = $userdevicesModel->where('user_id', $orderData->user_id)->first();
            if ($get_playerid) {

                $message['player_id'] = $get_playerid->player_id;
                $message['msg'] = $messageData;

                $message['order_uuid'] = $order_uuid;
                $notify_msg = $message['msg'];
                $usernotifyModel = new Notification;
                $notify = $usernotifyModel->create(['user_id' => $orderData->user_id, 'order_id' => $order_uuid, 'notification' => $notify_msg]);

                $message['notification_uuid'] = $notify->uuid;
                sendNotification($message);
            }
        }

        if ($request->order_status == 'PACKED') {
            $order_uuid = $order_uuid;

            $salesOrderModel->where('uuid', $order_uuid)->update([
                'dispatcher_id' => $dispatcher_name,
            ]);

            $basketData = \App\Models\Basket::where('order_id', $order_uuid)->select('uuid')->first();
            if ($basketData != null) {

                $bascketProducts =  \App\Models\BasketProducts::where('basket_id', $basketData->uuid)->get();

                $pickingData = \App\PickingDocument::where('order_id', $order_uuid)->get();
                if (count($pickingData) == 0) {
                    foreach ($bascketProducts as $product) {
                        $salesData = SalesOrder::where('uuid', $order_uuid)->first();
                        PickingDocument::create([
                            'order_id' => $order_uuid,
                            'product_id' => $product->product_id,
                            'basket_products_id' => '',
                            'basket_id' => $product->basket_id,
                            'product_price' => $product->product_price,
                            'single_qty' => $product->single_qty,
                            'shipment_amount' => $salesData->shipment_amount,
                            'discount_amount' => $salesData->discount_amount,
                            'tax_amount' => $salesData->tax_amount,
                            'cart_amount' => $product->product_price * $product->single_qty,
                            'final_total' => ($product->product_price * $product->single_qty - $salesData->discount_amount) + $salesData->shipment_amount + $salesData->tax_amount,
                            'old_qnty' => $product->single_qty,
                            'old_final_total' => ($product->product_price * $product->single_qty - $salesData->discount_amount) + $salesData->shipment_amount + $salesData->tax_amount,
                            'color' => $product->color,
                            'size' => $product->size,

                        ]);
                    }
                }

                \Log::info($request->order_status);
                $pickingData = \App\PickingDocument::where('order_id', $order_uuid)->get();
                foreach ($pickingData as $picking) {
                    $dispatchedData = \App\DispatchedDocument::where('order_id', $order_uuid)->get();
                    if (count($dispatchedData) == 0) {
                        \App\DispatchedDocument::create([
                            'order_id' => $picking->order_id,
                            'product_id' => $picking->product_id,
                            'basket_id' => $picking->basket_id,
                            'basket_products_id' => $picking->basket_products_id,
                            'single_qty' => $picking->single_qty,
                            'old_qnty' => $picking->old_qnty,
                            'product_price' => $picking->product_price,
                            'cart_amount' => $picking->cart_amount,
                            'shipment_amount' => $picking->shipment_amount,
                            'discount_amount' => $picking->discount_amount,
                            'tax_amount' => $picking->tax_amount,
                            'final_total' => $picking->final_total,
                            'old_final_total' => $picking->final_total,
                            'color' => $picking->color,
                            'size' => $picking->size,
                            'offer_price' => $picking->offer_price,
                            'offer_id' => $picking->offer_id
                        ]);
                    }
                }
            }
        }

        if ($request->order_status == 'DISPATCHED') {

            $order_uuid = $order_uuid;



            // 
            $pickingData = PickingDocument::leftjoin('sales_orders', 'sales_orders.uuid', '=', 'picking_documents.order_id')
                ->leftjoin('dispatched_documents', 'dispatched_documents.order_id', '=', 'sales_orders.uuid')
                ->leftjoin('products', 'products.uuid', '=', 'picking_documents.product_id')
                ->leftjoin('promotions', 'promotions.product_id', '=', 'products.uuid')
                ->where('picking_documents.order_id', $order_uuid)
                ->select(
                    'picking_documents.product_id',
                    'picking_documents.single_qty',
                    'picking_documents.old_qnty',
                    'picking_documents.final_total',
                    'picking_documents.old_final_total',
                    'picking_documents.product_price',
                    'products.name as product_name',
                    'dispatched_documents.single_qty as dispatched_single_qty',
                    'dispatched_documents.old_qnty as dispatched_old_qnty',
                    'dispatched_documents.final_total as dispatched_final_total',
                    'dispatched_documents.old_final_total as dispatched_old_final_total',
                    'dispatched_documents.product_price as dispatched_product_price',
                    'promotions.promotion_price'
                )
                ->groupBy('products.uuid')
                ->get();

            $pickingData = $pickingData->transform(function ($query) {
                // if ($query->dispatched_single_qty != null) {
                //     // $qnty = $query->old_qnty - $query->dispatched_single_qty;
                //     // $price = $qnty * $query->product_price;
                //     // $refundPrice = $price;
                //     $qnty = $query->old_qnty - $query->dispatched_single_qty;
                //     if ($query->promotion_price != null) {
                //         $price = $qnty * $query->promotion_price;
                //     } else {

                //         $price = $qnty * $query->product_price;
                //     }
                //     $refundPrice = $price;
                // } else {
                //     // $qnty = $query->old_qnty - $query->single_qty;
                //     // $price = $qnty * $query->product_price;
                //     // $refundPrice = $query->old_final_total - $query->final_total;
                //     $qnty = $query->old_qnty - $query->single_qty;

                //     if ($query->promotion_price != null) {
                //         $price = $qnty * $query->promotion_price;
                //         $old = $query->old_qnty * $query->promotion_price;
                //         $refundPrice = $price;
                //         // dd($refundPrice, $old, $price);
                //         $query->product_price = $query->promotion_price;
                //     } else {
                //         $price = $qnty * $query->product_price;
                //         $refundPrice = $query->old_final_total - $query->final_total;
                //         $old = $price;
                //         $query->product_price = $query->product_price;
                //     }
                // }
                // $query->qnty = $qnty;
                // $query->price = $price;
                // $query->refundPrice = $refundPrice;
                // return $query;
                if ($query->dispatched_single_qty != null) {
                    $qnty = $query->old_qnty - $query->dispatched_single_qty;
                    if ($query->promotion_price != null) {
                        $price = $qnty * $query->promotion_price;
                    } else {

                        $price = $qnty * $query->product_price;
                    }
                    $refundPrice = $price;
                    $old = $query->final_total;
                    if ($query->old_qnty == $query->single_qty) {
                        $old = 0;
                        $query->product_price = 0;
                    }
                } else {

                    $qnty = $query->old_qnty - $query->single_qty;

                    if ($query->promotion_price != null) {
                        $price = $qnty * $query->promotion_price;
                        $old = $query->old_qnty * $query->promotion_price;
                        $refundPrice = $price;
                        // dd($refundPrice, $old, $price);
                        $query->product_price = $query->promotion_price;
                    } else {
                        $price = $qnty * $query->product_price;
                        $refundPrice = $query->old_final_total - $query->final_total;
                        // $old = $price;
                        $old = $query->old_qnty * $query->product_price;

                        $query->product_price = $query->product_price;
                    }
                    if ($query->old_qnty == $query->single_qty) {
                        $old = 0;
                        $query->product_price = 0;
                    }
                    // dd('hi', $price, $old);
                }
                // dd($price, $old);
                $query->qnty = $qnty;
                $query->price = $price;
                $query->refundPrice = $refundPrice;
                $query->paid = $old;
                return $query;
            });



            $salesData = SalesOrder::leftjoin('baskets', 'baskets.order_id', 'sales_orders.uuid')
                ->where('sales_orders.uuid', $order_uuid)
                ->select('sales_orders.*', 'baskets.uuid as basket_id')
                ->first();

            $supplier = UserCompany::where('owner_user_id', $salesData->supplier_id)->first();
            if ($supplier != null) {
                $supplierAddress = $this->getAddressNew($supplier);
            } else {
                $supplierAddress = '';
            }
            $trader = UserCompany::where('owner_user_id', $salesData->user_id)->first();
            if ($trader != null) {
                $traderAddress = $this->getAddressNew($trader);
            } else {
                $traderAddress = '';
            }

            $supplierData = User::where('uuid', $salesData->supplier_id)->with('company')->first();

            $pdf = PDF::loadView('supplier.document.creditNoteForPDF', compact('pickingData', 'supplierData', 'supplierAddress', 'traderAddress'))->setPaper('A4', 'portait');
            \Log::info('pdf');


            $pathCreditNote = public_path('creditNote');
            if (!is_dir($pathCreditNote)) {
                mkdir($pathCreditNote);
            }

            $invoiceNo = $salesData->order_number;

            $fileName =  $invoiceNo . '.' . 'pdf';
            \Log::info($fileName);
            \Log::info('pdf---');

            $pdf->save($pathCreditNote . '/' . $fileName);

            $basketModel = new Basket;
            $supplierItemInventoryModel = new SupplierItemInventory;
            $offerModel = new OfferDeals;
            $traderData = User::where('uuid', $orderData->user_id)->select('email')->first();
            $userEmail = $traderData->email;
            $admin_email = env('MAIL_USERNAME');
            \Log::info($admin_email);

            $pdf1 = $this->getDispatchDocument($request, $basketModel, $supplierItemInventoryModel, $offerModel, $order_uuid);
            // 

            $emailContent = 'Your orders are dispatched';
            Mail::send([], [], function ($message) use ($userEmail, $pdf, $pdf1, $emailContent, $admin_email, $fileName) {
                $message->to($userEmail)
                    ->subject('Order Invoice')
                    ->setBody($emailContent, 'text/html'); // for HTML rich messages
                // $message->attachData($pdf->stream(), 'Credit_Note.pdf');
                // $message->attachData($pdf1->stream(), 'Tax_Invoice.pdf');
                // public_path('creditNote').'/'.$fileName;
                // public_path('taxInvoice').'/'.$fileName;
                // $message->attachData($pdf->output(), 'Credit_Note.pdf');
                // $message->attachData($pdf1->output(), 'Tax_Invoice.pdf');
                $message->attachData(url('creditNote') . '/' . $fileName, 'Credit_Note.pdf');
                $message->attachData(url('taxInvoice') . '/' . $fileName, 'Tax_Invoice.pdf');
                $message->from($admin_email, env('APP_NAME'));
            });
            \Log::info('pdf sent');
            dd('hi');

            // return 'DISPATCHED';

            // $path = public_path('pdf/');
            // $fileName =  $post['title'] . '.' . 'pdf' ;
            // $pdf->save($path . '/' . $fileName);
            // return view('supplier.document.creditNote',compact('pickingData','supplierData'));
        }
        return redirect()->back();
        // return $request->get('order_status');
    }

    public function getDispatchDocument(Request $request, $basketModel, $supplierItemInventoryModel, $offerModel, $order_id)
    {

        $pickingData = DispatchedDocument::where('order_id', $order_id)->first();
        $salesData = SalesOrder::leftjoin('baskets', 'baskets.order_id', 'sales_orders.uuid')
            ->where('sales_orders.uuid', $order_id)
            ->select('sales_orders.*', 'baskets.uuid as basket_id')
            ->first();
        $supplierData = User::where('uuid', $salesData->supplier_id)->with('company')->first();

        if ($pickingData != null) {

            $cart_amount = DispatchedDocument::where('order_id', $order_id)->sum('cart_amount');
            $shipment_amount = DispatchedDocument::where('order_id', $order_id)->sum('shipment_amount');
            $final_total = DispatchedDocument::where('order_id', $order_id)->sum('final_total');
            $tax_amount = DispatchedDocument::where('order_id', $order_id)->sum('tax_amount');

            $productTotal = $cart_amount;
            $shippingTotal = $shipment_amount;
            $paybel_amt_input = $final_total;
            $item_tax_input = $tax_amount;
        } else {
            $productTotal = $salesData->cart_amount;

            $shippingTotal = $salesData->shipment_amount;

            $paybel_amt_input = $salesData->final_total;

            $item_tax_input = $salesData->tax_amount;
        }

        $distance = $salesData->distance;

        $weight = $salesData->total_weight;



        $shippingMethod = $salesData->delivery_type;

        $supplierId = $salesData->supplier_id;

        $offerTotal = $salesData->offer_total;

        $offerId = $salesData->offer_id;





        $basketId = $salesData->basket_id;



        $todayDate = Carbon::now()->format('Y-m-d');

        $basket = DispatchedDocument::where('order_id', $order_id)->get();

        // $basket = $basketModel->where('uuid', $basketId)->first();
        // if($orderData->delivery_type != 'pickup'){

        // $basketProductIds = $basket->products()->pluck('product_id')->toArray();

        $basketProducts = [];
        foreach ($basket as $bid) {
            $basketProducts = $bid->products;
        }
        // $basketProducts = $basket->products;

        $productOffer = '';

        $supplierLoopData = [];

        $supplierLoopData["products"] = [];

        $totalWeight = 0;

        $totalProducts = 0;

        $totalAvailableProducts = 0;

        $total = 0;

        $oldFinalTotal = 0;

        $pickingAllData = DispatchedDocument::where('order_id', $order_id)->get();

        if (count($pickingAllData) != 0) {
            $product = [];
            foreach ($pickingAllData as $proIndex => $pickData) {

                $productData = Product::where('uuid', $pickData->product_id)->first();

                $product[$proIndex]['product_id'] = $pickData->product_id;
                $product[$proIndex]['product_name'] = $productData->name;
                $product[$proIndex]['qty'] = $pickData->single_qty;
                $product[$proIndex]['productSinglePrice'] = $pickData->product_price;
                $product[$proIndex]['price'] = $pickData->product_price;
                $product[$proIndex]['totalprice'] = $pickData->cart_amount;
                $product[$proIndex]['single_qty'] = $pickData->single_qty;
                $product[$proIndex]['barcode'] = $productData->barcode;
                $product[$proIndex]['product_code'] = $productData->product_code;
                $product[$proIndex]['Store_item_code'] = $productData->Store_item_code;
                $product[$proIndex]['product_image'] = $productData->product_image;
                $product[$proIndex]['category'] = $productData->category;
                $product[$proIndex]['description'] = $productData->description;
                $product[$proIndex]['units_ordered'] = $pickData->old_qnty;
                $product[$proIndex]['basket_product_id'] = $pickData->basket_products_id;
                $product[$proIndex]['basket_id'] = $pickData->basket_id;
                $product[$proIndex]['order_id'] = $pickData->order_id;
                $product[$proIndex]['exist_picking'] = '1';
                $product[$proIndex]['color'] = $pickData->color;
                $product[$proIndex]['size'] = $pickData->size;

                $product[$proIndex]['row_total'] = '';
                $product[$proIndex]['total_weight'] = '';
                $oldFinalTotal = $pickData->old_final_total;
                $product[$proIndex]['oldFinalTotal'] = $pickData->old_final_total;
            }
            $supplierLoopData['products'] = $product;
            $supplierLoopData['total_available_products'] = '';
        } else {


            foreach ($basketProducts as $proIndex => $basketProduct) {



                $rowTotal = 0;

                $itemWeight = 0;

                if ($basketProduct->product()->exists()) {

                    if ($supplierItemInventoryModel->where('product_id', $basketProduct->product_id)->where('user_id', $supplierId)->count() > 0) {

                        $totalProducts++;

                        $supplierLatestRate = $supplierItemInventoryModel->where('product_id', $basketProduct->product_id)->where('user_id', $supplierId)->orderBy('id', 'DESC')->first();

                        $singlePrice = $supplierLatestRate->single_price;
                        $productSinglePrice = $supplierLatestRate->single_price;

                        $supplierQty = $supplierLatestRate->single;

                        if ($offerModel->where('user_id', $supplierId)->where('products_id', $basketProduct->product_id)->whereDate('end_date', '>=', $todayDate)->count() > 0) {
                            $productOffer = $offerModel->where('user_id', $supplierId)->where('products_id', $basketProduct->product_id)->whereDate('end_date', '>=', $todayDate)->orderBy('id', 'DESC')->first();
                            if ($productOffer->offer_type == 'RENT') {
                                $singlePrice = $singlePrice - ($productOffer->offer_value);
                            } else {
                                $singlePrice = $singlePrice - (($singlePrice * ($productOffer->offer_value)) / 100);
                            }
                        }


                        $itemWeight = 0;

                        $totalAvailableProducts++;



                        $productName = $supplierLatestRate->product->name;
                        $productId = $supplierLatestRate->product->uuid;

                        $supplierProductLoopData["product_id"] = $productId;
                        $supplierProductLoopData["product_name"] = $productName;



                        if ($basketProduct->single_qty > 0) {

                            if ($supplierQty < $basketProduct->single_qty) {

                                $basketProduct->single_qty = $supplierQty;
                            }

                            $single_qty_new = $basketProduct->getOriginal()['single_qty'];
                            if ($pickingData != null) {

                                $supplierProductLoopData["qty"] = $pickingData->single_qty;
                            } else {
                                $supplierProductLoopData["qty"] = $single_qty_new;
                            }

                            $supplierProductLoopData["productSinglePrice"] = $productSinglePrice;

                            $supplierProductLoopData["price"] = $singlePrice;

                            if ($pickingData != null) {

                                $supplierProductLoopData["totalprice"] = $pickingData->final_total;
                            } else {
                                $supplierProductLoopData["totalprice"] = ($single_qty_new * $singlePrice);
                            }

                            // new added
                            $supplierProductLoopData["single_qty"] = $supplierProductLoopData["qty"];
                            //  $supplierProductLoopData["single_qty"] = $basketProduct->single_qty;


                            $productData = Product::where('uuid', $basketProduct->product_id)->first();

                            $supplierProductLoopData["barcode"] = $productData->barcode;
                            $supplierProductLoopData["product_code"] = $productData->product_code;
                            $supplierProductLoopData["Store_item_code"] = $productData->store_item_code;
                            $supplierProductLoopData["product_image"] = $productData->base_image;
                            $supplierProductLoopData["price"] = $productData->base_price;
                            $supplierProductLoopData["category"] = $productData->category;
                            $supplierProductLoopData["description"] = $productData->description;
                            $supplierProductLoopData["units_ordered"] = $single_qty_new;

                            $supplierProductLoopData["basket_product_id"] = $basketProduct->uuid;
                            $supplierProductLoopData["basket_id"] = $basketProduct->basket_id;
                            $supplierProductLoopData["product_id"] = $basketProduct->product_id;
                            $supplierProductLoopData["order_id"] = $order_id;
                            $supplierProductLoopData["color"] = $basketProduct->color;
                            $supplierProductLoopData["size"] = $basketProduct->size;
                            if ($pickingData != null) {

                                $supplierProductLoopData["exist_picking"] = 1;
                            } else {
                                $supplierProductLoopData["exist_picking"] = 0;
                            }

                            // 

                            $itemWeight += $basketProduct->product->getCalculatedWeight("single", $single_qty_new);

                            $rowTotal += ($single_qty_new * $singlePrice);
                            $oldFinalTotal = 0;
                            $product[$proIndex]['oldFinalTotal'] = 0;
                        }

                        $totalWeight += $itemWeight;

                        $total += $rowTotal;

                        $supplierProductLoopData["row_total"] = $rowTotal;

                        $supplierProductLoopData["total_weight"] = $totalWeight;

                        $supplierLoopData["products"][$proIndex] = $supplierProductLoopData;

                        $supplierLoopData["total_available_products"] = $totalAvailableProducts;
                    }
                }
            }
        }


        $walletBalance = auth()->user()->wallet_balance;

        $pageTitle = "Dispatched Docuement";

        $bodyClass = ['about-us'];



        // Session::push('requestData', $request->all());

        if ($oldFinalTotal == 0) {
            $creditNote = 0;
        } else {
            $creditNote = $oldFinalTotal - $paybel_amt_input;
        }

        $pdf = PDF::loadView('supplier.document.dispatchedDocumentForPDF', compact('pageTitle', 'bodyClass', 'supplierLoopData', 'productTotal', 'shippingMethod', 'shippingTotal', 'walletBalance', 'supplierId', 'offerTotal', 'offerId', 'paybel_amt_input', 'item_tax_input', 'distance', 'weight', 'supplierData', 'productOffer', 'creditNote'))->setPaper('A4', 'landscape');

        \Log::info('pdf1');
        $pathTaxInvoice = public_path('taxInvoice');
        if (!is_dir($pathTaxInvoice)) {
            mkdir($pathTaxInvoice);
        }

        $invoiceNo = $salesData->order_number;

        $fileName =  $invoiceNo . '.' . 'pdf';
        \Log::info($fileName);
        \Log::info('pdf1--');
        $pdf->save($pathTaxInvoice . '/' . $fileName);

        return $pdf;
        // return view('supplier.document.dispatchedDocument', compact('pageTitle', 'bodyClass','supplierLoopData', 'productTotal', 'shippingMethod', 'shippingTotal', 'walletBalance', 'supplierId','offerTotal','offerId', 'paybel_amt_input', 'item_tax_input', 'distance', 'weight', 'supplierData', 'productOffer','creditNote'));
    }

    public function test(Request $request)
    {

        //     $basketModel = new Basket; 
        //     $supplierItemInventoryModel = new SupplierItemInventory; 
        //     $offerModel = new OfferDeals;
        //     // $traderData = User::where('uuid',$orderData->user_id)->select('email')->first();
        //     // $userEmail = $traderData->email;
        //     $admin_email = env('MAIL_USERNAME');

        //     $pdf1 = $this->getDispatchDocument($request,$basketModel,$supplierItemInventoryModel,$offerModel,'600004e2-e068-4410-aaa9-1eb405406257');

        //    dd($pdf1);
        // $pickingData = \App\PickingDocument::where('order_id','0bd15705-113a-4708-a085-7b0f948a72eb')->get();
        // foreach($pickingData as $picking){
        // // dd($picking);

        //     $dispatchedData = \App\DispatchedDocument::create([
        //         'order_id' => $picking->order_id,
        //         'product_id' => $picking->product_id,
        //         'basket_id' => $picking->basket_id,
        //         'basket_products_id' => $picking->basket_products_id,
        //         'single_qty' => $picking->single_qty,
        //         'old_qnty' => $picking->single_qty,
        //         'product_price' => $picking->product_price,
        //         'cart_amount' => $picking->cart_amount,
        //         'shipment_amount' => $picking->shipment_amount,
        //         'discount_amount' => $picking->discount_amount,
        //         'tax_amount' => $picking->tax_amount,
        //         'final_total' => $picking->final_total,
        //         'old_final_total' => $picking->final_total,
        //     ]);

        // }
        // dd('hi');

        // $userId = '31cf4938-064e-48ed-b387-d300ac22cbb9';
        // $request->merge([
        //     'single_qty' => 1,
        //     'product_id' => 'cb29ed13-47a5-4049-b942-690bb9557163'
        // ]);
        // $this->addToCartAfterSupplierAction($request,$userId);
        // $order_uuid = '3253f1d1-1697-42c1-b4f2-193a833bef68';

        //     $basketData = \App\Models\Basket::where('order_id', $order_uuid)->select('uuid')->first();
        //     $basketProduct = \App\Models\BasketProducts::where('basket_id',$basketData->uuid)->get();
        //     $pickingData = \App\PickingDocument::where('order_id',$order_uuid)->get();

        //     $bproductId = $basketProduct->pluck('product_id');
        //     $pickingId = $pickingData->pluck('product_id');

        //     $pid = [];

        //     foreach($pickingData as $key => $pproduct){
        // \Log::info('PACKED IN LOOP');

        //         if($pproduct->single_qty != $pproduct->old_qnty){
        //             $diff = $pproduct->old_qnty - $pproduct->single_qty;
        //             $pid[$pproduct->product_id]['single_qty'] =  $diff;
        //             $pid[$pproduct->product_id]['product_id'] =  $pproduct->product_id;
        //         }
        //     }

        //     $bproductIdArr = $bproductId->toArray();
        //     $pickingIdArr = $pickingId->toArray();
        //     $pendingCartProduct = array_diff($bproductIdArr, $pickingIdArr);
        //     foreach($pendingCartProduct as $cardPId){
        // \Log::info('PACKED IN cardPId LOOP');

        //         $basketProduct = \App\Models\BasketProducts::where('basket_id',$basketData->uuid)->where('product_id',$cardPId)->select('single_qty')->first();
        //         $pid[$cardPId]['single_qty'] = $basketProduct->single_qty;
        //         $pid[$cardPId]['product_id'] = $cardPId;
        //     }
        //     $basketProduct = \App\Models\BasketProducts::where('basket_id','ab6b9683-91df-4813-aef9-0da01cc343dc')->get();

        //    $allData = $this->getSalesData( $basketProduct);
        //    dd($allData);
    }

    public function getSalesData(
        $arrBasketProducts,
        $supplier_id,
        $vendor_id,
        $supplierLoopData,
        $total,
        $totalWeight,
        $totalProducts,
        $offerAmount,
        $totalAvailableProducts,
        $itemTotalTax,
        $basketModel,
        $productModel,
        $userModel,
        $supplierItemInventoryModel,
        $salesOrder,
        $walletTransactionModel,
        $user,
        $codeModel,
        $offerModel,
        $basketProductModel,
        $deliveryVehicleMasterModel,
        $logisticModel
    ) {




        $supplier = $user->where('uuid', $supplier_id)->first();
        // $supplierLoopData["supplier"]["display_name"] = $supplier->company()->exists() ? $supplier->company->trading_name : $supplier->name;

        // $supplierLoopData["supplier"]["uuid"] = $supplier->uuid;


        $todayDate = Carbon::now()->format('Y-m-d');
        $currentUser = User::where('uuid', $vendor_id)->first();

        \Log::info('innnnnnnnnn');
        \Log::info($arrBasketProducts);
        \Log::info('innnnnnnnnn');

        foreach ($arrBasketProducts as $proIndex => $basketProduct) {

            $totalProducts++;

            $rowTotal = 0;

            if (!empty($basketProduct->single_qty) && $basketProduct->single_qty > 0) {

                $supplierItemInventoryDataModel = $supplierItemInventoryModel->where('product_id', $basketProduct->product_id)->where('user_id', $supplier->uuid);

                if ($basketProduct->single_qty > 0) {

                    $supplierItemInventoryModel->where('single', '>', 0);
                }

                $supplierItemInventoryDataModel->whereNotNull('single_price')->where('single_price', '>', 0);
                $supplierLatestRate = $supplierItemInventoryDataModel->first();


                //$supplierLatestRate && !empty($supplierLatestRate->single_price) && $supplierLatestRate->single_price > 0

                if ($supplierLatestRate && !empty($supplierLatestRate->single_price) && $supplierLatestRate->single_price > 0) {

                    //$supplierLatestRate = $supplierItemInventoryModel->where('product_id', $basketProduct->product_id)->where('user_id', $supplier->uuid)->first();

                    $singlePrice = $supplierLatestRate->single_price;

                    if ($offerModel->where('user_id', $supplier->uuid)->where('products_id', $basketProduct->product_id)->where(DB::raw("date_format(start_date, '%Y-%m-%d %H:%i:%s')"), '<=', date('Y-m-d H:i:s', strtotime($todayDate)))->where(DB::raw("date_format(end_date, '%Y-%m-%d %H:%i:%s')"), '>=', date('Y-m-d H:i:s', strtotime($todayDate)))->count() > 0) {

                        $productOffer = $offerModel->where('user_id', $supplier->uuid)->where('products_id', $basketProduct->product_id)->where(DB::raw("date_format(start_date, '%Y-%m-%d %H:%i:%s')"), '<=', date('Y-m-d H:i:s', strtotime($todayDate)))->where(DB::raw("date_format(end_date, '%Y-%m-%d %H:%i:%s')"), '>=', date('Y-m-d H:i:s', strtotime($todayDate)))->orderBy('id', 'DESC')->first();

                        if ($productOffer->offer_type == 'RENT') {

                            $singlePrice = $singlePrice - ($productOffer->offer_value);
                            // dd($singlePrice);

                        } else {
                            $singlePrice = $singlePrice - (($singlePrice * ($productOffer->offer_value)) / 100);
                            // dd($singlePrice);
                        }

                        // dd($productOffer);

                    }
                    // dd('hii');

                    /* $shrinkPrice = $supplierLatestRate->shrink_price;

                            $casePrice = $supplierLatestRate->case_price;

                            $palletPrice = $supplierLatestRate->pallet_price; */

                    $itemWeight = 0;

                    $productitemTax = 0;

                    $totalAvailableProducts++;

                    // $itemMinValue = 0;//added


                    $productName = $supplierLatestRate->product->name;

                    $supplierProductLoopData["product_name"] = $productName;

                    $supplierProductLoopData["stock"] = [];

                    if ($basketProduct->single_qty > 0) {

                        $supplierProductLoopData["stock"]["single"]["qty"] = $basketProduct->single_qty;

                        $supplierProductLoopData["stock"]["single"]["price"] = $singlePrice;

                        $itemWeight = $basketProduct->product->getCalculatedWeight("single", $basketProduct->single_qty);

                        $rowTotal = ($basketProduct->single_qty * $singlePrice);

                        $productitemTax = $basketProduct->product->getCalculatedTax("single", $basketProduct->single_qty, $singlePrice);
                    }



                    $totalWeight += $itemWeight;

                    $itemTotalTax += $productitemTax;

                    $total += $rowTotal;

                    $supplierProductLoopData["row_total"] = $rowTotal;




                    $supplierProductLoopData["total_weight"] = $totalWeight;

                    $supplierLoopData["products"][$proIndex] = $supplierProductLoopData;

                    $supplierLoopData["total_available_products"] = $totalAvailableProducts;
                }
            }
        }

        $supplierLoopData["product_price"] = $total;
        $supplierLoopData["total_tax"] = $itemTotalTax;

        $todayDate = Carbon::now()->format('Y-m-d');

        if ($offerModel->where('user_id', $supplier->uuid)->whereDate('end_date', '>=', $todayDate)->count() > 0) {



            $offerLatestRate = $offerModel->where('user_id', $supplier->uuid)->whereDate('end_date', '>=', $todayDate)->orderBy('id', 'DESC')->first();

            if ($offerLatestRate->offer_type == 'RENT') {

                $supplierLoopData["offertype"] = $offerLatestRate->offer_type;

                $supplierLoopData["offervalue"] = $offerLatestRate->offer_value;
            } else {

                $supplierLoopData["offertype"] = $offerLatestRate->offer_type;

                $supplierLoopData["offervalue"] = $offerLatestRate->offer_value;
            }
        }

        $supplierLoopData["total_weight_unit"] = $supplier->kgToUnit($totalWeight);

        $distance = $supplier->getDrivingDistance($currentUser->latitude, $currentUser->longitude, $supplier->latitude, $supplier->longitude);
        // print_r($currentUser->latitude.'  '.$currentUser->longitude.'  '.$supplier->longitude.'  '.$supplier->longitude);exit();
        $distanceValue = isset($distance['distance']) ? $distance['distance'] : 0;

        $supplierLoopData["total"] = $distance;

        $supplierLoopData["distance"] = $distance;

        $supplierLoopData["distance_value"] = $distanceValue;

        $supplierLoopData["delivery_details"] = $deliveryVehicleMasterModel->getDeliveryPrice($totalWeight, $distanceValue);



        return $supplierLoopData;
    }

    public function addToCartAfterSupplierAction($request, $userId, $supplier_id, $salesOrderData, $basketId, $shippingMethod)
    {

        $basketModel = new Basket;
        $productModel = new Product;
        $userModel = new User;
        $inventary = new SupplierItemInventory;
        $salesOrder = new SalesOrder;

        $walletTransactionModel = new WalletTransactions;
        $user = new User;
        $codeModel = new OffercodeUsedby;
        $offerModel = new OfferDeals;
        $basketProductModel = new basketProducts;

        \Log::info('PACKED IN id LOOP start');

        $route_doc = 'supplier.document.create';

        $curr_id = $userId;

        $user = $userModel->where('uuid', $curr_id)->first();
        $userEmail = $user->email;

        $route_err = route($route_doc, $curr_id);

        $supplierId = $supplier_id;



        // $cartAmount = $request->get("order_amount", 0);
        // $discountAmount = $request->get("discount_amount", 0);
        // $offer_id = $request->get("offer_id", 0);
        // $shippingMethod = $request->get("delivery_type", "pickup");
        // if($shippingMethod == 'delivery'){
        //     $shipmentAmount = $request->get("shipping_amount", 0);
        // }else{
        //     $shipmentAmount = 0;
        // }
        // $itemTax = $request->get("itemTax", 0);
        // $amtPayble = $request->get("amtPayble", 0);
        // $totalAmount = $amtPayble; //+ $shipmentAmount;
        // $supplierId = $request->get("supplier_id", 0);
        // $distance = $request->get("distance", 0);
        // $weight = $request->get("weight", 0);

        /***Sales Entry***/

        //      if($salesOrder->where('uuid',session()->get('sales_order_id'))->first() == 0){

        //         $salesOrderData = $salesOrder->create([

        //             'user_id' => $curr_id,

        //             'supplier_id' => $supplier_id,

        // //           'logistic_id',

        //             'cart_amount' => $cartAmount,

        //             'shipment_amount' => $shipmentAmount,

        //             'discount_amount' => $discountAmount,

        //             'tax_amount' => $itemTax,

        //             'final_total' => $totalAmount,

        //             'order_status' => SalesOrder::ORDERPLACED,
        //             // 'order_status' => "PLACED",

        //             'payment_status' => "PENDING",

        //             'delivery_type' => $shippingMethod,

        //             'total_weight' => $weight,

        //             'distance' => $distance,

        //         ]);
        //         session()->put('sales_order_id',$salesOrderData); 
        //     } else {
        //         $salesOrderData = $salesOrder->where('uuid',session()->get('sales_order_id'))->first();
        //     }


        // 
        // $basketCheck = $basketModel->where('uuid', session()->get('basket_id',null))->first();
        // if(session()->get('basket_id',null) == null || $basketCheck == null){
        //     \Log::info('ifelse');

        //     $basketId = $basketModel->createNewBasketWithUserId($curr_id)->uuid;
        //     session(['basket_id' => $basketId]);


        // } else {
        //     $basketId = session()->get('basket_id',null);
        // }
        //  
        \Log::info("basketId");
        \Log::info($basketId);
        \Log::info("salesOrderData");
        \Log::info($salesOrderData);
        // session(['basket_id' => $basketId]);



        $basket = $basketModel->where('uuid', $basketId)->first();
        \Log::info("basket");
        \Log::info($basket);

        $productId = $request->get('product_id');

        if ($basket->products()->where('product_id', $productId)->count() == 0) {
            // session(['basket_id' => $basketId]);

            \Log::info('if2');

            $basket->products()->create($request->all(['product_id', 'single_qty', 'shrink_qty', 'case_qty', 'pallet_qty', 'color', 'size'])); // attribute  single_qty  model

            $message = "success.checkout|productAddedToCart";
        } else {
            \Log::info('else2');

            $basket->products()->where('product_id', $productId)->update($request->all(['product_id', 'single_qty', 'shrink_qty', 'case_qty', 'pallet_qty', 'color', 'size']));

            $message = "success.checkout|productUpdatedInCart";
        }

        $product = $productModel->where('uuid', $productId)->first();

        $basket = $basketModel->where('uuid', $basketId)->first();

        $products = $basket->products;

        // echo "<pre>";
        // print_r($basketId);die();   

        // foreach ($products as $pro) {

        //     if( $inventary->where('product_id', $pro->product_id)->where('user_id', $supplierId)->count() >0 ){

        //         $inventary->where('product_id', $pro->product_id)->where('user_id', $supplierId)->decrement('single', $pro->single_qty); 

        //     }

        //     else

        //     {

        //     $basket = $basketModel->where('uuid', $basketId)->first();

        //      $basket->products()->where('product_id', $pro->product_id)->delete();      

        //     }



        // }
        $todayDate = Carbon::now()->format('Y-m-d');

        foreach ($products as $pro) {

            $productOffer = $offerModel->where('user_id', $supplierId)->where('products_id', $pro['product_id'])->whereDate('end_date', '>=', $todayDate)->orderBy('id', 'DESC')->first();

            $supplierInventory = $inventary->where('product_id', $pro['product_id'])->where('user_id', $supplierId)->first();

            if (isset($productOffer)) {

                // DB::enableQueryLog(); // Enable query log
                $basketProductModel->where('basket_id', $basketId)->where('product_id', $productOffer->products_id)->update(['product_price' => $supplierInventory->single_price, 'offer_id' => $productOffer->uuid]);
                // dd(DB::getQueryLog()); // Show results of log
                // print_r('po');echo "<br>";
            } else {

                // DB::enableQueryLog(); // Enable query log
                $basketProductModel->where('basket_id', $basketId)->where('product_id', $pro['product_id'])->update(['product_price' => $supplierInventory->single_price]);
                // dd(DB::getQueryLog()); // Show results of log
                // print_r('pi');
            }
        }

        // $basket = $basketModel->where("uuid", $basketId)->update(["order_id" => session()->get('sales_order_id'),'is_modify' => '1']);
        $basket = $basketModel->where("uuid", $basketId)->update(['is_modify' => '1']);
        // $basket = $basketModel->where("uuid", $basketId)->update(["order_id" => $salesOrderData->uuid,'is_modify' => '1']);

        \Log::info('PACKED IN id LOOP start 123');

        $req_data['order_status'] = SalesOrder::ORDERPLACED;
        // $req_data['order_uuid'] = $salesOrderData->uuid;  
        $req_data['user_id'] = $supplier_id;
        $req_data['delivery_type'] = $shippingMethod;
        $req_data['basket_id'] = $basketId;

        // $basketModel->where('order_id',$order_id)->update([
        //     'is_modify' => '1'
        // ]);
        return $req_data;
        // return redirect()->back()->with(['status' => 'success', 'message' => trans($message, ['product_name' => $product->name,'link' => '<a href="'.route("checkout.cart") .'">Cart</a>' ])]);

    }


    public function getAddress(Request $request, User $user) //Company user same address of selected company

    {

        $response['latitude'] = '';

        $response['longitude'] = '';

        if ($request->has('company_uuid') && !empty($request->get('company_uuid'))) {

            $data =  $user->where('uuid', $request->get('company_uuid'))->select('latitude', 'longitude')->first();

            $response['latitude'] = $data->latitude;

            $response['longitude'] = $data->longitude;
        }



        return json_encode($response);
    }

    public function getAddressNew($data)
    {

        // {{ $supplierData->company->trading_name }}<br>
        //                         {{ $supplierData->company->address1 }}, {{ $supplierData->company->address2 }},
        //                         {{ $supplierData->company->city_name }}, {{ $supplierData->company->state_name }},
        //                         {{ $supplierData->company->country_name }},<br>
        //                         {{ $supplierData->company->zipcode_name }}, {{ $supplierData->company->zipcode_code }}
        $trading_name = (isset($data->trading_name) && $data->trading_name != null) ? $data->trading_name : '';
        $zipcode_name = (isset($data->zipcode_name) && $data->zipcode_name != null) ? $data->zipcode_name : '';
        $zipcode_code = (isset($data->zipcode_code) && $data->zipcode_code != null) ? $data->zipcode_code : '';


        $address1 = (isset($data->address1) && $data->address1 != null) ? $data->address1 : '';
        $address2 = (isset($data->address2) && $data->address2 != null) ? $data->address2 : '';
        $country = (isset($data->country) && $data->country != null) ? $data->country->country_name : '';
        $state = (isset($data->state) && $data->state != null) ? $data->state->state_name : '';
        $city = (isset($data->city) && $data->city != null) ? $data->city->city_name : '';
        $zipcode = (isset($data->zipcode) && $data->zipcode != null) ? $data->zipcode->zipcode : '';

        $address =  $trading_name . '<br>' . $address1 . ', ' . $address2 . '<br>' . $city . ', ' . $state . '<br>' . $country . '<br>' . $zipcode_name . ', ' . $zipcode_code;
        // $address = $address1 . ' ' . $address2 . ' ' . $city  . ' ' . $state . ' ' . $country . ' ' . $zipcode;
        return $address;
    }

    public function saveNewBankBranch(Request  $request, BankBranch $bank_branch)

    {







        $validator = Validator::make($request->all(), [

            'branch_name' => 'required',

            'branch_code' => 'required',

            'swift_code' => 'required',

            'bank_master_id' => 'required',

            'address1' => 'required',

            'zipcode_id' => 'required',

            'city_id' => 'required',

            'state_id' => 'required',

            'country_id' => 'required',

        ]);

        /* if ($validator->fails())  {

                return \Response::json(array("errors" => $validator->getMessageBag()->toArray()), 422);

            }*/



        if ($validator->passes()) {



            $bankBranchModel = $bank_branch->create($request->all());



            return response()->json(['success' => 'Added new records.', 'uuid' => $bankBranchModel->uuid]);
        } else {

            return response()->json(['errors' => $validator->errors()->all()]);
        }
    }





    public function saveNewBank(Request  $request, BankMaster $bank)

    {



        $validator = Validator::make($request->all(), [

            'name' => 'required',

            'short_name' => 'required'

        ]);



        if ($validator->passes()) {



            $bankModel = $bank->create($request->all());



            return response()->json(['success' => 'Added new records.', 'uuid' => $bankModel->uuid]);
        } else {

            return response()->json(['errors' => $validator->errors()->all()]);
        }
    }





    public function updatedBankGrid(Request $request, UserBankDetails $user_bank_detail, BankBranch $bankBranchModel, BankMaster $bankMasterModel)
    {



        $selectId = $request->Id;

        $url = $request->url();



        $bankGridData = $this->bankBranchGrid($request, $bankBranchModel, $bankMasterModel, $url);

        $dataGridTitle = $bankGridData['dataGridTitle'];

        $dataGridSearch = $bankGridData['dataGridSearch'];

        $dataGridPagination = $bankGridData['dataGridPagination'];

        $data = $bankGridData['data'];

        $user_bank_detail = $user_bank_detail->ofUser()->first();

        //  if ($request->ajax()) {

        return view('frontend.bankBranch.grid', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'user_bank_detail', 'selectId'));

        // }

    }
}
