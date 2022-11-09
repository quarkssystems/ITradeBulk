<?php



namespace App\Http\Controllers;



use App\Http\Requests\FrontCheckoutAddToCartRequest;

use App\Http\Requests\FrontCheckoutStoreLocationRequest;

use App\Models\Basket;

use App\Models\LogisticDetails;

use App\Models\DeliveryVehicleMaster;

use App\Models\LocationZipcode;

use App\Models\Product;

use App\Models\EmailTemplate;

use App\Models\SupplierItemInventory;

use App\User;

use App\Models\OfferDeals;

use Illuminate\Http\Request;

use App\Models\UserDocument;
use App\Models\SalesOrder;

use Carbon\Carbon;

use Illuminate\Support\Facades\Mail;

use DB;
use Auth;
use App\Models\BasketProducts;
use App\Models\Promotion;

class FrontCheckoutController extends Controller

{

    public function storeLocation(FrontCheckoutStoreLocationRequest $request, LocationZipcode $locationZipcodeModel)

    {

        session(['checkout_location' => $request->get('location')]);

        $location = $locationZipcodeModel->where('uuid', $request->get('location'))->count() > 0 ? $locationZipcodeModel->where('uuid', $request->get('location'))->first() : false;

        session(['checkout_location_details' => $location]);

        return redirect()->route('products');
    }



    public function addToCart(Request $request, Basket $basketModel, Product $productModel, User $userModel)

    {


        $userdoc = new UserDocument;

        $route_doc = 'supplier.document.create';

        $curr_id = auth()->user()->uuid;

        $user = $userModel->where('uuid', $curr_id)->first();
        $userEmail = $user->email;

        $route_err = route($route_doc, $curr_id);

        $phone = '+88 0123 4567 890, +88 0123 4567 999';
        $facebook_url = 'https://www.facebook.com/';
        $instagram_url = 'https://www.instagram.com/';
        $twitter_url = 'https://www.twitter.com/';
        $pinterest_url = 'https://www.pinterest.com/';

        if (!$userdoc->getDocumentStatus()) {

            $message = "We would like to inform you that your KYC is not completed. In order to add order please complete your KYC <a href = " . $route_err . " >Upload Documents</a>";

            $email = EmailTemplate::where('name', '=', 'trader_KYC_pending_notification')->first();

            if (isset($email)) {
                $email->description = str_replace('[CUSTOMER_NAME]', $user['first_name'] . ' ' . $user['last_name'], $email->description);
                $email->description = str_replace('[SITE_NAME]', env('WEBSITE'), $email->description);
                $email->description = str_replace('[PHONE]', $phone, $email->description);
                $email->description = str_replace('[FACEBOOK_URL]', $facebook_url, $email->description);
                $email->description = str_replace('[TWITTER_URL]', $twitter_url, $email->description);
                $email->description = str_replace('[INSTAGRAM_URL]', $instagram_url, $email->description);
                $email->description = str_replace('[PINTEREST_URL]', $pinterest_url, $email->description);
                $email->description = str_replace('[LOGO]', asset('assets/images/logo.png'), $email->description);
            }

            $emailContent = $email->description;

            Mail::send([], [], function ($message) use ($userEmail, $emailContent) {
                $message->to($userEmail)
                    ->subject('Trader - KYC Pending Notification')
                    ->setBody($emailContent, 'text/html'); // for HTML rich messages
            });


            return redirect()->back()->withErrors(['status' => 'errors', 'message' => trans($message)]);
        }



        $basketId = session()->get('basket_id', null);
        // dd($basketId);
        if (is_null($basketId)) {

            $basket_data = $basketModel->getBasket();

            if ($basket_data->first()) {

                $basketId = $basket_data['0'];
            } else {

                $basketId = $basketModel->createNewBasket()->uuid;
            }
        }

        session(['basket_id' => $basketId]);



        $basket = $basketModel->where('uuid', $basketId)->first();

        $productId = $request->get('product_id');
        if ($basket != null) {


            if ($basket->products()->where('product_id', $productId)->count() == 0) {

                $basket->products()->create($request->all(['product_id', 'single_qty', 'shrink_qty', 'case_qty', 'pallet_qty', 'color', 'size'])); // attribute  single_qty  model

                $message = "success.checkout|productAddedToCart";
            } else {

                $basket->products()->where('product_id', $productId)->update($request->all(['product_id', 'single_qty', 'shrink_qty', 'case_qty', 'pallet_qty', 'color', 'size']));

                $message = "success.checkout|productUpdatedInCart";
            }
        } else {
            $message = '';
        }

        $product = $productModel->where('uuid', $productId)->first();





        return redirect()->back()->with(['status' => 'success', 'message' => trans($message, ['product_name' => $product->name, 'link' => '<a href="' . route("checkout.cart") . '">Cart</a>'])]);
    }


