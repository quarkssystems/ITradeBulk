<?php



namespace App\Http\Controllers;



use App\Http\Requests\FrontendContactSubmitRequest;
use App\Http\Requests\FrontendReportAbuseSubmitRequest;
use App\Http\Requests\FrontendFeedbackSubmitRequest;

use App\Mail\ContactMail;
use App\Mail\ReportAbuseMail;
use App\Mail\FeedbackMail;

use App\Models\Basket;

use App\Models\LocationZipcode;

use App\Models\SalesOrder;

use App\Models\WalletTransactions;

use App\Models\Product;

use App\Models\Category;

use App\Models\Brand;

use App\Models\Banner;

use App\Models\CMSBlock;

use App\Models\Team;

use App\Models\Testimonials;

use App\Models\UserCompany;

use App\Models\OfferDeals;

use App\Models\OffercodeUsedby;

use App\Models\SupplierItemInventory;

use App\Models\SuccessStory;

use App\Models\CMSModule;

use App\Models\EmailTemplate;

use App\Models\BasketProducts;

use App\Models\Setting;

use App\General\ChangeOrderStatus;

use App\User;

use Illuminate\Http\Request;

use App\Models\RequestQuote;
use App\Models\Notification;

use App\Http\Requests\AdminRequestQuoteRequest;

use Illuminate\Support\Facades\Mail;

use Carbon\Carbon;

use PDF;

use DB;

use Session;

use App\PickingDocument;
use App\DispatchedDocument;
use App\Models\Promotion;

class FrontendHomeController extends Controller

{

    public function home(LocationZipcode $locationZipcodeModel)

    {



        $locations = $locationZipcodeModel->getDropDownWithZipCode();

        $pageTitle = "Home";

        $bodyClass = ['home'];

        $categories = Category::whereNull('deleted_at')->where('status', 'Active')->paginate(18);

        $brands = Brand::whereNull('deleted_at')->where('status', 'Active')->limit(6)->get();

        $suppliers = User::with('company')->whereNull('deleted_at')->where([['status', 'Active'], ['role', 'SUPPLIER']])->get();

        // $newarrivals = Product::whereNull('deleted_at')->where([['status', 'Active'], ['arrival_type', 1], ['default_stock_type', '1']])
        //     ->whereHas('supplierStock', function ($q) {
        //         $q->where('single', '>', 0);
        //         $q->where('single_price', '>', 0);
        //     })
        //     ->limit(4)->get();

        $newarrivals = Product::whereNull('deleted_at')
            ->whereHas('supplierStock', function ($q) {
                $q->where('single', '>', 0);
                $q->where('single_price', '>', 0);
            })
            ->orderBy('id', 'desc')
            ->limit(4)->get();

        // $bestsales = Product::whereNull('deleted_at')->where([['status', 'Active'], ['arrival_type', 2], ['default_stock_type', '1']])
        //     ->whereHas('supplierStock', function ($q) {
        //         $q->where('single', '>', 0);
        //         $q->where('single_price', '>', 0);
        //     })
        //     ->limit(4)->get();

        $bestsales = Basket::join('basket_products', 'basket_products.basket_id', '=', 'baskets.uuid')
            ->join('products', 'products.uuid', '=', 'basket_products.product_id')
            ->join('supplier_item_inventories', 'supplier_item_inventories.product_id', '=', 'products.uuid')
            ->join('sales_orders', 'sales_orders.uuid', '=', 'baskets.order_id')
            ->where('sales_orders.order_status', 'DELIVERED')
            ->where(function ($q) {
                $q->where('supplier_item_inventories.single', '>', 0);
                $q->where('supplier_item_inventories.single_price', '>', 0);
            })
            ->select('products.*')
            ->orderBy('products.id', 'desc')
            ->limit(4)->get();
        // dd($bestsales);
        // BasketProducts::leftjoin('products','product.uuid','=','basket_products.product_id')
        // ->leftjoin('basket','product.uuid','=','basket_products')
        // SalesOrder::
        // $newarrivals = Product::whereNull('deleted_at')
        //     ->whereHas('supplierStock', function ($q) {
        //         $q->where('single', '>', 0);
        //         $q->where('single_price', '>', 0);
        //     })
        //     ->orderBy('id', 'desc')
        //     ->limit(4)->get();


        // $dealofthedays = Product::whereNull('deleted_at')->where([['status', 'Active'], ['arrival_type', 3], ['default_stock_type', '1']])
        //     ->whereHas('supplierStock', function ($q) {
        //         $q->where('single', '>', 0);
        //         $q->where('single_price', '>', 0);
        //     })
        //     ->limit(4)->get();

        $dealofthedays = Promotion::join('products', 'products.uuid', '=', 'promotions.product_id')
            // ->whereHas('supplierStock', function ($q) {
            //     $q->where('single', '>', 0);
            //     $q->where('single_price', '>', 0);
            // })
            ->join('supplier_item_inventories', 'supplier_item_inventories.product_id', '=', 'products.uuid')
            ->where(function ($q) {
                $q->where('supplier_item_inventories.single', '>', 0);
                $q->where('supplier_item_inventories.single_price', '>', 0);
            })
            ->whereDate('promotions.period_from', '<=', date("Y-m-d"))
            ->whereDate('promotions.period_to', '>=', date("Y-m-d"))
            ->where('published', '=', '1')
            ->select('products.*')
            ->orderBy('products.id', 'desc')
            ->groupBy('products.uuid')
            ->limit(4)->get();

        $bestofthisweeks = Product::whereNull('deleted_at')->where([['status', 'Active'], ['arrival_type', 4], ['default_stock_type', '1']])
            ->whereHas('supplierStock', function ($q) {
                $q->where('single', '>', 0);
                $q->where('single_price', '>', 0);
            })
            ->limit(4)->get();

        // $recommendations = Product::whereNull('deleted_at')->where([['status', 'Active'], ['arrival_type', '<>', ''], ['default_stock_type', '1']])
        //     ->whereHas('supplierStock', function ($q) {
        //         $q->where('single', '>', 0);
        //         $q->where('single_price', '>', 0);
        //     })
        //     ->limit(6)->get();

        $recommendations = Product::whereHas('supplierStock', function ($q) {
            $q->where('single', '>', 0);
            $q->where('single_price', '>', 0);
        })->orderBy('products.id', 'desc')
            ->get()->random(6);
        // all()->random(10);

        $banner = Banner::whereNull('deleted_at')->where([['status', 'Active'], ['page_name', 1]])->first();
        $banners = Banner::whereNull('deleted_at')->where([['status', 'Active'], ['in_slider', 'on']])->orderBy('sequence_number', 'ASC')->get();

        $banner = $banner->image ?? '';

        $newarrivalsProducts = [];
        $bestsalesProducts = [];
        $dealofthedaysProducts = [];
        $bestofthisweeksProducts = [];
        $recommendationsProducts = [];

        if ($newarrivals) {
            foreach ($newarrivals as $pkey => $product) {

                $newarrivalsProducts[$pkey] = $product;
                $newarrivalsProducts[$pkey]['child'] = Product::where('parent_id', $product->parent_id)->get();
            }
        }
        if ($bestsales) {
            foreach ($bestsales as $pkey => $product) {

                $bestsalesProducts[$pkey] = $product;
                $bestsalesProducts[$pkey]['child'] = Product::where('parent_id', $product->parent_id)->get();
            }
        }
        if ($dealofthedays) {
            foreach ($dealofthedays as $pkey => $product) {


                $dealofthedaysProducts[$pkey] = $product;
                $dealofthedaysProducts[$pkey]['child'] = Product::where('parent_id', $product->parent_id)->get();
                $productData = Product::where('uuid', $product->uuid)->first();
                if (isset($productData)) {
                    $dealofthedaysProducts[$pkey]['pdetails'] = $productData->pdetails;
                } else {
                    $dealofthedaysProducts[$pkey]['pdetails'] = null;
                }

                $user = User::with('company')->where('uuid', $product->user_id)->first();
                if (isset($user)) {
                    $dealofthedaysProducts[$pkey]['user'] = $user;
                } else {
                    $dealofthedaysProducts[$pkey]['user'] = null;
                }
            }
        }
        if ($bestofthisweeks) {
            foreach ($bestofthisweeks as $pkey => $product) {

                $bestofthisweeksProducts[$pkey] = $product;
                $bestofthisweeksProducts[$pkey]['child'] = Product::where('parent_id', $product->parent_id)->get();
            }
        }
        if ($recommendations) {
            foreach ($recommendations as $pkey => $product) {

                $recommendationsProducts[$pkey] = $product;
                $recommendationsProducts[$pkey]['child'] = Product::where('parent_id', $product->parent_id)->get();
            }
        }

        // dd($newarrivalsProducts);
        // echo '<pre>';

        // print_r($suppliers->toArray());

        // die;

        return view('frontend.home.home', compact('pageTitle', 'bodyClass', 'locations', 'categories', 'brands', 'suppliers', 'newarrivals', 'bestsales', 'dealofthedays', 'bestofthisweeks', 'recommendations', 'banner', 'newarrivalsProducts', 'bestsalesProducts', 'dealofthedaysProducts', 'bestofthisweeksProducts', 'recommendationsProducts', 'banners'));
    }



