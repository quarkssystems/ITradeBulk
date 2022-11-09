<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/*Authorization Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODA4MFwvYXBpXC92MVwvbG9naW4iLCJpYXQiOjE1NzE1MDc2NzAsImV4cCI6MTU3MTUxMTI3MCwibmJmIjoxNTcxNTA3NjcwLCJqdGkiOiJaU21rME5jZ0l4OU9oaUFFIiwic3ViIjozOSwicHJ2IjoiODdlMGFmMWVmOWZkMTU4MTJmZGVjOTcxNTNhMTRlMGIwNDc1NDZhYSJ9.Ri7xjR-uV-sCZxdwcgVONZal2AEYvKhqDZqQ-9WD4a0
*/

/*Content-Type application/json*/

Route::middleware('auth:api')->get('/user', function (Request $request) {

    return $request->user();
});


Route::group([
    'middleware' => 'api',
    'prefix' => 'v1',
    'namespace' => 'Api'

], function ($router) {

    //Route::post('/hi', 'AuthController@index');
    $router->post('login', 'AuthController@login');
    $router->post('refresh', 'AuthController@refresh');
    $router->post('me', 'AuthController@me');

    $router->post('become-vendor', 'UserController@becomeVendor');
    $router->post('become-supplier', 'UserController@becomesupplier');
    $router->post('become-driver', 'UserController@becomeDriver');

    $router->post('forgot-password', 'UserController@forgotPassword');


    Route::get('get-policy-page', function () {
        //
        return response()->json(['url' => url('/') . '/pages/privacy-policy']);
    });

    Route::get('get-term-page', function () {
        //
        return response()->json(['url' => url('/') . '/pages/terms-condition']);
    });


    $router->post('mark-all-as-read', 'UserController@markAllAsRead');

    $router->post('get-countries', 'UserController@getCountries');
    $router->post('get-state', 'UserController@getState');
    $router->post('get-city', 'UserController@getCity');
    $router->post('get-zipcode', 'UserController@getZipcode');
    $router->post('get-banner', 'UserController@getBanner');
    $router->post('get-ITZPaymentInstruction', 'UserController@getITZPaymentInstruction');


    //Trader home
    $router->post('search-product', 'ProductController@searchProduct');
    $router->post('arrivals-product', 'ProductController@arrivalsProduct');
    $router->post('best-sales-product', 'ProductController@bestSalesProduct');
    $router->post('deals-of-day-product', 'ProductController@dealsOfDayProduct');
    $router->post('best-of-week-product', 'ProductController@bestOfWeekProduct');
    $router->post('get-categories', 'ProductController@getCategories');
    $router->post('get-manufacture', 'ProductController@getManufacture');
    $router->post('get-supplier-offers', 'ProductController@getSupplierOffers');
    $router->post('get-trader-unread-noti-cnt', 'ProductController@getUnreadNotification');

    $router->post('get-productdetails', 'ProductController@getProductDetails');

    $router->post('get-product-by-category', 'ProductController@getProductByCat');

    $router->post('get-product-by-brand', 'ProductController@getProductByMan');

    $router->post('get-business-type', 'UserController@getBusinessType');

    $router->post('add-bank', 'BankController@addBank');
    $router->post('add-bankBranch', 'BankController@addBankBranch');
    $router->post('get-banks', 'BankController@getBankList');
    $router->post('get-account-type', 'BankController@getAccountTypeList');
    $router->post('get-bank-branch', 'BankController@getBranchList');
    $router->post('get-bank-city', 'BankController@getBankCityList');
    $router->post('get-bank-province', 'BankController@getBankProvinceList');
    $router->post('get-bank-postalcode', 'BankController@getBankPostalCodeList');

    $router->post('get-transport', 'UserController@getTransport');
    $router->post('get-truck-bodytype', 'UserController@getTruckBodyType');
    $router->post('get-work-type', 'UserController@getWorktype');
    $router->post('get-availability', 'UserController@getAvailability');



    Route::group(['middleware' => 'jwt.verify'], function () use ($router) {
        $router->post('device-token/update', 'UserController@updateToken');
        $router->post('change-password', 'UserController@changePassword');
        Route::post('logout', 'AuthController@logout');

        $router->post('upload-user-photo', 'UserController@uploadUserPhoto');
        $router->post('remove-user-photo', 'UserController@removeUserPhoto');
        //Company Driver
        // Become Company Driver
        $router->post('company-driver', 'UserController@companyDriverDetails');


        $router->post('get-driver-details', 'UserController@getCompanyDriverDetail');
        $router->post('get-company-drivers', 'UserController@getCompanyDriverlist');

        $router->post('get-notification', 'UserController@getNotificationData');
        $router->post('read-notification', 'UserController@readNotificationData');


        $router->post('get-user-document', 'UserController@getViewDocument');


        $router->post('update-basic-detail', 'UserController@updateUserDetails');
        $router->post('update-company-detail', 'UserController@updateCompanyDetails');
        $router->post('update-bank-detail', 'UserController@updateBankDetails');
        //$router->post('update-document', 'UserController@updateDocument');
        /* $router->post('update-proof-of-address', 'UserController@updateDocument');
    $router->post('update-company-reg', 'UserController@updateDocument');
    $router->post('update-identity-document', 'UserController@updateDocument');
    $router->post('update-document-tax', 'UserController@updateDocument');
    $router->post('update-document-vat', 'UserController@updateDocument');
    $router->post('update-document-bbbee', 'UserController@updateDocument');
    $router->post('update-bank-confirmation-letter', 'UserController@updateDocument');*/

        $router->post('update-user-tax', 'UserController@updateTax');

        $router->post('update-document', 'UserController@updateDocument');
        $router->post('update-driver-vehicle', 'UserController@updateDriverVehicle');
        $router->post('get-driver-vehicle', 'UserController@getDriverVehicle');



        $router->post('remove-document', 'UserController@removeDocument');

        $router->post('get-orders-supplier', 'OrderController@getOrderForSupplier');
        $router->post('get-orders-vendor', 'OrderController@getOrderForVendor');
        $router->post('get-orders-details', 'OrderController@getOrderDetail');
        $router->post('get-recent-orders', 'OrderController@getRecentOrders');

        $router->post('add-order', 'OrderController@addOrder');


        $router->post('pickup-order-packed', 'OrderController@packedOrder');
        $router->post('delivery-order-packed', 'OrderController@deliveryOrderPacked');

        $router->post('update-order-status', 'OrderController@updateOrderstatus');

        $router->post('verify-otp', 'OrderController@verifyOTP');

        $router->post('driver-accept-order', 'OrderController@acceptOrderDriver');

        $router->post('driver-reject-order', 'OrderController@rejectOrderDriver');


        $router->post('giving-rating-review', 'ProductController@givingrating');
        $router->post('get-rating-review', 'ProductController@getRatingReview');


        //Trader purchage produce
        $router->post('add-to-cart', 'CheckoutController@addtocart');
        $router->post('cart', 'CheckoutController@cart');
        $router->post('repeat-order', 'CheckoutController@repeatOrder');
        $router->post('remove-to-cart', 'CheckoutController@removetocart');

        //select Supplier
        $router->post('select-supplier', 'CheckoutController@selectSupplier');
        $router->post('supplier-orderdetail', 'CheckoutController@orderDetails');

        $router->post('payment-summary', 'CheckoutController@paymentSummary');

        //user promocode
        $router->post('apply-promocode', 'CheckoutController@applyPromocode');

        $router->post('orderplaced', 'CheckoutController@orderPlaced');

        $router->post('order-tracking', 'CheckoutController@orderlist');

        $router->post('map-update-location', 'CheckoutController@updateLocation');

        $router->post('driver-update-location', 'CheckoutController@driverUpdateLocation');

        $router->post('account-list', 'CheckoutController@accountsList');

        $router->post('orderlist-tracking', 'CheckoutController@orderlistTrack');

        //user wallet
        $router->post('get-wallet', 'CheckoutController@getWallet');
        $router->post('add-wallet', 'CheckoutController@addWallet');
        $router->post('get-transaction-type', 'CheckoutController@getTransactionType');

        //user withdrawal
        $router->post('get-withdrawal', 'CheckoutController@getWithdrawal');
        $router->post('add-withdrawal-request', 'CheckoutController@addWithdrawalRequest');


        $router->post('get-user-details', 'UserController@userDetail');


        //supplier dashbord

        $router->post('supplier-dashbord', 'OrderController@getSupplierDashbord');

        //Driver dashbord
        $router->post('driver-dashbord', 'OrderController@getDriverDashbord');

        //Company-Driver dashboard
        $router->post('company-driver-dashbord', 'OrderController@getCompanyDriverDashbord');


        $router->post('get-orders-driver', 'OrderController@getDriverOrder');
    });
});