    public function newPendingCartFromSupplier(Request $request)
    {

        $order_uuid = '8ee67dbf-f222-478a-947d-38042aa2cdf4';

        $basketData = Basket::where('order_id', $order_uuid)->select('uuid')->first();
        $basketProduct = \App\Models\BasketProducts::where('basket_id', $basketData->uuid)->get();
        $pickingData = \App\PickingDocument::where('order_id', $order_uuid)->get();

        $bproductId = $basketProduct->pluck('product_id');
        $pickingId = $pickingData->pluck('product_id');

        $pid = [];

        foreach ($pickingData as $key => $pproduct) {
            if ($pproduct->single_qty != $pproduct->old_qnty) {
                $diff = $pproduct->old_qnty - $pproduct->single_qty;
                $pid[$pproduct->product_id]['single_qty'] =  $diff;
                $pid[$pproduct->product_id]['product_id'] =  $pproduct->product_id;
            }
        }

        $bproductIdArr = $bproductId->toArray();
        $pickingIdArr = $pickingId->toArray();
        $pendingCartProduct = array_diff($bproductIdArr, $pickingIdArr);
        foreach ($pendingCartProduct as $cardPId) {
            $basketProduct = \App\Models\BasketProducts::where('basket_id', $basketData->uuid)->where('product_id', $cardPId)->select('single_qty')->first();
            $pid[$cardPId]['single_qty'] = $basketProduct->single_qty;
            $pid[$cardPId]['product_id'] = $cardPId;
        }

        // dd($pid);
        $cart = [];
        foreach ($basketProduct as $bproduct) {
            foreach ($pickingData as $pproduct) {
                // dd($bproduct->product_id,$pproduct->product_id);
                print_r();
            }
            // $cart[] = 
        }
        // $request->merge([
        //     'product_id' => $request->product_id,
        //     'single_qty' => $request->single_qty,
        // ]);
        // dd($request->all());

        // $FrontCheckoutController = new FrontCheckoutController(); 
        $this->addToCart($request, new Basket, new Product, new User);
    }


    public function cart(Basket $basketModel, Product $productModel)

    {

        $loginUser =  \Auth::user()->uuid;
        // $basketModel->where('order_id',$order_id)->update([
        //     'is_modify' => '1'
        // ]);
        $data = $basketModel->where('user_id', $loginUser)->where('is_modify', '1')->latest()->first();
        // $basketIdData = BasketProducts::where('basket_id',$data->uuid)->get();
        $basketId = '';
        if ($data == null) {
            $basketId = session()->get('basket_id', null);
        }
        $basketData = Basket::where('uuid', $basketId)->first();
        // dd($basketId,$basketData,$data);
        if ($data != null) {
            if ($basketData != null) {
                $salesOrder = SalesOrder::where('uuid', $basketData->order_id)->first();
                if ($salesOrder != null) {
                    if ($salesOrder->order_status != SalesOrder::ORDERPLACED) {
                        $basketId = session()->put('basket_id', null);
                    }
                }
            }
            if (is_null($basketId) || is_null($basketData)) {

                session(['basket_id' => $data->uuid]);
                $basketId = session()->get('basket_id', null);
            }
        }

        if (is_null($basketId)) {

            $basket_data = $basketModel->getBasket();

            if ($basket_data->first()) {

                $basketId = $basket_data['0'];

                $basket = $basketModel->where('uuid', $basketId)->first();

                $products = $basket->products;

                session(['basket_id' => $basketId]);
            } else {

                $products = null;

                $basket = null;
            }
        } else {

            $basket = $basketModel->where('uuid', $basketId)->first();
            if ($basket != null) {

                $products = $basket->products;
            } else {
                // $basketIdData = BasketProducts::where('basket_id',$data->uuid)->get();
                // $products = $basketIdData;
                $products = [];
            }
            // dd($products);
        }

        $pageTitle = "Cart";

        $bodyClass = ['about-us'];

        // Product::where();
        // $productModel->where();
        // dd($productModel);
        if ($products != null) {

            $products = $products->transform(function ($query) use ($productModel) {
                $pId = $productModel->where('uuid', $query->product_id)->first();

                $getColorVariants = [];
                if ($pId->colour_variants != null) {
                    $getColorVariants = $pId->colour_variants;
                    $getColorVariants = explode(',', $getColorVariants);
                }
                $getSizeVariants = [];
                if ($pId->size_variants != null) {
                    $getSizeVariants = $pId->size_variants;
                    $getSizeVariants = explode(',', $getSizeVariants);
                }

                $query->getColorVariants = $getColorVariants;
                $query->getSizeVariants = $getSizeVariants;
                return $query;
            });
        }

        // dd($products);
        // color
        // size
        return view('frontend.checkout.cart', compact('pageTitle', 'bodyClass', 'products', 'basket'));
    }