    public function about()

    {

        $pageTitle = "About Us";

        $bodyClass = ['about-us'];

        $brands = Brand::whereNull('deleted_at')->where('status', 'Active')->limit(6)->get();

        $banner = Banner::whereNull('deleted_at')->where([['status', 'Active'], ['page_name', 3]])->first();

        $cmsblock = CMSBlock::whereNull('deleted_at')->where([['status', 'Active'], ['slug', "about-us"]])->count() > 0 ? CMSBlock::whereNull('deleted_at')->where([['status', 'Active'], ['slug', "about-us"]])->first() : false;

        $whyuscmsblock = CMSBlock::whereNull('deleted_at')->where([['status', 'Active'], ['slug', "why-choose-us"]])->count() > 0 ? CMSBlock::whereNull('deleted_at')->where([['status', 'Active'], ['slug', "why-choose-us"]])->first() : false;

        $teams = Team::whereNull('deleted_at')->where('status', 'Active')->get();

        $testimonials = Testimonials::with('user')->whereNull('deleted_at')->where('status', 'Active')->count() > 0 ? Testimonials::with('user')->whereNull('deleted_at')->where('status', 'Active')->get() : false;

        $banner = $banner->image ?? '';

        // dd($testimonials);



        return view('frontend.home.about', compact('pageTitle', 'bodyClass', 'brands', 'banner', 'cmsblock', 'teams', 'testimonials', 'whyuscmsblock'));
    }



    public function contact()

    {

        $pageTitle = "Contact Us";

        $bodyClass = ['about-us'];

        $banner = Banner::whereNull('deleted_at')->where([['status', 'Active'], ['page_name', 2]])->first();

        $cmsblock = CMSBlock::where([['status', 'Active'], ['slug', "contact-us"]])->count() > 0 ? CMSBlock::where([['status', 'Active'], ['slug', "contact-us"]])->first() : false;

        // dd($cmsblock);

        $testimonials = Testimonials::with('user')->whereNull('deleted_at')->where('status', 'Active')->count() > 0 ? Testimonials::with('user')->whereNull('deleted_at')->where('status', 'Active')->get() : false;

        $banner = $banner->image ?? '';

        return view('frontend.home.contact', compact('pageTitle', 'bodyClass', 'cmsblock', 'banner', "testimonials"));
    }

    public function reportAbuse()
    {
        $pageTitle = "Report Abuse";
        $bodyClass = ['about-us'];
        $banner = Banner::whereNull('deleted_at')->where([['status', 'Active'], ['page_name', 2]])->first();
        // $cmsblock = CMSBlock::where([['status','Active'],['slug',"contact-us"]])->count() > 0 ? CMSBlock::where([['status','Active'],['slug',"contact-us"]])->first() : false;
        // dd($cmsblock);
        $testimonials = Testimonials::with('user')->whereNull('deleted_at')->where('status', 'Active')->count() > 0 ? Testimonials::with('user')->whereNull('deleted_at')->where('status', 'Active')->get() : false;
        $banner = $banner->image ?? '';

        return view('frontend.home.report-abuse', compact('pageTitle', 'bodyClass', 'banner', 'testimonials'));
    }

    public function feedback()
    {
        $pageTitle = "Feedback";
        $bodyClass = ['about-us'];
        $banner = Banner::whereNull('deleted_at')->where([['status', 'Active'], ['page_name', 2]])->first();
        // $cmsblock = CMSBlock::where([['status','Active'],['slug',"contact-us"]])->count() > 0 ? CMSBlock::where([['status','Active'],['slug',"contact-us"]])->first() : false;
        // dd($cmsblock);
        $testimonials = Testimonials::with('user')->whereNull('deleted_at')->where('status', 'Active')->count() > 0 ? Testimonials::with('user')->whereNull('deleted_at')->where('status', 'Active')->get() : false;
        $banner = $banner->image ?? '';

        return view('frontend.home.feedback', compact('pageTitle', 'bodyClass', 'banner', 'testimonials'));
    }



    public function becomeDriver()

    {

        $pageTitle = "Become Driver";

        $bodyClass = ['home'];
        // $transportTypes = $logisticDetailsModel->getTransportTypesDropDown();


        return view('frontend.home.becomeDriver', compact('pageTitle', 'bodyClass'));
    }



    public function becomeSupplier()

    {

        $pageTitle = "Become Supplier";

        $bodyClass = ['home'];

        return view('frontend.home.becomeSupplier', compact('pageTitle', 'bodyClass'));
    }



    public function becomeVendor()

    {

        $pageTitle = "Become Vendor";

        $bodyClass = ['home'];

        return view('frontend.home.becomeVendor', compact('pageTitle', 'bodyClass'));
    }



    public function requestQuote()

    {

        $pageTitle = "Request Quote";

        $bodyClass = ['about-us'];

        $banner = Banner::whereNull('deleted_at')->where([['status', 'Active'], ['page_name', 4]])->first();

        $banner = $banner->image ?? '';

        return view('frontend.home.requestQuote', compact('pageTitle', 'bodyClass', 'banner'));
    }



    public function supplier()

    {

        $pageTitle = "Supplier";

        $bodyClass = ['about-us'];



        $categories = Category::whereNull('deleted_at')->where('status', 'Active')->get();

        $brands = Brand::whereNull('deleted_at')->where('status', 'Active')->limit(6)->get();

        $suppliers = User::whereNull('deleted_at')->where([['status', 'Active'], ['role', 'SUPPLIER']])->with('company')->get();

        $newarrivals = Product::whereNull('deleted_at')->where([['status', 'Active'], ['arrival_type', 1]])->limit(4)->get();

        $bestsales = Product::whereNull('deleted_at')->where([['status', 'Active'], ['arrival_type', 2]])->limit(4)->get();

        $dealofthedays = Product::whereNull('deleted_at')->where([['status', 'Active'], ['arrival_type', 3]])->limit(4)->get();

        $bestofthisweeks = Product::whereNull('deleted_at')->where([['status', 'Active'], ['arrival_type', 4]])->limit(4)->get();

        $trendingProducts = Product::whereNull('deleted_at')->where([['status', 'Active'], ['arrival_type', '<>', '']])->get();

        $banners = Banner::whereNull('deleted_at')->where([['status', 'Active'], ['page_name', 5]])->get();

        return view('frontend.home.supplier', compact('pageTitle', 'bodyClass', 'categories', 'brands', 'suppliers', 'newarrivals', 'bestsales', 'dealofthedays', 'bestofthisweeks', 'trendingProducts', 'banners'));
    }

    /**

     * Purpose : show supplier detail

     */

    public function supplierDetail($supplier_uuid)

    {

        $admin_email = env('MAIL_USERNAME');

        $pageTitle = "Supplier Detail";

        $bodyClass = ['supplier-detail'];

        $suppliers = User::where([['uuid', $supplier_uuid]])->with('company')->first();

        $banners = Banner::whereNull('deleted_at')->where([['status', 'Active'], ['page_name', 5]])->get();

        $successStory = SuccessStory::where('user_uuid', $supplier_uuid)->first();

        return view('frontend.home.supplierdetail', compact('pageTitle', 'bodyClass', 'suppliers', 'banners', 'successStory', 'admin_email'));
    }



    public function productList()

    {

        $pageTitle = "Products";

        $bodyClass = ['about-us'];

        return view('frontend.catalog.product.list', compact('pageTitle', 'bodyClass'));
    }



    public function cart()

    {

        $pageTitle = "Cart";

        $bodyClass = ['about-us'];

        return view('frontend.checkout.cart', compact('pageTitle', 'bodyClass'));
    }



    public function selectSupplier()

    {

        $pageTitle = "Select Supplier";

        $bodyClass = ['about-us'];

        return view('frontend.home.selectSupplier', compact('pageTitle', 'bodyClass'));
    }



    public function selectPaymentMethod(Request $request, Basket $basketModel, SupplierItemInventory $supplierItemInventoryModel, Promotion $offerModel)

