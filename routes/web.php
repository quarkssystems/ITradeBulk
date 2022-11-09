<?php



/*

|--------------------------------------------------------------------------

| Web Routes

|--------------------------------------------------------------------------

|

| Here is where you can register web routes for your application. These

| routes are loaded by the RouteServiceProvider within a group which

| contains the "web" middleware group. Now create something great!

|

*/



use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Route;





Route::get('/', function () {

    // checkUserLoggedIn();

    // $pdf = App::make('dompdf.wrapper');

    // $pdf->loadHTML('<h1>Test</h1>');

    // return $pdf->stream();

    // $pdf = PDF::loadView('frontend.checkout.invoice');

    // return $pdf->download('document.pdf');

});


Route::get('/invoice/{pdf}', function ($pdf) {

    $file = File::get(public_path('invoice/') . $pdf);
    $response = Response::make($file, 200);
    $response->header('Content-Type', 'application/pdf');

    return $response;
});

Route::get('/supplier_own_invoice/{uuid}/{pdf}', function ($uuid, $pdf) {

    $file = File::get(public_path('supplier_own_invoice/') . $uuid . '/' . $pdf);

    $extension = pathinfo(public_path('supplier_own_invoice/') . $uuid . '/' . $pdf, PATHINFO_EXTENSION);
    $response = Response::make($file, 200);

    if ($extension == 'pdf') {
        $response->header('Content-Type', 'application/pdf');
    }
    if ($extension == 'jpeg') {
        $response->header('Content-Type', 'image/jpeg');
    }
    if ($extension == 'jpg') {
        $response->header('Content-Type', 'image/jpg');
    }
    if ($extension == 'png') {
        $response->header('Content-Type', 'image/png');
    }
    return $response;
});


Route::get('/runDB', function () {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    //Artisan::call('migrate', [
    // '--path' => '/database/migrations/2022_07_21_091124_add_columns_to_products_table.php',
    // '--path' => '/database/migrations/2022_08_02_085924_create_promotions_table.php',
    // '--path' => '/database/migrations/2022_08_02_085934_create_promotions_histories_table.php',
    // '--path' => '/database/migrations/2022_08_02_085946_add_columns_to_supplier_item_inventories_table.php',
    // '--path' => '/database/migrations/2022_08_05_080329_add_columns_to_product_histories_table.php',

    // '--path' => '/database/migrations/2022_08_26_105142_create_dispatched_documents_table.php',
    // '--path' => '/database/migrations/2022_08_26_105142_create_picking_documents_table.php',
    // '--path' => '/database/migrations/2022_09_06_093429_update_company_histories.php',
    // '--path' => '/database/migrations/2022_09_06_093625_update_user_companies.php',
    // '--path' => '/database/migrations/2022_09_06_093702_update_sales_order.php',
    // '--path' => '/database/migrations/2022_09_06_093821_update_notifications.php',
    // '--path' => '/database/migrations/2022_09_06_093954_add_field_to_baskets.php',
    // '--path' => '/database/migrations/2022_09_06_094057_add_field_to_products.php',
    // '--path' => '/database/migrations/2022_09_06_094136_add_field_to_brands.php',

    //]);

    // \Artisan::call('migrate --path=/database/migrations/2022_07_21_091124_add_columns_to_products_table.php');
    // \Artisan::call('migrate --path=/database/migrations/2022_08_02_085924_create_promotions_table.php');
    // \Artisan::call('migrate --path=/database/migrations/2022_08_02_085934_create_promotions_histories_table.php');
    // \Artisan::call('migrate --path=/database/migrations/2022_08_02_085946_add_columns_to_supplier_item_inventories_table.php');
    // \Artisan::call('migrate --path=/database/migrations/2022_08_05_080329_add_columns_to_product_histories_table.php');

    // \Artisan::call('migrate --path=/database/migrations/2022_08_26_105142_create_dispatched_documents_table.php');
    // \Artisan::call('migrate --path=/database/migrations/2022_08_26_105142_create_picking_documents_table.php');
    // \Artisan::call('migrate --path=/database/migrations/2022_09_06_093429_update_company_histories.php');
    // \Artisan::call('migrate --path=/database/migrations/2022_09_06_093625_update_user_companies.php');
    // \Artisan::call('migrate --path=/database/migrations/2022_09_06_093702_update_sales_order.php');
    // \Artisan::call('migrate --path=/database/migrations/2022_09_06_093821_update_notifications.php');
    // \Artisan::call('migrate --path=/database/migrations/2022_09_06_093954_add_field_to_baskets.php');
    // \Artisan::call('migrate --path=/database/migrations/2022_09_06_094057_add_field_to_products.php');
    // \Artisan::call('migrate --path=/database/migrations/2022_09_06_094136_add_field_to_brands.php');

    dd('migrated!');
});




