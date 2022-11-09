<?php

namespace App\Http\Controllers;

use App\Exports\DataGridExport;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Helpers\DataGrid;
use App\Http\Requests\AdminProductRequest;
use App\Http\Requests\AdminImportCsvRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\History\ProductHistory;
use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\ProductCategory;
use App\Models\Tax;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Excel;
use App\Models\ArrivalType;
use App\Imports\AdminProductImport;
use Illuminate\Support\Facades\Redirect;
use App\User;
use App\AdminQuickView;
use DB;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Session;
use Auth;

class AdminProductController extends Controller
{
    use DataGrid;

    public $dataUrl = '/admin/products';

    public $route = 'admin.products';

    /**
     * @param Request $request
     * @param Category $categoryModel
     * @param Excel $excel
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|Excel|\Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function index(Request $request, Product $productModel, Excel $excel, Brand $brandModel, User $userModel)
    {

        /* if (\Route::current()->getName() !== 'admin.products.index') {
            echo \Route::current()->getName();
        }*/

        $filters = [];
        $filters[] = ['title' => 'No'];

        $filters[] = [
            'title' => 'Thumbnail',
        ];

        // $filters[] = [
        //     'title' => 'Barcode',
        //     'column' => 'barcode',
        //     'operator' => 'LIKE',
        //     'sorting' => true,
        //     'search' => [
        //         'type' => 'text',
        //         'placeholder' => 'Search Barcode'
        //     ]
        // ];

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
            // 'title' => 'Manufacturer',
            'column' => 'brand_id',
            'operator' => '=',
            'sorting' => true,
            'search' => [
                'type' => 'select',
                'placeholder' => 'Show all',
                'data' => $brandModel->getDropDown()
            ]
        ];


        $filters[] = [
            'title' => 'User',
            'column' => 'user_id',
            'operator' => '=',
            'sorting' => true,
            'search' => [
                'type' => 'select',
                'placeholder' => 'Show all',
                'data' => $userModel->getDropDown()
            ]
        ];


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

        //        $filters[] = [
        //            'title' => 'Action'
        //        ];


        $tableName = $productModel->getTable();
        $url = $this->dataUrl;
        $this->setGridModel($productModel);
        $this->setGridRequest($request);
        $this->setFilters($filters);

        if (\Route::current()->getName() !== 'admin.products.index') {

            $this->setScopes(["OfUserNotAdmin"]);
            // $this->setScopes(["OfNoChild"]);
            //$this->setScopesWithValue(['barcode' => '1600000000196']);
        }

        // $this->setScopes(["OfNoChild"]);
        // $this->setScopes(["OfNoChildNew"]);
        // $this->setScopes(["OfNoParent"]);

        $this->setSorting(['sorting_field' => $tableName . '_sorting_field', 'sort' => $tableName . '_sort', 'default_field' => 'name', 'default_sort' => 'ASC']);

        $this->setGridUrl($url);

        $this->setGridVariables();

        if ($request->has('export_data')) {
            $this->setPaginationEnable(false);
            $data = $this->getGridData();
        } else {

            // DB::enableQueryLog(); // Enable query log
            $data = $this->getGridData();

            // dd(DB::getQueryLog()); // Show results of log
            $dataGridTitle = $this->gridTitles();
            $dataGridSearch = $this->gridSearch();
            $dataGridPagination = $this->gridPagination($data);
        }

        if ($request->has('export_data')) {
            $fileName = 'PRODUCT_DATA';
            $adminQuickView = AdminQuickView::where('user_id', Auth::user()->uuid)->first();
            // dd($adminQuickView);

            return $excel->download(new DataGridExport('admin.product.export', [$data, $adminQuickView]), "$fileName.xlsx");
        }


        // dd($data);      

        $route = $this->route;

        if (\Route::current()->getName() !== 'admin.products.index') {

            $pageTitle = "Requested Products";
        } else {
            $pageTitle = "MANAGE PRODUCTS";
        }

        if (Session::has('ProductPage')) {
            Session::forget('ProductPage');
        }
        Session::put('ProductPage', $request->input('page') ?? 1);


        if ($request->ajax()) {
            return view('admin.product.grid', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'route'));
        } else {
            return view('admin.product.index', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'pageTitle', 'route'));
        }
    }




    /**
     * @param Product $product
     * @return View
     */
    public function create(Request $request, Product $product, Brand $brandModel, Tax $taxModel, Category $categoryModel, ProductUnit $productUnitModel): View
    {



        $categories = $categoryModel->getParentCategories();
        $brands = $brandModel->getDropDown();
        $taxes = $taxModel->getDropDown();
        $statuses = $product->getStatusesDropDown();

        $productUnits = $productUnitModel->getDropDown();
        $productAttribute = $product->getStockProductAttribute();
        $productgst = $product->getStockGST();
        $defaultStockType = $product->getDefaultStockType();
        $selectedCategories = $product->productCategory()->pluck('category_id')->toArray();
        $arrival = ArrivalType::all();
        $pageTitle = "CREATE PRODUCT";
        $route = $this->route;
        $role = auth()->user()->role;
        $id = auth()->user()->uuid;
        $quickView = AdminQuickView::where('user_id', $id)->first();

        // $categoryId = $request->has('category_id') ? $request->get('category_id') : null;
        // $subCategoryId = $request->has('sub_category_id') ? $request->get('sub_category_id') : null;
        // if(!is_null($categoryId)) {
        //     $categoryModel->where('uuid', $categoryId)->count() > 0 ? $categoryModel->where('uuid', $categoryId)->get() : [];
        // }
        // $countryInput = $request->has('country_id') ? $request->get('country_id') : $user_company->country_id;
        // $stateInput = $request->has('state_id') ? $request->get('state_id') : $user_company->state_id;
        // $cityInput = $request->has('city_id') ? $request->get('city_id') : $user_company->city_id;
        // if(!is_null($countryInput)) {
        //     $states = $locationCountryModel->where('uuid', $countryInput)->count() > 0 ? $locationCountryModel->where('uuid', $countryInput)->first()->getStateDropDown() : [];
        // }
        // if(!is_null($stateInput)) {
        //     $cities = $locationStateModel->where('uuid', $stateInput)->count() > 0 ? $locationStateModel->where('uuid', $stateInput)->first()->getCityDropDown() : [];
        // }
        // if(!is_null($cityInput)) {
        //     $zipcodes = $locationCityModel->where('uuid', $cityInput)->count() > 0 ? $locationCityModel->where('uuid', $cityInput)->first()->getZipcodeDropDown() : [];
        // }

        // dd($categories);
        return view('admin.product.form', compact('product', 'pageTitle', 'route', 'role', 'statuses', 'brands', 'taxes', 'categories', 'selectedCategories', 'productUnits', 'arrival', 'productAttribute', 'productgst', 'defaultStockType', 'quickView'));
    }

    /**
     * @param AdminProductRequest $request
     * @param Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(AdminProductRequest $request, Product $product)
    {
        // dd($request->all());
        if ($request->hasFile('base_image_file') && $request->file('base_image_file')->isValid()) {
            $documentFile = $product->uploadMedia($request->file('base_image_file'));
            $document = $documentFile['path'] . $documentFile['name'];
            $request->merge(['base_image' => $document]);
        }

        $unitData = $request->get('unit_data');
        $unitDataArray = explode('|', $unitData);
        if (count($unitDataArray) > 1) {
            $request->merge([
                'unit' => $unitDataArray[0],
                'unit_name' => $unitDataArray[1]
            ]);
        }

        $request->merge(['status' => 'ACTIVE']);
        $request->merge(['audited' => '1']);
        $request->merge(['published' => '1']);
        $request->merge(['user_id' => auth()->user()->uuid]);
        $request->merge(['parent_id' => 0]);
        $productModel = $product->create($request->all());

        if ($request->has('category_id')) {
            $productModel->productCategory()->create([
                'category_id' => $request->category_id
            ]);
        }
        if ($request->has('sub_category_id')) {
            $productModel->productCategory()->create([
                'category_id' => $request->sub_category_id
            ]);
        }
        // if($request->has('categories'))
        // {
        //     $categories = $request->get('categories');
        //     foreach ($categories as $category)
        //     {
        //         $productModel->productCategory()->create([
        //             'category_id' => $category
        //         ]);
        //     }
        // }

        $route = $this->route;

        $redirectRoute = route("$route.index");
        if ($request->has('save_continue')) {
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
    public function edit($id, Brand $brandModel, Tax $taxModel, Category $categoryModel, ProductUnit $productUnitModel): View
    {
        // Product $product
        $product = Product::withoutGlobalScopes()->where('uuid', $id)->first();
        $categories = $categoryModel->getParentCategories();
        $brands = $brandModel->getDropDown();
        $taxes = $taxModel->getDropDown();
        $statuses = $product->getStatusesDropDown();
        $productUnits = $productUnitModel->getDropDown();
        $productAttribute = $product->getStockProductAttribute();
        $productgst = $product->getStockGST();
        $defaultStockType = $product->getDefaultStockType();
        $selectedCategories = $product->productCategory()->pluck('category_id')->toArray();
        $arrival = ArrivalType::all();
        $copy = request()->has('copy') ? true : false;
        $pageTitle = $copy ? "COPY PRODUCT" : "EDIT PRODUCT";
        $route = $this->route;
        $role = auth()->user()->role;
        $id = auth()->user()->uuid;
        $quickView = AdminQuickView::where('user_id', $id)->first();

        // dd('hi');

        // $duplicateRecord = Product::where('name',$product->name)->get();
        // dd($product->name);
        // dd($selectedCategories);
        // $product = $product->withoutGlobalScopes()->first();
        return view('admin.product.form', compact('product', 'pageTitle', 'route', 'role', 'statuses', 'copy', 'brands', 'taxes', 'categories', 'selectedCategories', 'productUnits', 'arrival', 'productAttribute', 'productgst', 'defaultStockType', 'quickView'));
    }

    /**
     * @param AdminProductRequest $request
     * @param Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(AdminProductRequest $request, $id)
    {

        $product = Product::withoutGlobalScopes()->where('uuid', $id)->first();

        if ($request->hasFile('base_image_file') && $request->file('base_image_file')->isValid()) {
            $documentFile = $product->uploadMedia($request->file('base_image_file'));
            $document = $documentFile['path'] . $documentFile['name'];
            $request->merge(['base_image' => $document]);
        }
        $unitData = $request->get('unit_data');
        $unitDataArray = explode('|', $unitData);
        if (count($unitDataArray) > 1) {
            $request->merge([
                'unit' => $unitDataArray[0],
                'unit_name' => $unitDataArray[1]
            ]);
        }
        $productUpdate = $product->update($request->all());
        if ($productUpdate) {
            if (isset($request['arrival_type']) && !empty($request['arrival_type'])) {
                $product->where('parent_id', '=', $request['uuid'])->update(['arrival_type' => $request['arrival_type']]);
            }
        }

        $product->productCategory()->delete();
        if ($request->has('category_id')) {
            $product->productCategory()->create([
                'category_id' => $request->category_id
            ]);
        }
        if ($request->has('sub_category_id')) {
            $product->productCategory()->create([
                'category_id' => $request->sub_category_id
            ]);
        }
        // if($request->has('categories'))
        // {
        //     $categories = $request->get('categories');
        //     foreach ($categories as $category)
        //     {
        //         $product->productCategory()->create([
        //             'category_id' => $category
        //         ]);
        //     }
        // }

        $route = $this->route;

        $redirectRoute = route("$route.index");
        if ($request->has('save_continue')) {
            $redirectRoute = route("$route.edit", $product->uuid);
        } elseif (Session::has('ProductPage')) {
            // echo 11;die;
            $page = session()->get("ProductPage");
            // $redirectRoute = ;
            return redirect()->route("$route.index", ['page' => $page])->with(['status' => 'success', 'message' => trans('success.admin|product|updated')]);
        }

        return redirect($redirectRoute)->with(['status' => 'success', 'message' => trans('success.admin|product|updated')]);
    }

    /**
     * @param Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $product = Product::withoutGlobalScopes()->where('uuid', $id)->first();
        $route = $this->route;
        if ($product->canDelete()) {
            try {
                $product->delete();
                return redirect(route("$route.index"))->with(['status' => 'success', 'message' => trans('success.admin|product|deleted')]);
            } catch (\Exception $exception) {
                return redirect(route("$route.index"))->with(['status' => 'warning', 'message' => trans('warning.admin|product|deleteNotPossible')]);
            }
        } else {
            return redirect(route("$route.index"))->with(['status' => 'warning', 'message' => trans('warning.admin|product|deleteNotPossible')]);
        }
    }

    /**
     * @param Product INPORT $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getImport()
    {
        $route = "admin.product";
        $pageTitle = "IMPORTS PRODUCTS";
        return view("$route.import", compact('pageTitle', 'route'));
    }

    /**
     * @param Product submit $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function import_parse(AdminImportCsvRequest $request, Excel $excel)
    {

        if ($request->hasFile('file_import')) {

            //set heaging to default
            HeadingRowFormatter::default('none');

            $import = new AdminProductImport;
            $excel->import($import, $request->file('file_import'));
            //(new AdminProductImport)->import($request->file('file_import'), null, \Maatwebsite\Excel\Excel::XLSX);

            $errbarcode = AdminProductImport::getError();
            $errbarcode = implode(", ", $errbarcode);

            $message = $import->getProductAddCount() . " Product Add successfully And  " . $import->getProductUpdateCount() . " Product Update successfully.";

            $errmsg = "There is some problem in variant id with the following Barcode : " . $errbarcode . ". may be this product already exist (please check sheet) . may be something wrong with variant id.";

            if (isset($errbarcode) && !empty($errbarcode) && $import->getProductAddCount() > 0 || $import->getProductUpdateCount() > 0) {

                return redirect(route("admin.productimport"))
                    ->with(['status' => 'success', 'message' => $message])
                    ->with(['errstatus' => 'danger', 'errmessage' => $errmsg]);
            } elseif ($import->getProductAddCount() == 0 && $import->getProductUpdateCount() == 0) {

                return redirect(route("admin.productimport"))
                    ->with(['errstatus' => 'danger', 'errmessage' => $errmsg]);
            } else {

                return redirect(route("admin.productimport"))
                    ->with(['status' => 'success', 'message' => $message]);
            }
        }
    }


    public function storedata(Request $request, ProductCategory $productCategory, Product $product)
    {
        $productVarientData = Product::withoutGlobalScopes()->where('uuid', $request->parent_id)->where('stock_type', $request->varient)->first();
        // $productVarientData = Product::withoutGlobalScopes()->where('parent_id',$request->parent_id)->where('stock_type', $request->varient)->first();
        if ($productVarientData != null) {

            $validation = $request->validate([
                'barcode' => 'required|numeric',
                'base_price' => 'required|numeric',
                // 'stock_type' => 'required|max:255',
                'stock_of' => 'required|numeric',
                'stoc_wt' => 'required|numeric',
                'stock_gst' => 'required|numeric',
                'default_stock_type' => 'required|numeric',
                'tax_id' => 'required|string',
                'unit_value' => 'required|numeric',
                'unit_data' => 'required',
                'status' => 'required',
                'description' => 'required|max:255',
                // 'short_description' => 'required|max:255',
                // 'base_image_file' => 'required|mimes:jpeg,jpg,bmp,png',
                'search_keyword' => 'max:255',
                'meta_title' => 'max:255',
                'meta_keywords' => 'max:255',
                'meta_description' => 'max:255'
            ]);
        } else {
            $validation = $request->validate([
                'barcode' => 'required|numeric',
                'base_price' => 'required|numeric',
                // 'stock_type' => 'required|max:255',
                'stock_of' => 'required|numeric',
                'stoc_wt' => 'required|numeric',
                'stock_gst' => 'required|numeric',
                'default_stock_type' => 'required|numeric',
                'tax_id' => 'required|string',
                'unit_value' => 'required|numeric',
                'unit_data' => 'required',
                'status' => 'required',
                'description' => 'required|max:255',
                // 'short_description' => 'required|max:255',
                'base_image_file' => 'required|mimes:jpeg,jpg,bmp,png',
                'search_keyword' => 'max:255',
                'meta_title' => 'max:255',
                'meta_keywords' => 'max:255',
                'meta_description' => 'max:255'
            ]);
        }


        // dd($validation);

        $productCategoryData = $productCategory->where('product_id', $request['parent_id'])->pluck('category_id');
        $productCategoryData = $productCategoryData->toArray();
        // dd($productCategoryData);

        if ($request->hasFile('base_image_file') && $request->file('base_image_file')->isValid()) {
            $documentFile = $product->uploadMedia($request->file('base_image_file'));
            $document = $documentFile['path'] . $documentFile['name'];
            // $request->merge(['base_image' => $document]);
        }

        $unitData = $request->get('unit_data');
        $unitDataArray = explode('|', $unitData);
        if (count($unitDataArray) > 1) {
            $request->merge([
                'unit' => $unitDataArray[0],
                'unit_name' => $unitDataArray[1]
            ]);
        }


        if ($productVarientData != null) {
            Product::withoutGlobalScopes()->where('uuid', $request->parent_id)->where('stock_type', $request->varient)->update([
                // Product::withoutGlobalScopes()->where('parent_id',$request->parent_id)->where('stock_type', $request->varient)->update([
                // 'parent_id' => $request['parent_id'],
                'name' => $request['name'],
                'slug' => $request['slug'],
                'status' => $request['status'],
                'brand_id' => $request['brand_id'],
                'arrival_type' => $request['arrival_type'],
                'status' => $request['status'],
                'description' => $request['description'],
                // 'short_description' => $request['short_description'],
                'base_image' => ($request->base_image_file != null) ? $document : $productVarientData->base_image,
                'meta_description' => $request['meta_description'],
                'meta_keyword' => $request['meta_keyword'],
                'meta_title' => $request['meta_title'],
                'search_keyword' => $request['search_keyword'],
                'barcode' => $request['barcode'],
                'price' => $request['base_price'],
                'base_price' => $request['base_price'],
                'stock_type' => $request['varient'],
                // 'stock_type' => $request['stock_type'],
                'stock_of' => $request['stock_of'],
                'stoc_wt' => $request['stoc_wt'],
                'stock_gst' => $request['stock_gst'],
                'default_stock_type' => $request['default_stock_type'],
                'tax_id' => $request['tax_id'],
                'unit_value' => $request['unit_value'],
                'unit' => $request['unit'],
                'unit_name' => $request['unit_name'],
                'variant_id' => $request['variant_id'],
            ]);
            return redirect()->back()->with('message', 'product variant updated successfully.');
            // return redirect(route('admin.products.index'))->with('message', 'product variant updated successfully.');

        } else {
            $userData = Product::withoutGlobalScopes()->where('uuid', $request->parent_id)->select('user_id')->first();
            $product = Product::create([
                'audited' => '0',
                'published' => '0',
                'user_id' => $userData->user_id,
                'parent_id' => $request['parent_id'],
                'name' => $request['name'],
                'slug' => str_slug($request['slug'], '-'),
                'status' => $request['status'],
                'brand_id' => $request['brand_id'],
                'arrival_type' => $request['arrival_type'],
                'status' => $request['status'],
                'description' => $request['description'],
                // 'short_description' => $request['short_description'],
                'base_image' => $document,
                'meta_description' => $request['meta_description'],
                'meta_keyword' => $request['meta_keyword'],
                'meta_title' => $request['meta_title'],
                'search_keyword' => $request['search_keyword'],
                'barcode' => $request['barcode'],
                'price' => $request['base_price'],
                'base_price' => $request['base_price'],
                'stock_type' => $request['varient'],
                // 'stock_type' => $request['stock_type'],
                'stock_of' => $request['stock_of'],
                'stoc_wt' => $request['stoc_wt'],
                'stock_gst' => $request['stock_gst'],
                'default_stock_type' => $request['default_stock_type'],
                'tax_id' => $request['tax_id'],
                'unit_value' => $request['unit_value'],
                'unit' => $request['unit'],
                'unit_name' => $request['unit_name'],
                'variant_id' => $request['variant_id'],
            ]);

            foreach ($productCategoryData as $key => $value) {
                $productCategory = new ProductCategory;
                $productCategory->product_id = $product->uuid;
                $productCategory->category_id = $value;
                $productCategory->save();
            }
            return redirect()->back()->with('message', 'product variant created successfully.');
            // return redirect(route('admin.products.index'))->with('message', 'product variant created successfully.');

        }
    }


    public function variantsindex(Request $request, Product $productModel, Excel $excel, Brand $brandModel, User $userModel)
    {

        $filters = [];
        $filters[] = ['title' => 'No'];

        $filters[] = [
            'title' => 'Thumbnail',
        ];

        $filters[] = [
            'title' => 'Barcode',
            'column' => 'barcode',
            'operator' => 'LIKE',
            'sorting' => true,
            'search' => [
                'type' => 'text',
                'placeholder' => 'Search Barcode'
            ]
        ];
        $filters[] = [
            'title' => 'Variant Id',
            'column' => 'variant_id',
            'operator' => 'LIKE',
            'sorting' => true,
            // 'search' => [
            //     'type' => 'text',
            //     'placeholder' => 'Search Barcode'
            // ]
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
            // 'title' => 'Manufacturer',
            'column' => 'brand_id',
            'operator' => '=',
            'sorting' => true,
            'search' => [
                'type' => 'select',
                'placeholder' => 'Show all',
                'data' => $brandModel->getDropDown()
            ]
        ];


        $filters[] = [
            'title' => 'User',
            'column' => 'user_id',
            'operator' => '=',
            'sorting' => true,
            'search' => [
                'type' => 'select',
                'placeholder' => 'Show all',
                'data' => $userModel->getDropDown()
            ]
        ];


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

        //        $filters[] = [
        //            'title' => 'Action'
        //        ];

        // $filters[] = [

        //     'title' => 'Audited'

        // ];

        $tableName = $productModel->getTable();
        $url = '/admin/product-variants';
        $this->setGridModel($productModel);
        $this->setGridRequest($request);
        $this->setFilters($filters);

        if (\Route::current()->getName() !== 'admin.products.index') {

            $this->setScopes(["OfUserNotAdmin"]);
            //$this->setScopesWithValue(['barcode' => '1600000000196']);
        }

        $this->setScopes(["OfNoParent"]);
        $this->setSorting(['sorting_field' => $tableName . '_sorting_field', 'sort' => $tableName . '_sort', 'default_field' => 'name', 'default_sort' => 'ASC']);

        $this->setGridUrl($url);

        $this->setGridVariables();

        if ($request->has('export_data')) {
            $this->setPaginationEnable(false);
            $data = $this->getGridData();
        } else {
            $data = $this->getGridData();

            $dataGridTitle = $this->gridTitles();
            $dataGridSearch = $this->gridSearch();
            $dataGridPagination = $this->gridPagination($data);
        }

        if ($request->has('export_data')) {
            $fileName = 'PRODUCT_DATA';
            return $excel->download(new DataGridExport('admin.product.export', $data), "$fileName.xlsx");
        }


        $route = 'admin.products';

        if (\Route::current()->getName() !== 'admin.products.variantsindex') {

            $pageTitle = "VARIANTS PRODUCTS";
        } else {
            $pageTitle = "VARIANTS PRODUCTS";
        }

        if (Session::has('ProductPage')) {
            Session::forget('ProductPage');
        }
        Session::put('ProductPage', $request->input('page') ?? 1);

        // $data = tap($data,function($query){
        //     return $query->getCollection()->transform(function ($value) {
        //         $check = '';
        //         $cval = 1;

        //         $onOff = '';
        //         if($value->on_off == 1){
        //             $onOff = 'checked';
        //             $cval = 0;
        //         }

        //         $check =  '<label class="switchNew">
        //         <input type="checkbox" '. $onOff .' class="onoff" data-id="'.$value->uuid.'" data-onoff="'.$value->on_off.'" data-conoff="'.$cval.'">
        //         <span class="slider round"></span>
        //         </label>';
        //         $value->switch = $check;
        //         // dd($value);
        //         return $value;
        //     });

        // });

        // dd($data);
        if ($request->ajax()) {
            return view('admin.product.variantgrid', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'route'));
        } else {
            return view('admin.product.variantindex', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'pageTitle', 'route'));
        }
    }


    public function variantsedit(Product $product, Brand $brandModel, Tax $taxModel, Category $categoryModel, ProductUnit $productUnitModel, $id): View
    {

        $productData = $product->where('uuid', $id)->first();
        $categories = $categoryModel->getParentCategories();
        $brands = $brandModel->getDropDown();
        $taxes = $taxModel->getDropDown();
        $statuses = $product->getStatusesDropDown();
        $productUnits = $productUnitModel->getDropDown();
        $productAttribute = $product->getStockProductAttribute();
        $defaultStockType = $product->getDefaultStockType();
        $productgst = $product->getStockGST();
        $selectedCategories = $product->productCategory()->pluck('category_id')->toArray();
        $arrival = ArrivalType::all();
        $copy = request()->has('copy') ? true : false;
        $pageTitle = $copy ? "COPY PRODUCT" : "EDIT PRODUCT VARIANTS";
        $route = $this->route;
        $role = auth()->user()->role;

        // dd($productData);
        return view('admin.product.variantform', compact('product', 'pageTitle', 'route', 'role', 'statuses', 'copy', 'brands', 'taxes', 'categories', 'selectedCategories', 'productUnits', 'arrival', 'productAttribute', 'productgst', 'productData', 'id', 'defaultStockType'));
    }

    public function variantsupdate(Request $request, Product $product)
    {
        $validation = $request->validate([
            'barcode' => 'required|numeric',
            'base_price' => 'required|numeric',
            'stock_type' => 'required|max:255',
            'stock_of' => 'required|numeric',
            'stoc_wt' => 'required|numeric',
            'stock_gst' => 'required|numeric',
            'tax_id' => 'required|string',
            'unit_value' => 'required|numeric',
            'unit_data' => 'required',
            'default_stock_type' => 'required|numeric',
            'status' => 'required',
            'description' => 'required|max:255',
            'short_description' => 'required|max:255',
            // 'base_image_file' => 'required|mimes:jpeg,bmp,png',
            'search_keyword' => 'max:255',
            'meta_title' => 'max:255',
            'meta_keywords' => 'max:255',
            'meta_description' => 'max:255'
        ]);

        $document = '';
        if ($request->hasFile('base_image_file') && $request->file('base_image_file')->isValid()) {
            $documentFile = $product->uploadMedia($request->file('base_image_file'));
            $document = $documentFile['path'] . $documentFile['name'];
            // $request->merge(['base_image' => $document]);
        }

        $unitData = $request->get('unit_data');
        $unitDataArray = explode('|', $unitData);
        if (count($unitDataArray) > 1) {
            $request->merge([
                'unit' => $unitDataArray[0],
                'unit_name' => $unitDataArray[1]
            ]);
        }

        $product->where('uuid', $request['product_id'])
            ->update([
                'barcode' => $request['barcode'],
                'base_price' => $request['base_price'],
                'stock_type' => $request['stock_type'],
                'stock_of' => $request['stock_of'],
                'stoc_wt' => $request['stoc_wt'],
                'stock_gst' => $request['stock_gst'],
                'tax_id' => $request['tax_id'],
                'unit_value' => $request['unit_value'],
                'unit' => $request['unit'],
                'unit_name' => $request['unit_name'],
                'default_stock_type' => $request['default_stock_type'],
                'status' => $request['status'],
                'description' => $request['description'],
                'short_description' => $request['short_description'],
                // 'base_image' => $document,
                'meta_description' => $request['meta_description'],
                'meta_keyword' => $request['meta_keyword'],
                'meta_title' => $request['meta_title'],
                'search_keyword' => $request['search_keyword'],
                // new added
                // 'parent_id' => $request['parent_id'],
                'variant_id' => $request['variant_id'],
                'colour' => $request['colour'],
                'colour_variants' => $request['colour_variants'],
                'size_variants' => $request['size_variants'],
            ]);

        if ($document != '') {
            $product->where('uuid', $request['product_id'])
                ->update([
                    'base_image' => $document,
                ]);
        }
        return redirect(route('admin.products.variantsindex'))->with('message', 'product variant updated successfully.');
    }

    public function getProductVarient(Request $request)
    {
        $product = Product::withoutGlobalScopes()->where('uuid', $request->product_id)->where('stock_type', $request->type)->first();
        if ($product != null) {
            $product = Product::withoutGlobalScopes()->where('uuid', $product->parent_id)->first();
            $productData = Product::withoutGlobalScopes()->where('parent_id', $product->uuid)->where('stock_type', $request->type)->first();
            return  json_encode($productData);
        }
        return  json_encode($product);
    }
}