    {
        // dd($request->all());
        $currentUser = auth()->user();

        $supplierData = User::where('uuid', $request->supplier_id)->with('company')->first();
        //    dd($supplierData);

        $distance = $request->get("total_distance", 0);

        $weight = $request->get("total_weight", 0);

        $productTotal = $request->get("product_total", 0);

        $shippingTotal = $request->get("shipping_total", 0);

        $shippingMethod = $request->get("delivery_type", "pickup");

        $supplierId = $request->get("supplier_id", null);

        $offerTotal = $request->get("offer_total", null);

        $offerId = $request->get("offer_id", 0);

        $offerId = $request->get("offer_id", 0);

        $paybel_amt_input = $request->get("paybel_amt_input", 0);

        $item_tax_input = $request->get("item_tax_input", 0);
        $delivery_status = $request->get("delivery_status", '');



        if ($shippingMethod == 'own_distributor') {
            $supplierDataNew =  User::where('uuid', $request->supplier_id)->first();
            $shippingTotal = ($supplierDataNew->delivery_rate != "") ? $supplierDataNew->delivery_rate : 0;
        } else if ($shippingMethod == 'delivery') {
            $shippingTotal =  $shippingTotal;
        } else {
            $shippingTotal = 0;
        }
        // $shippingMethod = str_replace('_', ' ', $shippingMethod);

        $basketId = session()->get('basket_id', null);

        $todayDate = Carbon::now()->format('Y-m-d');

        if (is_null($basketId)) {

            return redirect()->back()->with(['status' => 'danger', 'message' => trans('warning.frontend|somethingWentWrong')]);
        }

        // if ($request->delivery_type == 'courier') {
        //     return redirect()->route('selectCourier', [$basketId]);
        // }

        $basket = $basketModel->where('uuid', $basketId)->first();

        $basketProductIds = $basket->products()->pluck('product_id')->toArray();

        $basketProducts = $basket->products;

        $productOffer = '';

        $supplierLoopData = [];

        $supplierLoopData["products"] = [];

        $totalWeight = 0;

        $totalProducts = 0;

        $totalAvailableProducts = 0;

        $total = 0;



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

                    if ($offerModel->where('user_id', $supplierId)->where('product_id', $basketProduct->product_id)->where(DB::raw("date_format(period_from, '%Y-%m-%d %H:%i:%s')"), '<=', date('Y-m-d H:i:s', strtotime($todayDate)))->where(DB::raw("date_format(period_to, '%Y-%m-%d %H:%i:%s')"), '>=', date('Y-m-d H:i:s', strtotime($todayDate)))->count() > 0) {
                        $productOffer = $offerModel->where('user_id', $supplierId)->where('product_id', $basketProduct->product_id)->where(DB::raw("date_format(period_from, '%Y-%m-%d %H:%i:%s')"), '<=', date('Y-m-d H:i:s', strtotime($todayDate)))->where(DB::raw("date_format(period_to, '%Y-%m-%d %H:%i:%s')"), '>=', date('Y-m-d H:i:s', strtotime($todayDate)))->orderBy('id', 'DESC')->first();
                        if ($productOffer->promotion_id != '') {
                            $singlePrice = $productOffer->promotion_price;
                        } else {
                            $singlePrice = $singlePrice - (($singlePrice * ($productOffer->promotion_price)) / 100);
                        }
                        $supplierProductLoopData["productOffer"] = $productOffer;
                    } else {
                        $supplierProductLoopData["productOffer"] = null;
                    }

                    // if ($offerModel->where('user_id', $supplierId)->where('products_id', $basketProduct->product_id)->whereDate('end_date', '>=', $todayDate)->count() > 0) {
                    //     $productOffer = $offerModel->where('user_id', $supplierId)->where('products_id', $basketProduct->product_id)->whereDate('end_date', '>=', $todayDate)->orderBy('id', 'DESC')->first();
                    //     if ($productOffer->offer_type == 'RENT') {
                    //         $singlePrice = $singlePrice - ($productOffer->offer_value);
                    //     } else {
                    //         $singlePrice = $singlePrice - (($singlePrice * ($productOffer->offer_value)) / 100);
                    //     }
                    // }


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

                        $supplierProductLoopData["qty"] = $basketProduct->single_qty;

                        $supplierProductLoopData["productSinglePrice"] = $productSinglePrice;

                        $supplierProductLoopData["price"] = $singlePrice;

                        $supplierProductLoopData["totalprice"] = ($basketProduct->single_qty * $singlePrice);
                        $supplierProductLoopData["color"] = $basketProduct->color;
                        $supplierProductLoopData["size"] = $basketProduct->size;

                        $itemWeight += $basketProduct->product->getCalculatedWeight("single", $basketProduct->single_qty);

                        $rowTotal += ($basketProduct->single_qty * $singlePrice);
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



        // $trader = auth()->user()->uuid;
        // $supplier = $request->supplier_id;

        // $supplier = UserCompany::where('owner_user_id', $request->supplier_id)->first();
        // if ($supplier != null) {
        //     $supplierAddress = $this->getAddress($supplier);
        // } else {
        //     $supplierAddress = '';
        // }
        // $trader = UserCompany::where('owner_user_id', auth()->user()->uuid)->first();
        // if ($trader != null) {
        //     $traderAddress = $this->getAddress($trader);
        // } else {
        //     $traderAddress = '';
        // }



        $walletBalance = auth()->user()->wallet_balance;

        $pageTitle = "Make Payment";

        $bodyClass = ['about-us'];

        if (Session::has('requestData')) {

            Session::forget('requestData');
        }

        Session::push('requestData', $request->all());

        return view('frontend.checkout.selectPaymentMethod', compact('pageTitle', 'bodyClass', 'supplierLoopData', 'productTotal', 'shippingMethod', 'shippingTotal', 'walletBalance', 'supplierId', 'offerTotal', 'offerId', 'paybel_amt_input', 'item_tax_input', 'distance', 'weight', 'supplierData', 'productOffer', 'delivery_status'));
    }

    public function getAddress($data)
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

    public function getPickingDocument(Request $request, Basket $basketModel, SupplierItemInventory $supplierItemInventoryModel, Promotion $offerModel, $order_id)