Route::post('/admin/products/getProductVarient', 'AdminProductController@getProductVarient')->name('products.getProductVarient');
Route::get('/admin/auditOn/{id}', 'AuditProductController@auditOn');
Route::get('/admin/published/{id}', 'AuditProductController@published');
Route::get('/admin/userProduct/{id}', 'AuditProductController@userProduct');
Route::get('/admin/userFact/{id}', 'AuditProductController@userFact');
Route::get('/admin/productData/{id}', 'AuditProductController@productData');
Route::get('/admin/promoTypeOn/{id}', 'PromoTypeController@promoTypeOn');
Route::get('/admin/vehicleStatus/{id}', 'FrontDriverVehicalController@vehicleStatus');
Route::get('/supplier/usersStatusChange/{id}', 'DispatcherUserController@usersStatusChange');
Route::get('/checkout/getCourierData', 'CourierController@getCourierData');
Route::get('/admin/changeCourierStatus/{id}', 'CourierController@changeCourierStatus');



Route::get('/test', 'FrontendAjaxController@test');

Route::middleware(['checkFrontEndUser'])

    //    ->namespace('Admin')

    ->group(function () {

        Route::get('/', 'FrontendHomeController@home')->name('home');

        Route::get('/about', 'FrontendHomeController@about')->name('about');

        Route::get('/terms', 'FrontendHomeController@about');

        Route::get('/policy', 'FrontendHomeController@about');



        Route::get('/contact', 'FrontendHomeController@contact')->name('contact');

        Route::get('/report-abuse', 'FrontendHomeController@reportAbuse')->name('report-abuse');

        Route::get('/feedback', 'FrontendHomeController@feedback')->name('feedback');


        Route::get('/become-supplier', 'FrontendBecomeSupplierController@index')->name('become-supplier');

        Route::post('/become-supplier', 'FrontendBecomeSupplierController@store')->name('become-supplier.store');

        Route::get('/become-picker', 'FrontendBecomePickerController@index')->name('become-picker');

        Route::post('/become-picker', 'FrontendBecomePickerController@store')->name('become-picker.store');

        Route::get('/become-dispatcher', 'FrontendBecomeDispatcherController@index')->name('become-dispatcher');

        Route::post('/become-dispatcher', 'FrontendBecomeDispatcherController@store')->name('become-dispatcher.store');


        Route::get('/become-vendor', 'FrontendBecomeVendorController@index')->name('become-vendor');

        Route::post('/become-vendor', 'FrontendBecomeVendorController@store')->name('become-vendor.store');

        Route::get('/become-driver', 'FrontendBecomeDriverController@index')->name('become-driver');

        Route::post('/become-driver', 'FrontendBecomeDriverController@store')->name('become-driver.store');

        Route::post('/request-quote', 'FrontendHomeController@requestQuotePost')->name('request-quote');

        Route::post('/contact-submit', 'FrontendHomeController@contactSubmit')->name('contact-submit');


        Route::post('/report-abuse-submit', 'FrontendHomeController@reportAbuseSubmit')->name('report-abuse-submit');

        Route::post('/feedback-submit', 'FrontendHomeController@feedbackSubmit')->name('feedback-submit');
    });

/**

 * Checkout Routes

 */


Route::get('/supplier/tender', 'DriverNotificationController@tender')->name('supplier.tender');
Route::get('/supplier/notification', 'DriverNotificationController@index')->name('supplier.notification.index');
Route::get('/supplier/read/{id}', 'DriverNotificationController@read')->name('supplier.notification.read');
Route::get('/supplier/readAll', 'DriverNotificationController@readAll')->name('supplier.notification.readAll');
Route::get('/supplier/accepted_delivery', 'DriverNotificationController@acceptedDelivery')->name('supplier.notification.accepted_delivery');
Route::get('/getDeliveryData/{id}', 'DriverNotificationController@getDeliveryData')->name('getDeliveryData');
Route::get('/getDeliverySchduleData/{id}', 'DriverNotificationController@getDeliverySchduleData')->name('getDeliveryData');
Route::get('/getAllDeliverySchduleData/{id}', 'DriverNotificationController@getAllDeliverySchduleData')->name('getDeliveryData');

Route::post('/supplier/notification_accept/{order_id}', 'DriverNotificationController@acceptNotification')->name('supplier.notification.accept');
Route::post('/supplier/notification_reject/{order_id}', 'DriverNotificationController@rejectNotification')->name('supplier.notification.reject');
Route::post('/supplier/notification_reject_after_accept/{order_id}', 'DriverNotificationController@rejectAfterAcceptNotification')->name('supplier.notification.reject_after_accept');

