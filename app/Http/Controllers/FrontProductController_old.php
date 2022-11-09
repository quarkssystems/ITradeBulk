<?php



namespace App\Http\Controllers;



use App\Exports\DataGridExport;

use App\Http\Controllers\Helpers\DataGrid;

use App\Http\Requests\AdminProductRequest;

use App\Models\Brand;

use App\Models\Category;

use App\Models\Notification;

use App\Models\History\ProductHistory;

use App\Models\Product;

use App\Models\ProductUnit;

use App\Models\ArrivalType;

use App\Models\ProductCategory;

use App\Models\Tax;

use Illuminate\Http\Request;

use Illuminate\View\View;

use Maatwebsite\Excel\Excel;

use App\Http\Requests\FrontSupplierImportCsvRequest;

use App\Models\UserDocument;

use App\Models\EmailTemplate;
use App\User;
use Illuminate\Support\Facades\Mail;

use App\Imports\ProductImport;







class FrontProductController_old extends Controller

{

    use DataGrid;



    public $dataUrl = '/supplier/products';



    public $route = 'supplier.products';



    /**

     * @param Request $request

     * @param Category $categoryModel

     * @param Excel $excel

     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|Excel|\Symfony\Component\HttpFoundation\BinaryFileResponse

     * @throws \PhpOffice\PhpSpreadsheet\Exception

     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception

     */

    public function index(Request $request, Product $productModel, Excel $excel, Brand $brandModel, Tax $taxModel)