    {


        $pickingData = PickingDocument::where('order_id', $order_id)->first();


        $salesData = SalesOrder::leftjoin('baskets', 'baskets.order_id', 'sales_orders.uuid')
            ->where('sales_orders.uuid', $order_id)
            ->select('sales_orders.*', 'baskets.uuid as basket_id')
            ->first();
        $supplierData = User::where('uuid', $salesData->supplier_id)->with('company')->first();

        if ($pickingData != null) {

            $PickingDocumentData = PickingDocument::where('order_id', $order_id)->get();
            // dd($PickingDocumentData->single_qty, $salesData);

            $cart_amount = 0;
            foreach ($PickingDocumentData as $data) {
                if (isset($data->offer_price) && $data->offer_price > 0) {
                    $qnty = $data->single_qty * $data->offer_price;
                } else {
                    $qnty = $data->single_qty * $data->product_price;
                }
                $cart_amount = $cart_amount + $qnty;
            }
            // offer_price
            // $cart_amount = PickingDocument::where('order_id', $order_id)->sum('cart_amount');
            $shipment_amount = PickingDocument::where('order_id', $order_id)->sum('shipment_amount');
            $final_total = PickingDocument::where('order_id', $order_id)->sum('final_total');
            $tax_amount = PickingDocument::where('order_id', $order_id)->sum('tax_amount');

            $productTotal = $cart_amount;
            $shippingTotal = $salesData->shipment_amount;
            // $shippingTotal = $shipment_amount;
            // $paybel_amt_input = $cart_amount;
            $paybel_amt_input = $cart_amount + $shippingTotal;
            // dd('hi', $cart_amount, $shippingTotal);
            // $paybel_amt_input = $final_total;
            $item_tax_input = $tax_amount;
        } else {
            $productTotal = $salesData->cart_amount;

            $shippingTotal = $salesData->shipment_amount;

            $paybel_amt_input = $salesData->final_total;

            $item_tax_input = $salesData->tax_amount;
        }
        // dd($paybel_amt_input);
        // dd($shippingTotal);

        $distance = $salesData->distance;

        $weight = $salesData->total_weight;



        $shippingMethod = $salesData->delivery_type;

        $supplierId = $salesData->supplier_id;

        $offerTotal = $salesData->offer_total;

        $offerId = $salesData->offer_id;





        $basketId = $salesData->basket_id;



        $todayDate = Carbon::now()->format('Y-m-d');

        $basket = $basketModel->where('uuid', $basketId)->first();

        $basketProductIds = $basket->products()->pluck('product_id')->toArray();

        $basketProducts = $basket->products;

        $productOffer = '';

        $supplierLoopData = [];

        $supplierLoopData["products"] = [];

        $totalWeight = 0;

        $totalProducts = 0;

        $totalAvailableProducts = 0;

        $total = 0;

        $oldFinalTotal = 0;

        $pickingAllData = PickingDocument::where('order_id', $order_id)->get();

        $cart_amount = 0;
        if (count($pickingAllData) != 0) {
            $product = [];
            foreach ($pickingAllData as $proIndex => $pickData) {

                $productData = Product::where('uuid', $pickData->product_id)->first();
                // $basketProducts = BasketProducts::where('uuid',$pickData->product_id)->select('color','size')->first();
                // dd($basketProducts,$pickData->product_id);
                if (isset($pickData->offer_price) && $pickData->offer_price > 0) {
                    $qnty = $pickData->single_qty * $pickData->offer_price;
                } else {
                    $qnty = $pickData->single_qty * $pickData->product_price;
                }
                // $qnty = $pickData->single_qty * $pickData->offer_price;
                $cart_amount = $cart_amount + $qnty;

                $product[$proIndex]['product_id'] = $pickData->product_id;
                $product[$proIndex]['product_name'] = $productData->name;
                $product[$proIndex]['qty'] = $pickData->single_qty;
                $product[$proIndex]['productSinglePrice'] = $pickData->product_price;
                $product[$proIndex]['price'] = $pickData->product_price;
                $product[$proIndex]['totalprice'] = $cart_amount;
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
                $product[$proIndex]['offer_price'] = $pickData->offer_price;
                $product[$proIndex]['offer_id'] = $pickData->offer_id;
                $product[$proIndex]['exist_picking'] = '1';
                $product[$proIndex]['color'] = $pickData->color;
                $product[$proIndex]['size'] = $pickData->size;
                // $itemWeight += $basketProduct->product->getCalculatedWeight("single", $basketProduct->single_qty);
                $product[$proIndex]["shippingTotal"] = $shippingTotal;

                // $rowTotal += ($basketProduct->single_qty * $singlePrice);
                $product[$proIndex]['row_total'] = '';
                $product[$proIndex]['total_weight'] = '';
                // $oldFinalTotal += $pickData->old_final_total; 
                $oldFinalTotal = $pickData->old_final_total;
                $product[$proIndex]['oldFinalTotal'] = $pickData->old_final_total;

                // $product[$proIndex]['old_final_total'] = $pickData->old_final_total;

                // $product
                $singlePrice = $pickData->product_price;
                if ($offerModel->where('user_id', $supplierId)->where('product_id', $pickData->product_id)->where(DB::raw("date_format(period_from, '%Y-%m-%d %H:%i:%s')"), '<=', date('Y-m-d H:i:s', strtotime($todayDate)))->where(DB::raw("date_format(period_to, '%Y-%m-%d %H:%i:%s')"), '>=', date('Y-m-d H:i:s', strtotime($todayDate)))->count() > 0) {
                    $productOffer = $offerModel->where('user_id', $supplierId)->where('product_id', $pickData->product_id)->where(DB::raw("date_format(period_from, '%Y-%m-%d %H:%i:%s')"), '<=', date('Y-m-d H:i:s', strtotime($todayDate)))->where(DB::raw("date_format(period_to, '%Y-%m-%d %H:%i:%s')"), '>=', date('Y-m-d H:i:s', strtotime($todayDate)))->orderBy('id', 'DESC')->first();
                    if ($productOffer->promotion_id != '') {
                        $singlePrice = $productOffer->promotion_price;
                    } else {
                        $singlePrice = $singlePrice - (($singlePrice * ($productOffer->promotion_price)) / 100);
                    }
                    $product[$proIndex]["productOffer"] = $productOffer;
                } else {
                    $product[$proIndex]["productOffer"] = null;
                }
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

                        if ($offerModel->where('user_id', $supplierId)->where('product_id', $basketProduct->product_id)->where(DB::raw("date_format(period_from, '%Y-%m-%d %H:%i:%s')"), '<=', date('Y-m-d H:i:s', strtotime($todayDate)))->where(DB::raw("date_format(period_to, '%Y-%m-%d %H:%i:%s')"), '>=', date('Y-m-d H:i:s', strtotime($todayDate)))->count() > 0) {
                            $productOffer = $offerModel->where('user_id', $supplierId)->where('product_id', $basketProduct->product_id)->where(DB::raw("date_format(period_from, '%Y-%m-%d %H:%i:%s')"), '<=', date('Y-m-d H:i:s', strtotime($todayDate)))->where(DB::raw("date_format(period_to, '%Y-%m-%d %H:%i:%s')"), '>=', date('Y-m-d H:i:s', strtotime($todayDate)))->orderBy('id', 'DESC')->first();
                            if ($productOffer->promotion_id != '') {
                                $singlePrice = $productOffer->promotion_price;
                            } else {
                                $singlePrice = $singlePrice - (($singlePrice * ($productOffer->promotion_price)) / 100);
                            }
                            $supplierProductLoopData["productOffer"] = $productOffer;
                            $supplierProductLoopData["offer_price"] = $productOffer->promotion_price;
                            $supplierProductLoopData["offer_id"] = $productOffer->uuid;
                        } else {
                            $supplierProductLoopData["productOffer"] = null;
                            $supplierProductLoopData["offer_price"] = 0;
                            $supplierProductLoopData["offer_id"] = null;
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
                                // $supplierProductLoopData["totalprice"] = $singlePrice;
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
                            $supplierProductLoopData["shippingTotal"] = $shippingTotal;


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

        // dd($supplierLoopData);
        // $trader = auth()->user()->uuid;
        // $supplier = $request->supplier_id;

        $supplier = UserCompany::where('owner_user_id', $salesData->supplier_id)->first();
        if ($supplier != null) {
            $supplierAddress = $this->getAddress($supplier);
        } else {
            $supplierAddress = '';
        }
        $trader = UserCompany::where('owner_user_id', $salesData->user_id)->first();
        if ($trader != null) {
            $traderAddress = $this->getAddress($trader);
        } else {
            $traderAddress = '';
        }



        $walletBalance = auth()->user()->wallet_balance;

        $pageTitle = "Picking Document";

        $bodyClass = ['about-us'];

        if (Session::has('requestData')) {

            Session::forget('requestData');
        }

        Session::push('requestData', $request->all());

        if ($oldFinalTotal == 0) {
            $creditNote = 0;
        } else {
            $creditNote = $oldFinalTotal - $paybel_amt_input;
        }

        return view('supplier.document.pickingDocument', compact('pageTitle', 'bodyClass', 'supplierLoopData', 'productTotal', 'shippingMethod', 'shippingTotal', 'walletBalance', 'supplierId', 'offerTotal', 'offerId', 'paybel_amt_input', 'item_tax_input', 'distance', 'weight', 'supplierData', 'productOffer', 'creditNote', 'supplierAddress', 'traderAddress'));
    }

    public function pickingDocUpdate(Request $request, Basket $basketModel, Product $productModel, User $userModel)
    {

        // dd($request->all());
        $bascketProducts = BasketProducts::where('basket_id', $request->basket_id)->get();

        $basketData = $basketModel->where('uuid', $request->basket_id)->select('order_id')->first();
        // $salesData = SalesOrder::where('uuid',$basketData->order_id)->get();
        $salesData = SalesOrder::where('uuid', $basketData->order_id)->select('shipment_amount', 'discount_amount', 'tax_amount')->first();

        $pickingData = PickingDocument::where('order_id', $request->order_id)->where('product_id', $request->product_id)->first();

        $cart_amount = $request->product_price * $request->single_qty;
        $request->merge([
            'cart_amount' => $cart_amount,
            'final_total' => ($cart_amount - $salesData->discount_amount) + $salesData->shipment_amount + $salesData->tax_amount
        ]);

        $request->request->remove('_token');

        if ($pickingData == null) {
            foreach ($bascketProducts as $product) {
                // shipment_amount
                $salesData = SalesOrder::where('uuid', $basketData->order_id)->first();
                $shipment_amount = $salesData->final_total - $salesData->cart_amount;
                PickingDocument::create([
                    'order_id' => $request->order_id,
                    'product_id' => $product->product_id,
                    'basket_products_id' => $request->basket_products_id,
                    'basket_id' => $product->basket_id,
                    'product_price' => $product->product_price,
                    'single_qty' => $product->single_qty,
                    'shipment_amount' => $shipment_amount,
                    'discount_amount' => $salesData->discount_amount,
                    'tax_amount' => $salesData->tax_amount,
                    'cart_amount' => $product->product_price * $product->single_qty,
                    'final_total' => ($product->product_price * $product->single_qty - $salesData->discount_amount) + $salesData->shipment_amount + $salesData->tax_amount,
                    'old_qnty' => $product->single_qty,
                    'old_final_total' => ($product->product_price * $product->single_qty - $salesData->discount_amount) + $salesData->shipment_amount + $salesData->tax_amount,
                    'color' => $product->color,
                    'size' => $product->size,
                    'offer_price' => $request->offer_price,
                    'offer_id' => $request->offer_id,
                ]);
            }
            PickingDocument::where('order_id', $request->order_id)->update(['old_final_total' => PickingDocument::where('order_id', $request->order_id)->sum('old_final_total')]);

            PickingDocument::where('order_id', $request->order_id)->where('product_id', $request->product_id)->update($request->all());
        } else {
            PickingDocument::where('order_id', $request->order_id)->where('product_id', $request->product_id)->update($request->all());
            $pickingData = PickingDocument::where('order_id', $request->order_id)->where('product_id', $request->product_id)->first();


            $bascketProducts = BasketProducts::where('basket_id', $request->basket_id)->where('product_id', $request->product_id)->update([
                'single_qty' => $pickingData->old_qnty - $pickingData->single_qty,
                'product_price' => ($pickingData->old_qnty - $pickingData->single_qty) * $pickingData->product_price
            ]);
        }


        return redirect()->back()->with(['status' => 'success']);
    }

    public function dispatchDocUpdate(Request $request, Basket $basketModel, Product $productModel, User $userModel)
    {

        $bascketProducts = BasketProducts::where('basket_id', $request->basket_id)->get();

        $basketData = $basketModel->where('uuid', $request->basket_id)->select('order_id')->first();
        // $salesData = SalesOrder::where('uuid',$basketData->order_id)->get();
        $salesData = SalesOrder::where('uuid', $basketData->order_id)->select('shipment_amount', 'discount_amount', 'tax_amount')->first();

        $pickingData = DispatchedDocument::where('order_id', $request->order_id)->where('product_id', $request->product_id)->first();
        // $pickingData = PickingDocument::where('order_id',$request->order_id)->where('product_id',$request->product_id)->first();

        $cart_amount = $request->product_price * $request->single_qty;
        $request->merge([
            'cart_amount' => $cart_amount,
            'final_total' => ($cart_amount - $salesData->discount_amount) + $salesData->shipment_amount + $salesData->tax_amount
        ]);

        $request->request->remove('_token');

        if ($pickingData == null) {
            foreach ($bascketProducts as $product) {
                $salesData = SalesOrder::where('uuid', $basketData->order_id)->first();
                DispatchedDocument::create([
                    'order_id' => $request->order_id,
                    'product_id' => $product->product_id,
                    'basket_products_id' => $request->basket_products_id,
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
                    'offer_price' => $request->offer_price,
                    'offer_id' => $request->offer_id,
                ]);
            }
            DispatchedDocument::where('order_id', $request->order_id)->update(['old_final_total' => DispatchedDocument::where('order_id', $request->order_id)->sum('old_final_total')]);

            DispatchedDocument::where('order_id', $request->order_id)->where('product_id', $request->product_id)->update($request->all());
        } else {
            DispatchedDocument::where('order_id', $request->order_id)->where('product_id', $request->product_id)->update($request->all());
            $pickingData = DispatchedDocument::where('order_id', $request->order_id)->where('product_id', $request->product_id)->first();


            $bascketProducts = BasketProducts::where('basket_id', $request->basket_id)->update([
                'single_qty' => $pickingData->old_qnty - $pickingData->single_qty,
                'product_price' => ($pickingData->old_qnty - $pickingData->single_qty) * $pickingData->product_price
            ]);
        }


        return redirect()->back()->with(['status' => 'success']);
    }

    public function resetPickingDoc($order_id)
    {
        PickingDocument::where('order_id', $order_id)->delete();
        Basket::where('order_id', $order_id)->update([
            'is_modify' => '0'
        ]);
        return redirect()->back()->with(['status' => 'success']);
    }

    public function resetDispatchDoc($order_id)
    {
        DispatchedDocument::where('order_id', $order_id)->delete();
        Basket::where('order_id', $order_id)->update([
            'is_modify' => '0'
        ]);
        return redirect()->back()->with(['status' => 'success']);
    }

    public function removeProductDoc($order_id, $product_id)
    {
        $existData = PickingDocument::where('order_id', $order_id)->where('product_id', $product_id)->get();
        if (count($existData) != 0) {
            PickingDocument::where('order_id', $order_id)->where('product_id', $product_id)->delete();
            // $basketModel->where('order_id',$order_id)->update([
            //     'is_modify' => '1'
            // ]);
        } else {


            $basket = Basket::where('order_id', $order_id)->first();
            $bascketProducts = BasketProducts::where('basket_id', $basket->uuid)->get();
            $pickingData = PickingDocument::where('order_id', $order_id)->where('product_id', $product_id)->first();

            if ($pickingData == null) {
                foreach ($bascketProducts as $product) {
                    $salesData = SalesOrder::where('uuid', $order_id)->first();
                    PickingDocument::create([
                        'order_id' => $order_id,
                        'product_id' => $product->product_id,
                        'basket_products_id' => $product->basket_products_id,
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
                PickingDocument::where('order_id', $order_id)->update(['old_final_total' => PickingDocument::where('order_id', $order_id)->sum('old_final_total')]);

                PickingDocument::where('order_id', $order_id)->where('product_id', $product_id)->delete();
            }
        }

        return redirect()->back()->with(['status' => 'success']);
    }

    public function removeProductDocDispatch($order_id, $product_id)
    {
        DispatchedDocument::where('order_id', $order_id)->where('product_id', $product_id)->delete();
        // $basketModel->where('order_id',$order_id)->update([
        //     'is_modify' => '1'
        // ]);
        return redirect()->back()->with(['status' => 'success']);
    }

    public function creditNote($order_id)
    {
        $pickingData = PickingDocument::leftjoin('sales_orders', 'sales_orders.uuid', '=', 'picking_documents.order_id')
            ->leftjoin('dispatched_documents', 'dispatched_documents.order_id', '=', 'sales_orders.uuid')
            ->leftjoin('products', 'products.uuid', '=', 'picking_documents.product_id')
            ->leftjoin('promotions', 'promotions.product_id', '=', 'products.uuid')
            ->where('picking_documents.order_id', $order_id)
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
            ->where('sales_orders.uuid', $order_id)
            ->select('sales_orders.*', 'baskets.uuid as basket_id')
            ->first();

        $supplier = UserCompany::where('owner_user_id', $salesData->supplier_id)->first();
        if ($supplier != null) {
            $supplierAddress = $this->getAddress($supplier);
        } else {
            $supplierAddress = '';
        }
        $trader = UserCompany::where('owner_user_id', $salesData->user_id)->first();
        if ($trader != null) {
            $traderAddress = $this->getAddress($trader);
        } else {
            $traderAddress = '';
        }

        $supplierData = User::where('uuid', $salesData->supplier_id)->with('company')->first();

        // return view('supplier.document.creditNoteForPDF',compact('pickingData','supplierData'));
        return view('supplier.document.creditNote', compact('pickingData', 'supplierData', 'supplierAddress', 'traderAddress'));
    }

    public function supplierOwnInvoice(Request $request)
    {
        // dd($request->all());
        $data = uploadFile($request, 'supplier_own_invoice', $request->order_id, $request->order_number);
        return redirect()->back();
    }

    public function getDispatchDocument(Request $request, Basket $basketModel, SupplierItemInventory $supplierItemInventoryModel, Promotion $offerModel, $order_id)
    {

        $pickingData = DispatchedDocument::where('order_id', $order_id)->first();
        // $pickingData = PickingDocument::where('order_id',$order_id)->first();
        // dd($pickingData);


        $salesData = SalesOrder::leftjoin('baskets', 'baskets.order_id', 'sales_orders.uuid')
            ->where('sales_orders.uuid', $order_id)
            ->select('sales_orders.*', 'baskets.uuid as basket_id')
            ->first();
        $supplierData = User::where('uuid', $salesData->supplier_id)->with('company')->first();

        if ($pickingData != null) {

            $PickingDocumentData = DispatchedDocument::where('order_id', $order_id)->get();
            $cart_amount = 0;
            foreach ($PickingDocumentData as $data) {
                if (isset($data->offer_price) && $data->offer_price > 0) {
                    $qnty = $data->single_qty * $data->offer_price;
                } else {
                    $qnty = $data->single_qty * $data->product_price;
                }
                $cart_amount = $cart_amount + $qnty;
            }

            // $cart_amount = DispatchedDocument::where('order_id', $order_id)->sum('cart_amount');
            $shipment_amount = DispatchedDocument::where('order_id', $order_id)->sum('shipment_amount');
            $final_total = DispatchedDocument::where('order_id', $order_id)->sum('final_total');
            $tax_amount = DispatchedDocument::where('order_id', $order_id)->sum('tax_amount');

            $productTotal = $cart_amount;
            $shippingTotal = $salesData->shipment_amount;
            // $shippingTotal = $shipment_amount;
            $paybel_amt_input = $cart_amount + $shippingTotal;
            // $paybel_amt_input = $final_total;
            $item_tax_input = $tax_amount;
        } else {

            $productTotal = $salesData->cart_amount;

            $shippingTotal = $salesData->shipment_amount;

            $paybel_amt_input = $salesData->final_total;

            $item_tax_input = $salesData->tax_amount;
        }

        // dd($paybel_amt_input);
        $distance = $salesData->distance;

        $weight = $salesData->total_weight;



        $shippingMethod = $salesData->delivery_type;

        $supplierId = $salesData->supplier_id;

        $offerTotal = $salesData->offer_total;

        $offerId = $salesData->offer_id;





        $basketId = $salesData->basket_id;



        $todayDate = Carbon::now()->format('Y-m-d');

        $basket = $basketModel->where('uuid', $basketId)->first();

        $basketProductIds = $basket->products()->pluck('product_id')->toArray();

        $basketProducts = $basket->products;

        $productOffer = '';

        $supplierLoopData = [];

        $supplierLoopData["products"] = [];

        $totalWeight = 0;

        $totalProducts = 0;

        $totalAvailableProducts = 0;

        $total = 0;

        $oldFinalTotal = 0;

        $pickingAllData = DispatchedDocument::where('order_id', $order_id)->get();

        $cart_amount = 0;
        if (count($pickingAllData) != 0) {
            $product = [];
            foreach ($pickingAllData as $proIndex => $pickData) {

                $productData = Product::where('uuid', $pickData->product_id)->first();

                if (isset($pickData->offer_price) && $pickData->offer_price > 0) {
                    $qnty = $pickData->single_qty * $pickData->offer_price;
                } else {
                    $qnty = $pickData->single_qty * $pickData->product_price;
                }
                $cart_amount = $cart_amount + $qnty;

                $product[$proIndex]['product_id'] = $pickData->product_id;
                $product[$proIndex]['product_name'] = $productData->name;
                $product[$proIndex]['qty'] = $pickData->single_qty;
                $product[$proIndex]['productSinglePrice'] = $pickData->product_price;
                $product[$proIndex]['price'] = $pickData->product_price;
                $product[$proIndex]['totalprice'] = $cart_amount;
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
                $product[$proIndex]['offer_price'] = $pickData->offer_price;
                $product[$proIndex]['offer_id'] = $pickData->offer_id;
                $product[$proIndex]['exist_picking'] = '1';
                $product[$proIndex]['color'] = $pickData->color;
                $product[$proIndex]['size'] = $pickData->size;

                // $itemWeight += $basketProduct->product->getCalculatedWeight("single", $basketProduct->single_qty);

                // $rowTotal += ($basketProduct->single_qty * $singlePrice);
                $product[$proIndex]['row_total'] = '';
                $product[$proIndex]['total_weight'] = '';
                // $oldFinalTotal += $pickData->old_final_total; 
                $oldFinalTotal = $pickData->old_final_total;
                $product[$proIndex]['oldFinalTotal'] = $pickData->old_final_total;

                // $product[$proIndex]['old_final_total'] = $pickData->old_final_total;

                // $product
                $singlePrice = $pickData->product_price;
                if ($offerModel->where('user_id', $supplierId)->where('product_id', $pickData->product_id)->where(DB::raw("date_format(period_from, '%Y-%m-%d %H:%i:%s')"), '<=', date('Y-m-d H:i:s', strtotime($todayDate)))->where(DB::raw("date_format(period_to, '%Y-%m-%d %H:%i:%s')"), '>=', date('Y-m-d H:i:s', strtotime($todayDate)))->count() > 0) {
                    $productOffer = $offerModel->where('user_id', $supplierId)->where('product_id', $pickData->product_id)->where(DB::raw("date_format(period_from, '%Y-%m-%d %H:%i:%s')"), '<=', date('Y-m-d H:i:s', strtotime($todayDate)))->where(DB::raw("date_format(period_to, '%Y-%m-%d %H:%i:%s')"), '>=', date('Y-m-d H:i:s', strtotime($todayDate)))->orderBy('id', 'DESC')->first();
                    if ($productOffer->promotion_id != '') {
                        $singlePrice = $productOffer->promotion_price;
                    } else {
                        $singlePrice = $singlePrice - (($singlePrice * ($productOffer->promotion_price)) / 100);
                    }
                    $product[$proIndex]["productOffer"] = $productOffer;
                } else {
                    $product[$proIndex]["productOffer"] = null;
                }
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

                        // if ($offerModel->where('user_id', $supplierId)->where('products_id', $basketProduct->product_id)->whereDate('end_date', '>=', $todayDate)->count() > 0) {
                        //     $productOffer = $offerModel->where('user_id', $supplierId)->where('products_id', $basketProduct->product_id)->whereDate('end_date', '>=', $todayDate)->orderBy('id', 'DESC')->first();
                        //     if ($productOffer->offer_type == 'RENT') {
                        //         $singlePrice = $singlePrice - ($productOffer->offer_value);
                        //     } else {
                        //         $singlePrice = $singlePrice - (($singlePrice * ($productOffer->offer_value)) / 100);
                        //     }
                        // }

                        if ($offerModel->where('user_id', $supplierId)->where('product_id', $basketProduct->product_id)->where(DB::raw("date_format(period_from, '%Y-%m-%d %H:%i:%s')"), '<=', date('Y-m-d H:i:s', strtotime($todayDate)))->where(DB::raw("date_format(period_to, '%Y-%m-%d %H:%i:%s')"), '>=', date('Y-m-d H:i:s', strtotime($todayDate)))->count() > 0) {
                            $productOffer = $offerModel->where('user_id', $supplierId)->where('product_id', $basketProduct->product_id)->where(DB::raw("date_format(period_from, '%Y-%m-%d %H:%i:%s')"), '<=', date('Y-m-d H:i:s', strtotime($todayDate)))->where(DB::raw("date_format(period_to, '%Y-%m-%d %H:%i:%s')"), '>=', date('Y-m-d H:i:s', strtotime($todayDate)))->orderBy('id', 'DESC')->first();
                            if ($productOffer->promotion_id != '') {
                                $singlePrice = $productOffer->promotion_price;
                            } else {
                                $singlePrice = $singlePrice - (($singlePrice * ($productOffer->promotion_price)) / 100);
                            }
                            $supplierProductLoopData["productOffer"] = $productOffer;
                            $supplierProductLoopData["offer_price"] = $productOffer->promotion_price;
                            $supplierProductLoopData["offer_id"] = $productOffer->uuid;
                        } else {
                            $supplierProductLoopData["productOffer"] = null;
                            $supplierProductLoopData["offer_price"] = 0;
                            $supplierProductLoopData["offer_id"] = null;
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
                            // dd($productData);

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
                            $supplierProductLoopData['color'] = $basketProduct->color;
                            $supplierProductLoopData['size'] = $basketProduct->size;

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




        //    dd($supplierLoopData);

        $walletBalance = auth()->user()->wallet_balance;

        $pageTitle = "Dispatched Docuement";

        $bodyClass = ['about-us'];

        if (Session::has('requestData')) {

            Session::forget('requestData');
        }

        Session::push('requestData', $request->all());

        // dd($paybel_amt_input,$oldFinalTotal);
        if ($oldFinalTotal == 0) {
            $creditNote = 0;
        } else {
            $creditNote = $oldFinalTotal - $paybel_amt_input;
        }

        $supplier = UserCompany::where('owner_user_id', $salesData->supplier_id)->first();
        if ($supplier != null) {
            $supplierAddress = $this->getAddress($supplier);
        } else {
            $supplierAddress = '';
        }
        $trader = UserCompany::where('owner_user_id', $salesData->user_id)->first();
        if ($trader != null) {
            $traderAddress = $this->getAddress($trader);
        } else {
            $traderAddress = '';
        }


        // return view('supplier.document.dispatchedDocumentForPDF', compact('pageTitle', 'bodyClass','supplierLoopData', 'productTotal', 'shippingMethod', 'shippingTotal', 'walletBalance', 'supplierId','offerTotal','offerId', 'paybel_amt_input', 'item_tax_input', 'distance', 'weight', 'supplierData', 'productOffer','creditNote'));
        return view('supplier.document.dispatchedDocument', compact('pageTitle', 'bodyClass', 'supplierLoopData', 'productTotal', 'shippingMethod', 'shippingTotal', 'walletBalance', 'supplierId', 'offerTotal', 'offerId', 'paybel_amt_input', 'item_tax_input', 'distance', 'weight', 'supplierData', 'productOffer', 'creditNote', 'supplierAddress', 'traderAddress'));
    }

    public function postPaymentMethod(Request $request, WalletTransactions $walletTransactionModel, SalesOrder $salesOrder, Basket $basketModel,  User $user, OffercodeUsedby $codeModel, SupplierItemInventory  $inventary, OfferDeals $offerModel, basketProducts $basketProductModel)

    {
        $currentUser = auth()->user();

        $cartAmount = $request->get("order_amount", 0);

        $discountAmount = $request->get("discount_amount", 0);

        $offer_id = $request->get("offer_id", 0);

        $shippingMethod = $request->get("delivery_type", "pickup");

        // if ($shippingMethod == 'delivery' || $shippingMethod == 'own_distributor') {
        $shipmentAmount = $request->get("shipping_amount", 0);
        // } else {
        //     $shipmentAmount = 0;
        // }
        // if ($shippingMethod == 'delivery') {
        //     $shipmentAmount = $request->get("shipping_amount", 0);
        // } else {
        //     $shipmentAmount = 0;
        // }

        $itemTax = $request->get("itemTax", 0);

        $amtPayble = $request->get("amtPayble", 0);

        $totalAmount = $amtPayble; //+ $shipmentAmount;

        $supplierId = $request->get("supplier_id", 0);

        $distance = $request->get("distance", 0);

        $weight = $request->get("weight", 0);
        $delivery_requested = $request->get("delivery_requested");

        // dd($distance,$weight);

        // $route = $this->route;



        /***Inventery Stock decrease from suppler Entry***/

        $basketId = session()->get("basket_id");

        $todayDate = Carbon::now()->format('Y-m-d');

        $basket = $basketModel->where('uuid', $basketId)->first();

        $products = $basket->products;

        // echo "<pre>";
        // print_r($basketId);die();   

        foreach ($products as $pro) {

            if ($inventary->where('product_id', $pro->product_id)->where('user_id', $supplierId)->count() > 0) {

                $inventary->where('product_id', $pro->product_id)->where('user_id', $supplierId)->decrement('single', $pro->single_qty);
            } else {

                $basket = $basketModel->where('uuid', $basketId)->first();

                $basket->products()->where('product_id', $pro->product_id)->delete();
            }
        }

        /***Sales Entry***/

        $salesOrderData = $salesOrder->create([

            'user_id' => auth()->user()->uuid,

            'supplier_id' => $supplierId,

            //           'logistic_id',

            'cart_amount' => $cartAmount,

            'shipment_amount' => $shipmentAmount,

            'discount_amount' => $discountAmount,

            'tax_amount' => $itemTax,

            'final_total' => $totalAmount,

            'order_status' => SalesOrder::ORDERPLACED,
            // 'order_status' => "PLACED",

            'payment_status' => "PENDING",

            'delivery_type' => $shippingMethod,

            'total_weight' => $weight,

            'distance' => $distance,

            'delivery_requested' => $delivery_requested

        ]);


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



        $req_data = array();

        // $req_data = array();

        /***********************Notification to supplier when order placed ************************/

        $req_data['order_status'] = SalesOrder::ORDERPLACED;
        // $req_data['order_status'] = "ORDER PLACED";
        // $req_data['order_status'] = "PLACED";

        $req_data['order_uuid'] = $salesOrderData->uuid;

        $req_data['user_id'] = $supplierId;

        $req_data['delivery_type'] = $shippingMethod;

        $url = "user.sales-orders.edit";
        $message = 'Order Successfully Placed - Track your Order Here <br><a href="' . route("user.sales-orders.edit", $salesOrderData->uuid) . '" class="btn btn-success ml-2">Track my Order</a>';

        $statusNotify = SalesOrder::ORDERPLACED;
        $traderData = User::where('uuid', auth()->user()->uuid)->first();
        $usernotifyModel = new Notification;

        $todayDate = Carbon::now()->format('Y-m-d');
        $notify_msg = 'New Order from ' . env('APP_NAME') . ' with Order no. #' . $salesOrderData->order_number . ' on ' . $todayDate . '.';
        $notify = $usernotifyModel->create(['user_id' => $req_data['user_id'], 'order_id' => $req_data['order_uuid'], 'notification' => $notify_msg]);

        ChangeOrderStatus::orderStatus($req_data); //notify supplier 



        /***********************Notification to supplier Close ************************/



        /***Offer User BY this user Entry***/

        if ($offer_id != 0) {

            $codeModel->create([

                'user_id' =>  auth()->user()->uuid,

                'offer_id' => $offer_id,

                'order_id' => $salesOrderData->uuid

            ]);
        }


        $amt = $cartAmount + $itemTax - $discountAmount;

        $settings = new  Setting;

        $charge_itz = $settings->get("itz_supplier_charge");
        $charge_itz_transport = $settings->get("itz_transporter_charge");

        $admin_user = $user->where('role', 'ADMIN')->first();

        /***Wallet Entry***/

        $walletTransactionModel->create([

            "credit_amount" => $amt - (($amt * $charge_itz) / 100),

            "debit_amount" => 0,

            "user_id" => $supplierId,

            "remarks" => "SUPPILER SELL PRODUCT",

            "status" => "PENDING",

            "order_id" => $salesOrderData->uuid,

            "admin_charge" => $charge_itz
        ]);

        $walletTransactionModel->create([

            "credit_amount" => (($amt * $charge_itz) / 100),

            "debit_amount" => 0,

            "user_id" => $admin_user->uuid,

            "remarks" => "ADMIN CHARGE FOR ORDER",

            "status" => "PENDING",

            "order_id" => $salesOrderData->uuid,

            "admin_charge" => $charge_itz

        ]);

        // if($shippingMethod == 'delivery'){ 

        $walletTransactionModel->create([

            "credit_amount" => 0,

            "debit_amount" => $shipmentAmount,

            "user_id" => auth()->user()->uuid,

            "remarks" => "SHIPMENT CHARGE",

            "status" => "PENDING",

            "order_id" => $salesOrderData->uuid,

            "admin_charge" => $charge_itz_transport

        ]);

        // }

        $walletTransactionModel->create([

            "credit_amount" => 0,

            "debit_amount" => $amt,

            "user_id" => auth()->user()->uuid,

            "remarks" => "BUY PRODUCT",

            "status" => "PENDING",

            "order_id" => $salesOrderData->uuid,

            "admin_charge" => $charge_itz
        ]);



        //Send Mail

        $data = [

            'user' => auth()->user(),

            'subject' => 'PAYMENT SUMMARY'

        ];

        $requestData = session('requestData');

        $orderId = $salesOrderData->uuid;

        $this->pdfForPaymentSummary($requestData[0], $data, $orderId);

        //End



        /***Product Entery of basket update order id***/

        $basketId = session()->get("basket_id");

        $basket = $basketModel->where("uuid", $basketId)->update(["order_id" => $salesOrderData->uuid, 'is_modify' => '0']);





        session()->forget('basket_id');

        session(["order_id" => $salesOrderData->uuid]);

        // dd('test');
        sendOrderStatusEmail($message, $traderData->email, $statusNotify);

        return redirect()->route("success")->with(['status' => 'success', 'message' => "Order placed successfully"]);
    }

    public function success(SalesOrder $salesOrderModel)

    {



        $orderId = session()->get("order_id");

        if ($salesOrderModel->where("uuid", $orderId)->count() > 0) {

            $order = $salesOrderModel->where("uuid", $orderId)->first();

            $orderNumber = $order->order_number;

            $pageTitle = "Success";

            $bodyClass = ['about-us'];

            return view('frontend.home.success', compact('pageTitle', 'bodyClass', 'orderNumber', 'order'));
        } else {

            return redirect("/");
        }
    }



    public function requestQuotePost(AdminRequestQuoteRequest $request, RequestQuote $request_quote)
    {

        $arr = $request->all();

        if ($request->hasFile('attachment') && $request->file('attachment')->isValid()) {

            $file = $request_quote->uploadMedia($request->file('attachment'));

            unset($arr['attachment']);

            $arr['attachment'] = $file['path'] . $file['name'];
        }

        $request_quote->create($arr);

        return redirect()->action('FrontendHomeController@requestQuote')->with(['status' => 'success', 'message' => "Thanks for contact us, our team will get back to you soon"]);
    }



    public function contactSubmit(FrontendContactSubmitRequest $request)

    {
        $admin_email = env('MAIL_USERNAME');
        Mail::to($admin_email)->cc("mkc110891@gmail.com")->send(new ContactMail($request->all()));

        return redirect()->back()->with(['status' => 'success', 'message' => "Thanks for contact us, our team will get back to you soon"]);
    }

    public function reportAbuseSubmit(FrontendReportAbuseSubmitRequest $request)
    {
        $admin_email = env('MAIL_USERNAME');
        Mail::to($admin_email)->cc("mkc110891@gmail.com")->send(new ReportAbuseMail($request->all()));

        return redirect()->back()->with(['status' => 'success', 'message' => "Thanks for report, our team will get back to you soon"]);
    }

    public function feedbackSubmit(FrontendFeedbackSubmitRequest $request)
    {
        Mail::to($admin_email)->cc("mkc110891@gmail.com")->send(new FeedbackMail($request->all()));

        return redirect()->back()->with(['status' => 'success', 'message' => "Thanks for feedback, our team will get back to you soon"]);
    }

    public function cmsPages($slug)
    {

        $cmsData = CMSModule::whereNull('deleted_at')->where('slug', $slug)->first();

        if (isset($cmsData) && !empty($cmsData)) {

            $pageTitle = ucfirst($slug);

            $bodyClass = [];

            return view('frontend.home.pages', compact('pageTitle', 'bodyClass', 'cmsData'));
        } else {

            return redirect()->action('FrontendHomeController@home');
        }
    }





    public function pdfForPaymentSummary($request, $data, $orderId)
    {

        $currentUser = auth()->user();
        $admin = User::whereNull('deleted_at')->where([['status', 'ACTIVE'], ['role', 'ADMIN']])->first();
        $adminEmail = $admin->email;

        $userCompany = new UserCompany;
        $userCompanyData = $userCompany->where('owner_user_id', $currentUser->uuid)->first();

        // $orderId = session()->get("order_id");
        $order = SalesOrder::where('uuid', $orderId)->first();
        $invoiceNo = $order->order_number;


        $productTotal = $request["product_total"] ?? 0;

        $shippingMethod = $request["delivery_type"] ?? "pickup";
        $delivery_status = $request["delivery_status"];

        // $shippingTotal = $request["shipping_total"] ??  0;
        if ($shippingMethod == 'own_distributor') {
            $supplierDataNew =  User::where('uuid', $request['supplier_id'])->first();
            $shippingTotal = ($supplierDataNew->delivery_rate != "") ? $supplierDataNew->delivery_rate : 0;
        } else if ($shippingMethod == 'delivery') {
            $shippingTotal = $request["shipping_total"] ??  0;
            // $shippingTotal = $request->get("shipping_total", 0);
        } else {
            $shippingTotal = 0;
        }


        $supplierId = $request["supplier_id"] ??  null;

        $offerTotal = $request["offer_total"] ?? null;

        $offerId = $request["offer_id"] ??  0;

        $paybel_amt_input = $request["paybel_amt_input"] ?? 0;

        $item_tax_input = $request["item_tax_input"] ?? 0;



        $basketId = session()->get('basket_id', null);

        if (is_null($basketId)) {

            return redirect()->back()->with(['status' => 'danger', 'message' => trans('warning.frontend|somethingWentWrong')]);
        }



        $basket = Basket::where('uuid', $basketId)->first();



        $basketProductIds = $basket->products()->pluck('product_id')->toArray();

        $basketProducts = $basket->products;



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

                if (SupplierItemInventory::where('product_id', $basketProduct->product_id)->where('user_id', $supplierId)->count() > 0) {

                    $supplierLatestRate = SupplierItemInventory::where('product_id', $basketProduct->product_id)->where('user_id', $supplierId)->orderBy('id', 'DESC')->first();

                    $singlePrice = $supplierLatestRate->single_price;

                    $offerModel = new Promotion;
                    $todayDate = Carbon::now()->format('Y-m-d');

                    \Log::info('hi');
                    \Log::info($singlePrice);
                    \Log::info('hi1');

                    if ($offerModel->where('user_id', $supplierId)->where('product_id', $basketProduct->product_id)->where(\DB::raw("date_format(period_from, '%Y-%m-%d %H:%i:%s')"), '<=', date('Y-m-d H:i:s', strtotime($todayDate)))->where(DB::raw("date_format(period_to, '%Y-%m-%d %H:%i:%s')"), '>=', date('Y-m-d H:i:s', strtotime($todayDate)))->count() > 0) {
                        $productOffer = $offerModel->where('user_id', $supplierId)->where('product_id', $basketProduct->product_id)->where(\DB::raw("date_format(period_from, '%Y-%m-%d %H:%i:%s')"), '<=', date('Y-m-d H:i:s', strtotime($todayDate)))->where(DB::raw("date_format(period_to, '%Y-%m-%d %H:%i:%s')"), '>=', date('Y-m-d H:i:s', strtotime($todayDate)))->orderBy('id', 'DESC')->first();
                        \Log::info($productOffer);
                        \Log::info('hi12');

                        if ($productOffer->promotion_id != '') {
                            $singlePrice = $productOffer->promotion_price;
                            \Log::info($singlePrice);
                            \Log::info('hi123');
                        } else {
                            $singlePrice = $singlePrice - (($singlePrice * ($productOffer->promotion_price)) / 100);
                            \Log::info($singlePrice);
                            \Log::info('hi124');
                        }
                        $supplierProductLoopData["productOffer"] = $productOffer;
                    } else {
                        $supplierProductLoopData["productOffer"] = null;
                    }




                    // $productOffer = $offerModel->where('user_id', $supplierId)->where('product_id', $basketProduct->product_id)->where(\DB::raw("date_format(period_from, '%Y-%m-%d %H:%i:%s')"), '<=', date('Y-m-d H:i:s', strtotime($todayDate)))->where(DB::raw("date_format(period_to, '%Y-%m-%d %H:%i:%s')"), '>=', date('Y-m-d H:i:s', strtotime($todayDate)))->orderBy('id', 'DESC')->first();
                    \Log::info($singlePrice);
                    \Log::info('hi125');
                    $itemWeight = 0;

                    $totalAvailableProducts++;



                    $productName = $supplierLatestRate->product->name;

                    $productId = $supplierLatestRate->product->uuid;

                    $supplierProductLoopData["product_id"] = $productId;
                    $supplierProductLoopData["product_name"] = $productName;



                    if ($basketProduct->single_qty > 0) {

                        $supplierProductLoopData["qty"] = $basketProduct->single_qty;

                        $supplierProductLoopData["price"] = $supplierLatestRate->single_price;
                        // $supplierProductLoopData["price"] = $singlePrice;

                        $supplierProductLoopData["totalprice"] = ($basketProduct->single_qty * $singlePrice);
                        $supplierProductLoopData["color"] =  $basketProduct->color;
                        $supplierProductLoopData["size"] = $basketProduct->size;

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





        $walletBalance = auth()->user()->wallet_balance;

        $pageTitle = "Make Payment";

        $bodyClass = ['about-us'];

        set_time_limit(360);

        $removebtn = 1;

        $phone = '+88 0123 4567 890, +88 0123 4567 999';
        $facebook_url = 'https://www.facebook.com/';
        $instagram_url = 'https://www.instagram.com/';
        $twitter_url = 'https://www.twitter.com/';
        $pinterest_url = 'https://www.pinterest.com/';

        $app_name = env('MAIL_FROM_NAME');
        $from = env('MAIL_FROM_ADDRESS');

        $supplierData = User::where('uuid', $supplierId)->with('company')->first();



        $pdf = PDF::loadView('frontend.checkout.invoiceEmail', compact('supplierLoopData', 'productTotal', 'shippingMethod', 'shippingTotal', 'walletBalance', 'supplierId', 'offerTotal', 'offerId', 'paybel_amt_input', 'item_tax_input', 'removebtn', 'invoiceNo', 'userCompanyData', 'currentUser', 'supplierData', 'delivery_status'))->setPaper('a4');

        $email = EmailTemplate::where('name', '=', 'order_placed')->first();

        if (isset($email)) {
            $email->description = str_replace('[ADMIN_NAME]', $admin->first_name . ' ' . $admin->last_name, $email->description);
            $email->description = str_replace('[CUSTOMER_NAME]', $data['user']['first_name'] . ' ' . $data['user']['last_name'], $email->description);
            $email->description = str_replace('[INVOICE_NO]', $invoiceNo, $email->description);
            $email->description = str_replace('[EMAIL]', env('SUPPORT'), $email->description);
            $email->description = str_replace('[SITE_NAME]', env('WEBSITE'), $email->description);
            $email->description = str_replace('[PHONE]', $phone, $email->description);
            $email->description = str_replace('[FACEBOOK_URL]', $facebook_url, $email->description);
            $email->description = str_replace('[TWITTER_URL]', $twitter_url, $email->description);
            $email->description = str_replace('[INSTAGRAM_URL]', $instagram_url, $email->description);
            $email->description = str_replace('[PINTEREST_URL]', $pinterest_url, $email->description);
            $email->description = str_replace('[LOGO]', asset('assets/images/logo.png'), $email->description);
            $email->description = str_replace('[BECOME_SUPPLIER]', asset("assets/frontend/images/Become-Supplier.png"), $email->description);
            $email->description = str_replace('[BECOME_TRANSPORTER]', asset("assets/frontend/images/Become-Driver-1.png"), $email->description);
            $email->description = str_replace('[BECOME_TRADER]', asset("assets/frontend/images/Become-Vender.png"), $email->description);
        }

        $emailContent = $email->description;



        $path = public_path('invoice');
        if (!is_dir($path)) {
            //Create our directory if it does not exist
            mkdir($path);
            // echo "Directory created";
        }

        $fileName =  $invoiceNo . '.' . 'pdf';
        $pdf->save($path . '/' . $fileName);
        $attachPath = $path . '/' . $fileName;

        Mail::send([], [], function ($message) use ($data, $pdf, $emailContent, $adminEmail, $from, $app_name, $attachPath, $fileName) {

            // need to clear config and cache because issue is not getting value from env
            //    $message->to((env('APP_ENV') == 'local') ? 'jigar.signet@gmail.com' : $adminEmail)
            $message->to($adminEmail)
                ->subject('New order placed - ' . env('APP_NAME'))
                ->setBody($emailContent, 'text/html'); // for HTML rich messages
            $message->attachData($pdf->output(), $fileName);
            $message->from($from, $app_name);
        });

        // Mail::send([], $data, function($message) use ($data,$pdf) {

        //     $message->to($data['user']['email'],$data['user']['first_name'])->subject

        //        ($data['subject'])

        //        ->setBody('Hi,'.$data['user']['first_name'].' <br/>Thank you for your recent business with us, we have attached a detailed copy of the invoice " Invoice No." to this email.<br>If you have any questions or concerns regarding this invoice, please do not hesitate to get in touch with us at support@itradezon.com

        //             <br/>

        //             We greatly appreciate your business!

        //             iTradezon.com');

        //        $message->attachData($pdf->output(),'customer.pdf');

        //     $message->from('itradezon@gmail.com','Itradezon');

        //  });


        //trader order invoice

        // $email = EmailTemplate::where('name','=','trader_order_invoice')->first();

        //    if(isset($email)){
        //         $email->description = str_replace('[CUSTOMER_NAME]', $data['user']['first_name'].' '.$data['user']['last_name'], $email->description);
        //         $email->description = str_replace('[INVOICE_NO]', $invoiceNo, $email->description);
        //         $email->description = str_replace('[EMAIL]', 'support@itradezon.com', $email->description);
        //         $email->description = str_replace('[SITE_NAME]', 'iTradezon.com ', $email->description);
        //         $email->description = str_replace('[PHONE]', $phone, $email->description);
        //         $email->description = str_replace('[FACEBOOK_URL]', $facebook_url, $email->description);
        //         $email->description = str_replace('[TWITTER_URL]', $twitter_url, $email->description);
        //         $email->description = str_replace('[INSTAGRAM_URL]', $instagram_url, $email->description);
        //         $email->description = str_replace('[PINTEREST_URL]', $pinterest_url, $email->description);
        //         $email->description = str_replace('[LOGO]', asset('assets/images/logo.png'), $email->description);
        //     } 

        //     $emailContent = $email->description;

        //     Mail::send([], [], function ($message) use ($data, $pdf, $emailContent) {
        //        $message->to('mananmozar786@gmail.com')
        //         ->subject('Trader Order Invoice')
        //         ->setBody($emailContent, 'text/html'); // for HTML rich messages
        //         $message->attachData($pdf->output(),'customer.pdf');
        //         $message->from('itradezon@gmail.com','Itradezon');
        //     });

        //trader order invoice complete




    }

    public function selectCourier($id)
    {
        // dd('hi', $id);
    }
}