Route::get('/supplier/delivery_schedule', 'DeliveryScheduleController@index')->name('supplier.delivery_schedule');

Route::name('checkout.')

    ->middleware(['web', 'checkFrontEndUser'])

    ->prefix('checkout')

    ->group(function () {

        Route::post('store-location', 'FrontCheckoutController@storeLocation')->name('store-location');



        Route::middleware(['auth'])

            ->group(function () {

                Route::post('add-to-cart', 'FrontCheckoutController@addToCart')->name('add-to-cart');

                Route::get('/cart', 'FrontCheckoutController@cart')->name('cart');

                Route::get('/remove-product-from-cart/{product_id}', 'FrontCheckoutController@removeProductFromCart')->name('remove-product-from-cart');
                Route::get('/edit-product-from-cart/{product_id}', 'FrontCheckoutController@removeProductFromCart')->name('edit-product-from-cart');

                Route::get('/select-supplier', 'FrontCheckoutController@selectSupplier')->name('select-supplier');
            });
    });


Route::middleware(['auth'])
    ->group(function () {
        Route::get('/products', 'FrontCatalogController@productList')->name('products');
    });


Route::get('/request-quote', 'FrontendHomeController@requestQuote')->name('request-quote');

Route::get('/supplier', 'FrontendHomeController@supplier')->name('supplier');

// Route::get('/products', 'FrontCatalogController@productList')->name('products');

Route::get('/products/{product_id}', 'FrontCatalogController@productdetail')->name('productdetail');

Route::get('/offers', 'FrontCatalogOffersController@offerList')->name('offers');

Route::get('/offers/{offer_id}', 'FrontCatalogOffersController@offerdetail')->name('orderdetail');

Route::get('/selectCourier/{id}', 'FrontendHomeController@selectCourier')->name('selectCourier');

Route::post('/make-payment', 'FrontendHomeController@selectPaymentMethod')->name('make-payment');
Route::get('/picking-document/{order_id}', 'FrontendHomeController@getPickingDocument')->name('picking-document'); //new added
Route::get('/dispatch-document/{order_id}', 'FrontendHomeController@getDispatchDocument')->name('dispatch-document'); //new added
Route::get('/credit-note/{order_id}', 'FrontendHomeController@creditNote')->name('credit-note'); //new added
Route::post('/supplierOwnInvoice', 'FrontendHomeController@supplierOwnInvoice')->name('supplierOwnInvoice'); //new added

Route::post('/picking-doc-Update', 'FrontendHomeController@pickingDocUpdate')->name('picking-doc-Update'); //new added
Route::post('/dispatch-doc-Update', 'FrontendHomeController@dispatchDocUpdate')->name('dispatch-doc-Update'); //new added
Route::get('/reset_picking_doc/{order_id}', 'FrontendHomeController@resetPickingDoc')->name('reset_picking_doc'); //new added
Route::get('/reset_dispatch_doc/{order_id}', 'FrontendHomeController@resetDispatchDoc')->name('reset_dispatch_doc'); //new added
Route::get('/remove-product-doc/{order_id}/{product_id}', 'FrontendHomeController@removeProductDoc')->name('remove-product-doc'); //new added
Route::get('/remove-product-doc-dispatch/{order_id}/{product_id}', 'FrontendHomeController@removeProductDocDispatch')->name('remove-product-doc-dispatch'); //new added

Route::get('/newPendingCartFromSupplier', 'FrontCheckoutController@newPendingCartFromSupplier')->name('newPendingCartFromSupplier'); //new added


Route::post('/make-payment-post', 'FrontendHomeController@postPaymentMethod')->name('make-payment-post');

Route::get('/success', 'FrontendHomeController@success')->name('success');

Route::get('/supplier-detail/{supplier_uuid}', 'FrontendHomeController@supplierDetail')->name('supplier-detail');

Route::get('/products/detail/{slug}', 'FrontCatalogController@moreDetail')->name('more-products');





Route::get('/pages/{slug}', 'FrontendHomeController@cmsPages')->name('cms-pages');