    {

        $filters = [];

        $filters[] = ['title' => 'No'];



        $filters[] = [

            'title' => 'Thumbnail',

        ];

        $filters[] = [

            'title' => 'Name',

            'column' => 'name',

            'operator' => 'LIKE',

            'sorting' => true,

            'search' => [

                'type' => 'text',

                'placeholder' => 'Search name'

            ]

        ];

        $filters[] = [

            'title' => 'Slug',

            'column' => 'slug',

            'operator' => 'LIKE',

            'sorting' => true,

            'search' => [

                'type' => 'text',

                'placeholder' => 'Search slug'

            ]

        ];







        $filters[] = [

            'title' => 'Brand',

            'column' => 'brand_id',

            'operator' => '=',

            'sorting' => true,

            'search' => [

                'type' => 'select',

                'placeholder' => 'Show all',

                'data' => $brandModel->getDropDown()

            ]

        ];



        // $filters[] = [

        //     'title' => 'Tax',

        //     'column' => 'tax_id',

        //     'operator' => '=',

        //     'sorting' => true,

        //     'search' => [

        //         'type' => 'select',

        //         'placeholder' => 'Show all',

        //         'data' => $taxModel->getDropDown()

        //     ]

        // ];



        $filters[] = [

            'title' => 'Status',

            'column' => 'status',

            'operator' => '=',

            'sorting' => true,

            'search' => [

                'type' => 'select',

                'placeholder' => 'Show all',

                'data' => $productModel->getStatusesDropDown()

            ]

        ];





       // $filters[] = [

       //     'title' => 'Action'

       // ];



        $tableName = $productModel->getTable();

        $url = $this->dataUrl;

        $this->setGridModel($productModel);

        $this->setGridRequest($request);

        $this->setFilters($filters);

        $this->setScopes(["ofUser"]);

       // $this->setScopes(["Active"]);



        $this->setSorting(['sorting_field' => $tableName.'_sorting_field', 'sort' => $tableName.'_sort', 'default_field' => 'created_at', 'default_sort' => 'DESC']);



        $this->setGridUrl($url);



        $this->setGridVariables();



        if($request->has('export_data'))

        {

            $this->setPaginationEnable(false);

            $data = $this->getGridData();

        }

        else

        {

            $data = $this->getGridData();

            $dataGridTitle = $this->gridTitles();

            $dataGridSearch = $this->gridSearch();

            $dataGridPagination = $this->gridPagination($data);

        }



        $route = $this->route;



        $pageTitle = "MANAGE PRODUCTS";



        if ($request->ajax()) {

            return view('supplier.product.grid', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'route'));

        } else {

            return view('supplier.product.index', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'pageTitle', 'route'));

        }

    }



    /**

     * @param Product $product

     * @return View

     */

    public function create(Product $product, Brand $brandModel, Tax $taxModel, Category $categoryModel, ProductUnit $productUnitModel) : View

    {
        
        $categories = $categoryModel->getParentCategories();

        $brands = $brandModel->getDropDown();

        $taxes = $taxModel->getDropDown();

        $statuses = $product->getStatusesDropDown();

        $productUnits = $productUnitModel->getDropDown();

        $selectedCategories = $product->productCategory()->pluck('category_id')->toArray();

        $arrival = ArrivalType::all();
        $defaultStockType = $product->getDefaultStockType();

        $productAttribute=$product->getStockProductAttribute();

        $productgst = $product->getStockGST();

        $pageTitle = "REQUEST PRODUCT";

        $route = $this->route;

        $role = auth()->user()->role;

        return view('supplier.product.form', compact('product', 'pageTitle', 'route', 'role', 'statuses', 'brands', 'taxes', 'categories', 'selectedCategories', 'productUnits','productAttribute','productgst','arrival','defaultStockType'));

    }



    /**

     * @param AdminProductRequest $request

     * @param Product $product

     * @return \Illuminate\Http\RedirectResponse

     */

    public function store(AdminProductRequest $request, Product $product)
    {
        
        // dd($request->all());

        if($request->hasFile('base_image_file') && $request->file('base_image_file')->isValid())
        {
            $documentFile = $product->uploadMedia($request->file('base_image_file'));
            $document = $documentFile['path'].$documentFile['name'];
            $request->merge(['base_image' => $document]);
        }

        $unitData = $request->get('unit_data');

        $unitDataArray = explode('|', $unitData);

        if(count($unitDataArray) > 1)
        {
            $request->merge([
                'unit' => $unitDataArray[0],
                'unit_name' => $unitDataArray[1]
            ]);
        }

        $productModel = $product->create($request->all());

        if($request->has('categories'))
        {
            $categories = $request->get('categories');
            foreach ($categories as $category)
            {
                $productModel->productCategory()->create([
                    'category_id' => $category
                ]);
            }
        }

        $route = $this->route;

        $redirectRoute = route("$route.index");

        if($request->has('save_continue'))
        {
            $redirectRoute = route("$route.edit", $productModel->uuid);
        }

        return redirect($redirectRoute)->with(['status' => 'success', 'message' => trans('success.admin|product|created')]);

    }



    /**

     * @param Product $product

     */

    public function show(Product $product)

    {

        //

    }



    /**

     * @param Product $product

     * @param Brand $brandModel

     * @param Tax $taxModel

     * @return View

     */

    public function edit(Product $product, Brand $brandModel, Tax $taxModel, Category $categoryModel, ProductUnit $productUnitModel) : View

    {

        $categories = $categoryModel->getParentCategories();

        $brands = $brandModel->getDropDown();

        $taxes = $taxModel->getDropDown();

        $statuses = $product->getStatusesDropDown();

        $productUnits = $productUnitModel->getDropDown();

        $selectedCategories = $product->productCategory()->pluck('category_id')->toArray();

        $productAttribute = $product->getStockProductAttribute();

        $arrival = ArrivalType::all();
        $defaultStockType = $product->getDefaultStockType();

        $productgst = $product->getStockGST();

        $copy = request()->has('copy') ? true : false;

        $pageTitle = $copy ? "COPY PRODUCT" : "EDIT PRODUCT";

        $route = $this->route;

        return view('supplier.product.form', compact('product', 'pageTitle', 'route', 'role', 'statuses', 'copy', 'brands', 'taxes', 'categories', 'selectedCategories', 'productUnits','productAttribute','productgst','arrival','defaultStockType'));

    }



    /**

     * @param AdminProductRequest $request

     * @param Product $product

     * @return \Illuminate\Http\RedirectResponse

     */

    public function update(AdminProductRequest $request, Product $product)

    {

        if($request->hasFile('base_image_file') && $request->file('base_image_file')->isValid())

        {

            $documentFile = $product->uploadMedia($request->file('base_image_file'));

            $document = $documentFile['path'].$documentFile['name'];

            $request->merge(['base_image' => $document]);

        }

        $unitData = $request->get('unit_data');

        $unitDataArray = explode('|', $unitData);

        if(count($unitDataArray) > 1)

        {

            $request->merge([

                'unit' => $unitDataArray[0],

                'unit_name' => $unitDataArray[1]

            ]);

        }

        $product->update($request->all());



        $product->productCategory()->delete();

        if($request->has('categories'))

        {

            $categories = $request->get('categories');

            foreach ($categories as $category)

            {

                $product->productCategory()->create([

                    'category_id' => $category

                ]);

            }

        }



        $route = $this->route;



        $redirectRoute = route("$route.index");

        if($request->has('save_continue'))

        {

            $redirectRoute = route("$route.edit", $product->uuid);

        }

        return redirect($redirectRoute)->with(['status' => 'success', 'message' => trans('success.admin|product|updated')]);

    }



    /**

     * @param Product $product

     * @return \Illuminate\Http\RedirectResponse

     */

    public function destroy(Product $product)

    {

        $route = $this->route;

        if($product->canDelete())

        {

            try{

                $product->delete();

                return redirect(route("$route.index"))->with(['status' => 'success', 'message' => trans('success.admin|product|deleted')]);



            }catch (\Exception $exception)

            {

                return redirect(route("$route.index"))->with(['status' => 'warning', 'message' => trans('warning.admin|product|deleteNotPossible')]);

            }

        }

        else

        {

            return redirect(route("$route.index"))->with(['status' => 'warning', 'message' => trans('warning.admin|product|deleteNotPossible')]);

        }

    }



     /**

     * @param Product INPORT $product

     * @return \Illuminate\Http\RedirectResponse

     */

    public function getImport(User $userModel){

        

        $userdoc = New UserDocument; 

        $route_doc = 'supplier.document.create';

        $curr_id = auth()->user()->uuid;

        $user = $userModel->where('uuid',$curr_id)->first();
        $userEmail = $user->email;

        $route_err = route($route_doc,$curr_id);

        $phone = '+88 0123 4567 890, +88 0123 4567 999';
        $facebook_url = 'https://www.facebook.com/';
        $instagram_url = 'https://www.instagram.com/';
        $twitter_url = 'https://www.twitter.com/';
        $pinterest_url = 'https://www.pinterest.com/';


        if(!$userdoc->getDocumentStatus()){

             $message = "We would like to inform you that your KYC is not completed. please complete your KYC";

            $email = EmailTemplate::where('name','=','supplier_KYC_pending_notification')->first();

            if(isset($email)){
                $email->description = str_replace('[CUSTOMER_NAME]', $user['first_name'].' '.$user['last_name'], $email->description);
                $email->description = str_replace('[SITE_NAME]', env('WEBSITE'), $email->description);
                $email->description = str_replace('[PHONE]', $phone, $email->description);
                $email->description = str_replace('[FACEBOOK_URL]', $facebook_url, $email->description);
                $email->description = str_replace('[TWITTER_URL]', $twitter_url, $email->description);
                $email->description = str_replace('[INSTAGRAM_URL]', $instagram_url, $email->description);
                $email->description = str_replace('[PINTEREST_URL]', $pinterest_url, $email->description);
                $email->description = str_replace('[LOGO]', asset('assets/images/logo.png'), $email->description);
            } 

            $emailContent = $email->description;

            Mail::send([], [], function ($message) use ($userEmail , $emailContent) {
              $message->to($userEmail)
                ->subject('Supplier - KYC Pending Notofication')
                ->setBody($emailContent, 'text/html'); // for HTML rich messages
            });

              return redirect(route($route_doc))->withErrors(['status' => 'warning', 'message' => trans($message)]);

         }



         $route = "supplier.product";   

         $pageTitle = "IMPORTS PRODUCTS";

        return view("$route.import",compact('pageTitle', 'route'));

    }



     /**

     * @param Product submit $product

     * @return \Illuminate\Http\RedirectResponse

     */

    public function import_parse(FrontSupplierImportCsvRequest $request,Excel $excel){

		ini_set('memory_limit','-1');


        if($request->hasFile('product_csv_file'))

        {  



            $excel->import(new ProductImport,$request->file('product_csv_file'));

            

             return redirect(route("supplier.productimport"))->with(['status' => 'success', 'message' =>"Stock Updated" ]);

        }

        

    }

}