    public function removeProductFromCart($product_id, Basket $basketModel, Product $productModel)

    {

        $basketId = session()->get('basket_id', null);

        if (is_null($basketId)) {

            return redirect()->back()->with(['status' => 'danger', 'message' => trans('warning.frontend|somethingWentWrong')]);
        } else {
            $basketModel->where('uuid', $basketId)->update(['is_modify' => '0']);

            $basket = $basketModel->where('uuid', $basketId)->first();

            $basket->products()->where('product_id', $product_id)->delete();
            $product = $productModel->where('uuid', $product_id)->first();

            return redirect()->back()->with(['status' => 'success', 'message' => trans('success.checkout|productDeletedFromCart', ['product_name' => $product->name])]);
        }
    }



    public function selectSupplier(Basket $basketModel, SupplierItemInventory $supplierItemInventoryModel, User $userModel, LogisticDetails $logisticModel, DeliveryVehicleMaster $deliveryVehicleMasterModel, Product $productModel, Promotion $offerModel)
    // public function selectSupplier(Basket $basketModel, SupplierItemInventory $supplierItemInventoryModel, User $userModel, LogisticDetails $logisticModel, DeliveryVehicleMaster $deliveryVehicleMasterModel, Product $productModel, OfferDeals $offerModel)

    {

        $supplierOfferCodes = array();

        $basketId = session()->get('basket_id', null);

        if (is_null($basketId)) {

            return redirect()->back()->with(['status' => 'danger', 'message' => trans('warning.frontend|somethingWentWrong')]);
        }

        $basket = $basketModel->where('uuid', $basketId)->first();

        //$basketProductIds = $basket->products()->pluck('product_id')->toArray();

        $basketProducts = $basket->products;

        $arrBasketProducts = array();

        foreach ($basketProducts as $productKey => $productData) {

            if (!empty($productData['single_qty']) && $productData['single_qty'] >= 0) {

                $arrBasketProducts[] = $productData;

                $basketProductIds[] = $productData['product_id'];
            }
        }

        //DB::enableQueryLog(); // Enable query log

        if (count($basketProducts) == 0) {

            return redirect('checkout/cart');
        }
        $supplierIdsWithStockModal = $supplierItemInventoryModel->whereIn('product_id', $basketProductIds);

        $supplierIdsWithStockModal->where(function ($q) {

            $q->whereNotNull('single');

            $q->where('single', '>', 0);

            $q->whereNotNull('single_price');

            $q->where('single_price', '>', 0);

            // new added
            $q->where('min_order_quantity', '>', 0);
        });

        $supplierIdsWithStock = $supplierIdsWithStockModal->groupBy('user_id')->pluck('user_id');

        //dd(DB::getQueryLog()); // Show results of log

        $suppliers = $userModel->whereIn('uuid', $supplierIdsWithStock)->with('company')->get();

        // dd($suppliers);die();

        $todayDate = Carbon::now()->format('Y-m-d');

        // $arrSupplierOfferCodes = $offerModel
        //     ->where(DB::raw("date_format(start_date, '%Y-%m-%d %H:%i:%s')"), '<=', date('Y-m-d H:i:s', strtotime($todayDate)))
        //     ->where(DB::raw("date_format(end_date, '%Y-%m-%d %H:%i:%s')"), '>=', date('Y-m-d H:i:s', strtotime($todayDate)))
        //     ->whereIn('user_id', $supplierIdsWithStock)->select('user_id', 'offercode')->get()->toArray();

        $arrSupplierOfferCodes = $offerModel
            ->where(DB::raw("date_format(period_from, '%Y-%m-%d %H:%i:%s')"), '<=', date('Y-m-d H:i:s', strtotime($todayDate)))
            ->where(DB::raw("date_format(period_to, '%Y-%m-%d %H:%i:%s')"), '>=', date('Y-m-d H:i:s', strtotime($todayDate)))
            ->whereIn('user_id', $supplierIdsWithStock)->select('user_id', 'promotion_id')->get()->toArray();


        foreach ($arrSupplierOfferCodes as $sdata) {

            $supplierOfferCodes[$sdata['user_id']][] = $sdata['promotion_id'];
        }



        $currentUser = auth()->user();

        $supplierData = [];



        foreach ($suppliers as $supplier) {

            if (!empty($supplier->latitude) && !empty($supplier->longitude)) {

                $supplierLoopData = [];

                $supplierLoopData["supplier"]["display_name"] = $supplier->company()->exists() ? $supplier->company->trading_name : $supplier->name;

                $supplierLoopData["supplier"]["uuid"] = $supplier->uuid;

                $supplierLoopData["products"] = [];

                $supplierLoopData["supplier_delivery"] = $supplier->supplier_delivery;

                $total = 0;

                $totalWeight = 0;

                $totalProducts = 0;

                $offerAmount = 0;

                $totalAvailableProducts = 0;

                $itemTotalTax = 0;

                // $itemMinValue = 0; //added
                // $itemOldValue = [];//added


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

                            if ($offerModel->where('user_id', $supplier->uuid)->where('product_id', $basketProduct->product_id)->where(DB::raw("date_format(period_from, '%Y-%m-%d %H:%i:%s')"), '<=', date('Y-m-d H:i:s', strtotime($todayDate)))->where(DB::raw("date_format(period_to, '%Y-%m-%d %H:%i:%s')"), '>=', date('Y-m-d H:i:s', strtotime($todayDate)))->count() > 0) {
                                // if ($offerModel->where('user_id', $supplier->uuid)->where('products_id', $basketProduct->product_id)->where(DB::raw("date_format(start_date, '%Y-%m-%d %H:%i:%s')"), '<=', date('Y-m-d H:i:s', strtotime($todayDate)))->where(DB::raw("date_format(end_date, '%Y-%m-%d %H:%i:%s')"), '>=', date('Y-m-d H:i:s', strtotime($todayDate)))->count() > 0) {

                                $productOffer = $offerModel->where('user_id', $supplier->uuid)->where('product_id', $basketProduct->product_id)->where(DB::raw("date_format(period_from, '%Y-%m-%d %H:%i:%s')"), '<=', date('Y-m-d H:i:s', strtotime($todayDate)))->where(DB::raw("date_format(period_to, '%Y-%m-%d %H:%i:%s')"), '>=', date('Y-m-d H:i:s', strtotime($todayDate)))->orderBy('id', 'DESC')->first();
                                // $productOffer = $offerModel->where('user_id', $supplier->uuid)->where('products_id', $basketProduct->product_id)->where(DB::raw("date_format(start_date, '%Y-%m-%d %H:%i:%s')"), '<=', date('Y-m-d H:i:s', strtotime($todayDate)))->where(DB::raw("date_format(end_date, '%Y-%m-%d %H:%i:%s')"), '>=', date('Y-m-d H:i:s', strtotime($todayDate)))->orderBy('id', 'DESC')->first();

                                if ($productOffer->promotion_id != '') {

                                    $singlePrice = $productOffer->promotion_price;
                                    // dd('1', $singlePrice);
                                } else {
                                    $singlePrice = $singlePrice - (($singlePrice * ($productOffer->promotion_price)) / 100);
                                    // dd('2', $singlePrice);
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

                if ($offerModel->where('user_id', $supplier->uuid)->whereDate('period_to', '>=', $todayDate)->count() > 0) {
                    // if ($offerModel->where('user_id', $supplier->uuid)->whereDate('end_date', '>=', $todayDate)->count() > 0) {



                    $offerLatestRate = $offerModel->where('user_id', $supplier->uuid)->whereDate('period_to', '>=', $todayDate)->orderBy('id', 'DESC')->first();
                    // $offerLatestRate = $offerModel->where('user_id', $supplier->uuid)->whereDate('end_date', '>=', $todayDate)->orderBy('id', 'DESC')->first();

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

                $available_list = $logisticModel->getVehicle($totalWeight);
                $available_list_for_supplier = $logisticModel->getVehicleData($totalWeight);



                $vehicle_list = array();
                $vehicle_list_supplier = array();

                if ($available_list) {

                    foreach ($available_list as $key1 => $value1) {

                        $vehicle_list[] = $value1->vehicle_type;
                    }
                }

                if ($available_list_for_supplier) {

                    foreach ($available_list_for_supplier as $key1 => $value1) {

                        $vehicle_list_supplier[] = $value1->vehicle_type;
                    }
                }


                $vehiclelist = implode(',', $vehicle_list);
                $vehiclelistSupplier = implode(',', $vehicle_list_supplier);

                $supplierLoopData["delivery_vehicle"] = $vehiclelist;
                $supplierLoopData["delivery_vehicle_supplier"] = $vehiclelistSupplier;

                //  new added
                //  $supplierLoopData["total_tax"] = $itemTotalTax;
                //  $supplierLoopData["total_tax"] = $itemTotalTax;


            }

            $supplierData[] = $supplierLoopData;
        }
        // dd($supplierData);
        // dd($basketProducts);
        // dd($suppliers);



        $pageTitle = "Select Supplier";

        $bodyClass = ['about-us'];


        return view('frontend.checkout.selectSupplier', compact('pageTitle', 'bodyClass', 'basketProductIds', 'suppliers', 'supplierItemInventoryModel', 'basketProducts', 'logisticModel', 'deliveryVehicleMasterModel', 'currentUser', 'productModel', 'supplierData', 'offerModel', 'arrBasketProducts', 'supplierOfferCodes', 'userModel'));
    }
}