Route::prefix('frontend/ajax')->group(function () {



    Route::post('/location/get-states', 'FrontendAjaxController@postGetStates')->name('frontend.ajax.postGetStates');

    Route::post('/location/get-cities', 'FrontendAjaxController@postGetCities')->name('frontend.ajax.postGetCities');

    Route::post('/location/get-areas', 'FrontendAjaxController@postGetAreas')->name('frontend.ajax.postGetAreas');

    Route::post('/get-product', 'FrontendAjaxController@postGetProduct')->name('frontend.ajax.postGetProduct');

    Route::post('/get-category', 'FrontendAjaxController@postGetCategories')->name('frontend.ajax.postGetCategories');

    Route::post('/get-brand', 'FrontendAjaxController@postGetBrand')->name('frontend.ajax.postGetBrand');

    Route::post('/get-supplier', 'FrontendAjaxController@postGetSupplier')->name('frontend.ajax.postGetSupplier');



    Route::post('/get-offer', 'FrontendAjaxController@verifyPromoCode')->name('frontend.ajax.verifyPromoCode');

    Route::get('/get-menu-categories/{parentId?}', 'FrontendAjaxController@getSubCategoryByCategory')->name('frontend.ajax.categoryMenu');



    Route::post('/get-capacity-data', 'FrontendAjaxController@postGetCapacityData')->name('frontend.ajax.postGetCapacityData');

    Route::post('/get-capacity', 'FrontendAjaxController@postGetCapacity')->name('frontend.ajax.postGetCapacity');

    Route::post('/get-pallet-capacity', 'FrontendAjaxController@postGetPalletCapacity')->name('frontend.ajax.postGetPalletCapacity');

    Route::post('/get-pallet', 'FrontendAjaxController@postGetPallet')->name('frontend.ajax.postGetPallet');



    Route::post('/get-address', 'FrontendAjaxController@getAddress')->name('frontend.ajax.getAddress'); //company driver user company address 



    Route::post('/change-status', 'FrontendAjaxController@updateOrderStatus')->name('frontend.ajax.updateOrderStatus');



    Route::post('/new-bank', 'FrontendAjaxController@saveNewBank')->name('frontend.ajax.new-bank');

    Route::post('/new-bankbranch', 'FrontendAjaxController@saveNewBankBranch')->name('frontend.ajax.new-bankbranch');

    Route::get('/refresh-bankbranch/{Id?}', 'FrontendAjaxController@updatedBankGrid')->name('frontend.ajax.refresh-bankbranch');
});



Auth::routes(['verify' => true]);



Route::get('/home', 'HomeController@index')->name('homeOld2ss');



//User Auth Routes

Route::get('/user/login', 'Auth\LoginController@showUserLoginForm')->name('user.loginForm');

Route::get('/user/password/email', 'Auth\LoginController@showUserForgotPasswordForm')->name('user.password.email');





//Admin Auth Routes

Route::get('/admin/login', 'Auth\LoginController@showAdminLoginForm')->name('admin.loginForm');

Route::get('/admin/register', 'Auth\RegisterController@showAdminRegistrationForm')->name('admin.registerForm');

Route::get('/admin/reset', 'Auth\ForgotPasswordController@showAdminLinkRequestForm')->name('admin.resetPasswordForm');

Route::get('/admin/password/reset/{token}', 'Auth\ResetPasswordController@showAdminResetForm')->name('admin.resetConfirmPasswordForm');


Route::post('/postGetCategory', 'AdminAjaxController@postGetCategory')->name('ajax.postGetCategory');


Route::name('admin.')

    ->middleware(['auth', 'web', 'verified', 'role'])

    ->prefix('admin')

    //    ->namespace('Admin')

    ->group(function () {



        /**

         * Ajax routes

         */

        Route::prefix('ajax')->group(function () {

            Route::post('/location/get-states', 'AdminAjaxController@postGetStates')->name('ajax.postGetStates');

            Route::post('/location/get-cities', 'AdminAjaxController@postGetCities')->name('ajax.postGetCities');

            Route::post('/location/get-areas', 'AdminAjaxController@postGetAreas')->name('ajax.postGetAreas');

            Route::post('/delivery-vehicle-master/get-capacity-data', 'AdminAjaxController@postGetCapacityData')->name('ajax.postGetCapacityData');

            Route::post('/delivery-vehicle-master/get-capacity', 'AdminAjaxController@postGetCapacity')->name('ajax.postGetCapacity');

            Route::post('/delivery-vehicle-master/get-pallet-capacity', 'AdminAjaxController@postGetPalletCapacity')->name('ajax.postGetPalletCapacity');

            Route::post('/delivery-vehicle-master/get-pallet', 'AdminAjaxController@postGetPallet')->name('ajax.postGetPallet');

            Route::post('/change-status', 'AdminAjaxController@updateOrderStatus')->name('ajax.updateOrderStatus');




            Route::post('/get-address', 'AdminAjaxController@getAddress')->name('ajax.getAddress'); //company driver user company address 

        });





        Route::get('/dashboard', 'AdminDashboardController@index')->name('dashboard');

        Route::resource('/users', 'AdminUserController');



        Route::resource('/manage-vendor', 'AdminManageVendorController');

        Route::resource('/manage-supplier', 'AdminManageSupplierController');

        Route::resource('/manage-logistic', 'AdminManageLogisticsController');

        Route::resource('/manage-transporter-company', 'AdminManageTransportersController');



        Route::resource('/manage-users/{user_uuid}/user-company', 'AdminUserCompanyController');

        Route::resource('/vendor/{user_uuid}/vendor-company', 'AdminVendorCompanyController');

        Route::resource('/supplier/{user_uuid}/supplier-company', 'AdminSupplierCompanyController');

        Route::resource('/logistic/{user_uuid}/logistic-detail', 'AdminLogisticDetailController');



        Route::resource('/manage-users/{user_uuid}/user-document', 'AdminUserDocumentController');

        Route::resource('/vendor/{user_uuid}/vendor-document', 'AdminVendorDocumentController');

        Route::resource('/supplier/{user_uuid}/supplier-document', 'AdminSupplierDocumentController');

        Route::resource('/logistic/{user_uuid}/logistic-document', 'AdminLogisticDocumentController');

        Route::resource('/company/{user_uuid}/company-document', 'AdminCompanyDocumentController');



        Route::resource('/manage-users/{user_uuid}/user-tax-details', 'AdminUserTaxDetailsController');

        Route::resource('/vendor/{user_uuid}/vendor-tax-details', 'AdminVendorTaxDetailsController');

        Route::resource('/supplier/{user_uuid}/supplier-tax-details', 'AdminSupplierTaxDetailsController');

        Route::resource('/logistic/{user_uuid}/logistic-tax-details', 'AdminLogisticTaxDetailsController');



        Route::resource('/manage-users/{user_uuid}/user-bank-details', 'AdminUserBankDetailsController');

        Route::resource('/vendor/{user_uuid}/vendor-bank-details', 'AdminVendorBankDetailsController');

        Route::resource('/supplier/{user_uuid}/supplier-bank-details', 'AdminSupplierBankDetailsController');

        Route::resource('/logistic/{user_uuid}/logistic-bank-details', 'AdminLogisticBankDetailsController');



        Route::resource('/permissions', 'AdminPermissionController');

        Route::resource('/roles', 'AdminRoleController');

        Route::resource('/bank-master', 'AdminBankMasterController');

        Route::resource('/bank-branch', 'AdminBankBranchController');



        Route::resource('/location-master/country', 'AdminLocationCountryController');

        Route::resource('/location-master/{country_uuid}/state', 'AdminLocationStateController');

        Route::resource('/location-master/{state_uuid}/city', 'AdminLocationCityController');

        Route::resource('/location-master/{city_uuid}/zipcode', 'AdminLocationZipcodeController');



        Route::resource('/categories', 'AdminCategoryController');

        Route::post('/categories/quick-action', 'AdminCategoryController@quickAction')->name('categories.quick-action');

        Route::resource('/brands', 'AdminBrandController');
        Route::post('storeBrandPhoto', 'AdminBrandController@storeBrandPhoto')->name('brand.storeBrandPhoto');
        Route::get('storeBrandPhoto/{brand_id}', 'AdminBrandController@statusBrandPhoto')->name('brand.storeBrandPhoto');

        Route::resource('/offerdeals', 'AdminSupplierOffersController');

        Route::resource('/tax', 'AdminTaxController');

        Route::resource('/products', 'AdminProductController');

        Route::resource('/admin-fields', 'AdminFieldsController');
        Route::resource('/product-code', 'ProductCodesController');
        Route::resource('/product-link', 'ProductLinksController');
        Route::resource('/product_description', 'ProductDescriptionController');
        Route::resource('/data-hierarchy', 'DataHierarchyController');
        Route::get('/hierarchyimport', 'DataHierarchyController@getHierarchyImport')->name('hierarchyimport');
        Route::post('/import_data_hierarchy', 'DataHierarchyController@import_data_hierarchy')->name('import_data_hierarchy');

        Route::resource('/product-fact', 'ProductFactController');
        Route::get('/supplier-fact/{id}/{user_id}', 'ProductFactController@editFact')->name('supplier-fact');
        Route::put('/supplier-fact-update/{id}', 'ProductFactController@updateFact')->name('supplier-fact-update');
        Route::get('/supplier-product/{id}/{user_id}', 'ProductFactController@editProduct')->name('supplier-product');
        Route::put('/supplier-product-update/{id}', 'ProductFactController@updateProduct')->name('supplier-product-update');


        Route::resource('/packing-dimension', 'PackingDimensionsController');
        Route::resource('/varients', 'VarientsController');
        Route::resource('/attributes', 'AttributesController');
        Route::resource('/image-management', 'ImageManagmentController');
        Route::resource('/promotions', 'PromotionsController');
        Route::resource('/invoice-splitting', 'InvoiceSplittingController');
        Route::resource('/promo-type', 'PromoTypeController');
        Route::resource('/courier', 'CourierController');


        Route::post('/products/storedata', 'AdminProductController@storedata')->name('products.storedata');
        Route::get('/product-variants', 'AdminProductController@variantsindex')->name('products.variantsindex');
        Route::get('/product-variants-edit/{id}', 'AdminProductController@variantsedit')->name('products.variantsedit');
        Route::post('/product-variants-update', 'AdminProductController@variantsupdate')->name('products.variantsupdate');

        Route::resource('/supplier-view', 'AuditProductController');
        Route::get('/product-list/{id}', 'AuditProductController@getSupplierProduct')->name('product-list');
        // Route::get('/audit-product/{id}', 'AuditProductController@getSupplierProduct')->name('audit-product');
        Route::get('/fact-list/{id}', 'AuditProductController@getSupplierFact')->name('fact-list');
        // Route::get('/audit-fact/{id}', 'AuditProductController@getSupplierFact')->name('audit-product');



        Route::resource('/product-unit', 'AdminProductUnitController');
        Route::resource('/quick-view', 'AdminQuickViewController');

        Route::resource('/delivery-vehicle-master', 'AdminDeliveryVehicleMasterController');
        Route::resource('/transport-type', 'TransportTypeController');

        Route::resource('/cmsmodule', 'AdminCMSModuleController');

        Route::resource('/cmsblock', 'AdminCMSBlockController');

        Route::resource('/banner', 'AdminBannerController');

        Route::resource('/emailTemplate', 'AdminEmailTemplateController');

        Route::resource('/shortcode', 'AdminShortcodeController');

        Route::post('/requestQuoteStatus', 'AdminRequestQuoteController@status');

        Route::post('/uploadZipImages', 'AdminUserController@uploadZipImages')->name('admin.uploadZipImages');

        Route::resource('/requestQuote', 'AdminRequestQuoteController');

        Route::post('/requestQuoteStatus', 'AdminRequestQuoteController@status');

        Route::resource('/testimonials', 'AdminTestimonialsController');

        Route::post('/testimonials/getClients', 'AdminTestimonialsController@getClients');

        Route::resource('/team', 'AdminTeamsController');

        Route::resource('/vehicle-capacity', 'AdminVehicleCapacityController');

        Route::resource('/sales-orders', 'AdminSalesOrderController');

        Route::resource('/order-logistic-queue', 'AdminOrderLogisticQueueController');

        Route::post('/orderLogisticQueueAccept', 'AdminOrderLogisticQueueController@accept');

        Route::post('/delivery-vehicle-master/quick-action', 'AdminDeliveryVehicleMasterController@quickAction')->name('delivery-vehicle-master.quick-action');



        Route::resource('/manage-users/{user_uuid}/user-wallet', 'AdminUserWalletTransactionsController');

        Route::resource('/supplier/{user_uuid}/supplier-wallet', 'AdminSupplierWalletTransactionsController');

        Route::resource('/logistic/{user_uuid}/logistic-wallet', 'AdminLogisticWalletTransactionsController');

        Route::resource('/vendor/{user_uuid}/vendor-wallet', 'AdminVendorWalletTransactionsController');



        Route::resource('/supplier/{user_uuid}/supplier-stock', 'AdminSupplierStockController');



        Route::get("/admin/wallet/{transaction_id}/approve", "AdminUserWalletTransactionsController@approveTransaction")->name("approve-wallet-transaction");

        Route::get("/admin/wallet/{transaction_id}/cancel", "AdminUserWalletTransactionsController@cancelTransaction")->name("cancel-wallet-transaction");



        Route::get('/productimport', 'AdminProductController@getImport')->name('productimport');

        Route::post('/import_parse', 'AdminProductController@import_parse')->name('import_parse');

        Route::get('/categoryimport', 'AdminCategoryController@getImport')->name('categoryimport');

        Route::post('/importcategoryparse', 'AdminCategoryController@import_parse')->name('importcategoryparse');

        Route::get('/manufacturerimport', 'AdminBrandController@getImport')->name('manufacturerimport');

        Route::post('/importmafparse', 'AdminBrandController@import_parse')->name('importmafparse');

        Route::get('products/index/1', 'AdminProductController@index')->name('req-pro'); //see request qutoa  on product module


        Route::get('/supplier-delivery/{id}', 'SupplierDashboardController@adminSupplierDelivery')->name('supplier-delivery');
        Route::post('/supplier-delivery', 'SupplierDashboardController@adminSupplierDeliveryStore')->name('supplier-delivery-post');
        Route::get('/itbinvoice/{id}', 'FrontendSalesOrderController@Itbinvoice')->name('itbinvoice');
        Route::get('/admin-details', 'AdminUserController@adminDetails')->name('admin-details');
        Route::post('/admin-details-store', 'AdminUserController@adminDetailsStore')->name('admin-details-store');


        Route::resource('/logistic-company', 'AdminManageLogisticsCompaniesController');

        Route::resource('/logistic-company/{logistic_companies_uuid}/logistic-company-bank-details', 'AdminLogisticCompanyBankDetailsController');

        Route::resource('/logistic-company/{logistic_companies_uuid}/logistic-company-tax-details', 'AdminLogisticCompanyTaxDetailsController');

        Route::resource('/offers', 'AdminSupplierOffersController');



        Route::get('/settings', 'AdminSettingController@index')->name('settings');

        Route::post('/settings', 'AdminSettingController@store')->name('settings.store');

        Route::get('/withdrawalrequest', 'AdminWithdrawalRequestController@index')->name('withdrawalrequest');

        Route::get("/admin/withdrawalrequest/{transaction_id}/approve", "AdminWithdrawalRequestController@approveWithdrawalTransaction")->name("approve-withdrawalrequest-transaction");
        Route::get("/admin/withdrawalrequest/{transaction_id}/cancel", "AdminWithdrawalRequestController@cancelWithdrawalTransaction")->name("cancel-withdrawalrequest-transaction");


        Route::get('/admin-commission', 'AdminCommissionController@index')->name('admin-commission');
    });



//Routes for suppliers admin

// TODO :: need to create middleware for each role



Route::name('supplier.')

    ->middleware(['auth', 'web', 'verified', 'checkFrontEndUser'])

    ->prefix('supplier')

    //    ->namespace('Admin')

    ->group(function () {



        Route::resource('/picker-users', 'PickerUserController');
        Route::resource('/dispatcher-users', 'DispatcherUserController');


        Route::get('/dashboard', 'SupplierDashboardController@index')->name('dashboard');
        Route::get('/supplier-delivery', 'SupplierDashboardController@supplierDelivery')->name('supplier-delivery');
        Route::post('/supplier-delivery', 'SupplierDashboardController@supplierDeliveryStore')->name('supplier-delivery-post');

        //Route::get('/inventory/item-form','FrontSupplierItemInventoryController@itemAddForm')->name('inventory.item-add-form');

        Route::resource('/inventory', 'FrontSupplierItemInventoryController');
        Route::get('/updateFact/{id}', 'FrontSupplierItemInventoryController@updateFact');
        Route::post('/updateFact', 'FrontSupplierItemInventoryController@updateStoreFact')->name('updateFact');

        Route::resource('/stock', 'FrontSupplierItemStockController');

        Route::resource('/bank-details', 'FrontUserBankDetailsController');

        Route::resource('/company', 'FrontUserCompanyController');

        Route::resource('/document', 'FrontUserDocumentController');

        Route::resource('/tax-details', 'FrontUserTaxDetailsController');

        Route::resource('/products', 'FrontProductController');
        Route::post('/products/storedata', 'FrontProductController@storedata')->name('products.storedata');
        Route::get('/product-variants', 'FrontProductController@variantsindex')->name('products.variantsindex');
        Route::get('/product-variants-edit/{id}', 'FrontProductController@variantsedit')->name('products.variantsedit');
        Route::post('/product-variants-update', 'FrontProductController@variantsupdate')->name('products.variantsupdate');

        Route::resource('/sales-orders', 'FrontendSalesOrderController');

        Route::resource('/drivers', 'FrontUserDriversController');



        Route::get('/productimport', 'FrontProductController@getImport')->name('productimport');

        Route::post('/import_parse', 'FrontProductController@import_parse')->name('import_parse');
        Route::post('/import_product_parse', 'FrontProductController@import_product_parse')->name('import_product_parse');
    });

Route::name('picker.')

    ->middleware(['auth', 'web', 'verified', 'checkFrontEndUser'])

    ->prefix('picker')

    //    ->namespace('Admin')

    ->group(function () {



        Route::get('/dashboard', 'SupplierDashboardController@index')->name('dashboard');

        //Route::get('/inventory/item-form','FrontSupplierItemInventoryController@itemAddForm')->name('inventory.item-add-form');

        Route::resource('/inventory', 'FrontSupplierItemInventoryController');

        Route::resource('/stock', 'FrontSupplierItemStockController');

        Route::resource('/bank-details', 'FrontUserBankDetailsController');

        Route::resource('/company', 'FrontUserCompanyController');

        Route::resource('/document', 'FrontUserDocumentController');

        Route::resource('/tax-details', 'FrontUserTaxDetailsController');

        Route::resource('/products', 'FrontProductController');
        Route::post('/products/storedata', 'FrontProductController@storedata')->name('products.storedata');
        Route::get('/product-variants', 'FrontProductController@variantsindex')->name('products.variantsindex');
        Route::get('/product-variants-edit/{id}', 'FrontProductController@variantsedit')->name('products.variantsedit');
        Route::post('/product-variants-update', 'FrontProductController@variantsupdate')->name('products.variantsupdate');

        Route::resource('/sales-orders', 'FrontendSalesOrderController');

        Route::resource('/drivers', 'FrontUserDriversController');



        Route::get('/productimport', 'FrontProductController@getImport')->name('productimport');

        Route::post('/import_parse', 'FrontProductController@import_parse')->name('import_parse');
        Route::post('/import_product_parse', 'FrontProductController@import_product_parse')->name('import_product_parse');
    });

Route::name('dispatcher.')

    ->middleware(['auth', 'web', 'verified', 'checkFrontEndUser'])

    ->prefix('dispatcher')

    //    ->namespace('Admin')

    ->group(function () {



        Route::get('/dashboard', 'SupplierDashboardController@index')->name('dashboard');

        //Route::get('/inventory/item-form','FrontSupplierItemInventoryController@itemAddForm')->name('inventory.item-add-form');

        Route::resource('/inventory', 'FrontSupplierItemInventoryController');

        Route::resource('/stock', 'FrontSupplierItemStockController');

        Route::resource('/bank-details', 'FrontUserBankDetailsController');

        Route::resource('/company', 'FrontUserCompanyController');

        Route::resource('/document', 'FrontUserDocumentController');

        Route::resource('/tax-details', 'FrontUserTaxDetailsController');

        Route::resource('/products', 'FrontProductController');
        Route::post('/products/storedata', 'FrontProductController@storedata')->name('products.storedata');
        Route::get('/product-variants', 'FrontProductController@variantsindex')->name('products.variantsindex');
        Route::get('/product-variants-edit/{id}', 'FrontProductController@variantsedit')->name('products.variantsedit');
        Route::post('/product-variants-update', 'FrontProductController@variantsupdate')->name('products.variantsupdate');

        Route::resource('/sales-orders', 'FrontendSalesOrderController');

        Route::resource('/drivers', 'FrontUserDriversController');



        Route::get('/productimport', 'FrontProductController@getImport')->name('productimport');

        Route::post('/import_parse', 'FrontProductController@import_parse')->name('import_parse');
        Route::post('/import_product_parse', 'FrontProductController@import_product_parse')->name('import_product_parse');
    });

Route::name('user.')

    ->middleware(['auth', 'web', 'verified', 'checkFrontEndUser'])

    ->prefix('user')

    ->group(function () {

        Route::get('/quick-view', 'AdminQuickViewController@indexSupplier')->name('quick-view.index');
        Route::post('/quick-view', 'AdminQuickViewController@store')->name('quick-view.index');

        Route::resource('/profile', 'FrontUserProfileController');

        Route::resource('/wallet', 'FrontUserWalletController');

        Route::resource('/withdrawal', 'FrontUserWithdrawalController');

        Route::resource('/sales-orders', 'FrontendSalesOrderController');

        Route::get('/pendingOrder', 'FrontendSalesOrderController@pendingOrder')->name('pendingOrder');

        Route::get('/companyPendingOrder', 'FrontendSalesOrderController@companyPendingOrder')->name('companyPendingOrder');

        Route::get('/completedOrder', 'FrontendSalesOrderController@completedOrder')->name('completedOrder');

        Route::get('/companyCompletedOrder', 'FrontendSalesOrderController@companyCompletedOrder')->name('companyCompletedOrder');
        Route::get('/itbinvoice/{id}', 'FrontendSalesOrderController@Itbinvoice')->name('itbinvoice');

        Route::get('/sales-orders/{sales_uuid}/driver', 'FrontendDriverInvoiceController@edit')->name('view_driver_invoice');

        Route::get('/transporter-invoice', 'FrontFavOrderController@transporterInvoice')->name('transporterInvoice');

        Route::resource('/fav-orders', 'FrontFavOrderController');

        Route::get('/addorder/{basket_uuid}', 'FrontFavOrderController@addorder')->name('addorder');

        Route::resource('/offers', 'FrontSupplierOfferController');

        Route::resource('/vehicle', 'FrontDriverVehicalController');

        Route::resource('/success-story', 'FrontendSuccessStoryController');

        Route::post('/review-rating', 'FrontendSuccessStoryController@saveReviewRating');

        Route::get('/lastorder', 'FrontFavOrderController@lastorder')->name('lastorder');
    });

Route::get('/getProvince', 'FrontDriverVehicalController@getProvince')->name('getProvince');


Route::get('clear', function () {

    try {

        \Artisan::call('cache:clear');

        \Artisan::call('view:clear');

        \Artisan::call('config:clear');

        \Artisan::call('route:clear');

        dd(['Cache, View, Route & Config Cleared']);
    } catch (\Exception $e) {

        dd($e);
    }
});
