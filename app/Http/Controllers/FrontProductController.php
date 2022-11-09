<?php

namespace App\Http\Controllers;

use DB;
use App\User;
// use App\Http\Requests\AdminProductRequest;
use App\Models\Tax;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use Illuminate\View\View;
use App\Models\ArrivalType;
use App\Models\ProductUnit;
use App\Models\Notification;
use App\Models\UserDocument;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;
use App\Models\EmailTemplate;
use App\Imports\ProductImport;
use App\Exports\DataGridExport;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Mail;
use App\Models\History\ProductHistory;
use App\Http\Controllers\Helpers\DataGrid;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use App\Http\Requests\FrontSupplierImportCsvRequest;
use App\Imports\SupplierProductImport;
use App\Http\Requests\ImportCsvSupplierRequest;

class FrontProductController extends Controller
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

        // $filters[] = [
        //     'title' => 'Status',
        //     'column' => 'status',
        //     'operator' => '=',
        //     'sorting' => true,
        //     'search' => [
        //         'type' => 'select',
        //         'placeholder' => 'Show all',
        //         'data' => $productModel->getStatusesDropDown()
        //     ]
        // ];

        $filters[] = [
            'title' => 'Action'
        ];

        $tableName = $productModel->getTable();

        $url = $this->dataUrl;

        $this->setGridModel($productModel);

        $this->setGridRequest($request);

        $this->setFilters($filters);

        $this->setScopes(["ofUser"]);

        // $this->setScopes(["Active"]);

        $this->setSorting(['sorting_field' => $tableName . '_sorting_field', 'sort' => $tableName . '_sort', 'default_field' => 'created_at', 'default_sort' => 'DESC']);

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

    public function create(Product $product, Brand $brandModel, Tax $taxModel, Category $categoryModel, ProductUnit $productUnitModel): View

    {

        $categories = $categoryModel->getParentCategories();

        $brands = $brandModel->getDropDown();

        $taxes = $taxModel->getDropDown();

        $statuses = $product->getStatusesDropDown();

        $productUnits = $productUnitModel->getDropDown();

        $selectedCategories = $product->productCategory()->pluck('category_id')->toArray();

        $arrival = ArrivalType::all();
        $defaultStockType = $product->getDefaultStockType();

        $productAttribute = $product->getStockProductAttribute();

        $productgst = $product->getStockGST();

        $pageTitle = "REQUEST PRODUCT";

        $route = $this->route;

        $role = auth()->user()->role;

        return view('supplier.product.form', compact('product', 'pageTitle', 'route', 'role', 'statuses', 'brands', 'taxes', 'categories', 'selectedCategories', 'productUnits', 'productAttribute', 'productgst', 'arrival', 'defaultStockType'));
    }



    /**

     * @param AdminProductRequest $request

     * @param Product $product

     * @return \Illuminate\Http\RedirectResponse

     */

    public function store(Request $request, Product $product)
    {
        // dd($request->all());

        $route = $this->route;

        if (isset($request) && !empty($request)) {

            $request->validate([
                'name' => 'required',
                'slug' => 'required',
                'brand_id' => 'required',
                'arrival_type' => 'required',
                'base_image_file' => 'required',
                'categories' => 'required',
            ], [
                'name.required' => 'name field is required.',
                'slug.required' => 'slug field is required.',
                'brand_id.required' => 'brand field is required.',
                // 'brand_id.required' => 'manufacturer field is required.',
                'arrival_type.required' => 'arrival type field is required.',
                'base_image_file.required' => 'image field is required.',
                'categories.required' => 'arrival type field is required.',
            ]);

            if (isset($request['single_barcode']) && !empty($request['single_barcode'])) {

                $request->validate([
                    'single_barcode' => 'required',
                    'single_base_price' => 'required',
                    'single_stock_of' => 'required',
                    'single_default_stock_type' => 'required',
                    'single_stoc_wt' => 'required',
                    // 'single_stock_gst' => 'required',
                    'single_tax_id' => 'required',
                    'single_unit_value' => 'required',
                    'single_unit_data' => 'required',
                    'single_description' => 'required',
                    'single_short_description' => 'required',
                    'single_base_image_file' => 'required|mimes:jpeg,jpg,bmp,png',
                ], [
                    'single_barcode.required' => 'barcode field is required.',
                    'single_base_price.required' => 'base price field is required.',
                    'single_stock_of.required' => 'pack size field is required.',
                    'single_default_stock_type.required' => 'default stock type field is required.',
                    'single_stoc_wt.required' => 'stock weight field is required.',
                    // 'single_stock_gst.required' => 'stock VAT field is required.',
                    'single_tax_id.required' => 'tax field is required.',
                    'single_unit_value.required' => 'unit field is required.',
                    'single_unit_data.required' => 'UOM field is required.',
                    'single_description.required' => 'description field is required.',
                    'single_short_description.required' => 'short description field is required.',
                    'single_base_image_file.required' => 'Image field is required.',
                ]);
            } elseif (isset($request['pallets_barcode']) && !empty($request['pallets_barcode'])) {

                $request->validate([
                    'pallets_barcode' => 'required',
                    'pallets_base_price' => 'required',
                    'pallets_stock_of' => 'required',
                    'pallets_default_stock_type' => 'required',
                    'pallets_stoc_wt' => 'required',
                    // 'pallets_stock_gst' => 'required',
                    'pallets_tax_id' => 'required',
                    'pallets_unit_value' => 'required',
                    'pallets_unit_data' => 'required',
                    'pallets_description' => 'required',
                    'pallets_short_description' => 'required',
                    'pallets_base_image_file' => 'required|mimes:jpeg,jpg,bmp,png',
                ], [
                    'pallets_barcode.required' => 'barcode field is required.',
                    'pallets_base_price.required' => 'base price field is required.',
                    'pallets_stock_of.required' => 'pack size field is required.',
                    'pallets_default_stock_type.required' => 'default stock type field is required.',
                    'pallets_stoc_wt.required' => 'stock weight field is required.',
                    // 'pallets_stock_gst.required' => 'stock VAT field is required.',
                    'pallets_tax_id.required' => 'tax field is required.',
                    'pallets_unit_value.required' => 'unit field is required.',
                    'pallets_unit_data.required' => 'UOM field is required.',
                    'pallets_description.required' => 'description field is required.',
                    'pallets_short_description.required' => 'short description field is required.',
                    'pallets_base_image_file.required' => 'Image field is required.',
                ]);
            } elseif (isset($request['case_barcode']) && !empty($request['case_barcode'])) {

                $request->validate([
                    'case_barcode' => 'required',
                    'case_base_price' => 'required',
                    'case_stock_of' => 'required',
                    'case_default_stock_type' => 'required',
                    'case_stoc_wt' => 'required',
                    // 'case_stock_gst' => 'required',
                    'case_tax_id' => 'required',
                    'case_unit_value' => 'required',
                    'case_unit_data' => 'required',
                    'case_description' => 'required',
                    'case_short_description' => 'required',
                    'case_base_image_file' => 'required|mimes:jpeg,jpg,bmp,png',
                ], [
                    'case_barcode.required' => 'barcode field is required.',
                    'case_base_price.required' => 'base price field is required.',
                    'case_stock_of.required' => 'pack size field is required.',
                    'case_default_stock_type.required' => 'default stock type field is required.',
                    'case_stoc_wt.required' => 'stock weight field is required.',
                    // 'case_stock_gst.required' => 'stock VAT field is required.',
                    'case_tax_id.required' => 'tax field is required.',
                    'case_unit_value.required' => 'unit field is required.',
                    'case_unit_data.required' => 'UOM field is required.',
                    'case_description.required' => 'description field is required.',
                    'case_short_description.required' => 'short description field is required.',
                    'case_base_image_file.required' => 'Image field is required.',
                ]);
            } elseif (isset($request['shrink_barcode']) && !empty($request['shrink_barcode'])) {

                $request->validate([
                    'shrink_barcode' => 'required',
                    'shrink_base_price' => 'required',
                    'shrink_stock_of' => 'required',
                    'shrink_default_stock_type' => 'required',
                    'shrink_stoc_wt' => 'required',
                    // 'shrink_stock_gst' => 'required',
                    'shrink_tax_id' => 'required',
                    'shrink_unit_value' => 'required',
                    'shrink_unit_data' => 'required',
                    'shrink_description' => 'required',
                    'shrink_short_description' => 'required',
                    'shrink_base_image_file' => 'required|mimes:jpeg,jpg,bmp,png',
                ], [
                    'shrink_barcode.required' => 'barcode field is required.',
                    'shrink_base_price.required' => 'base price field is required.',
                    'shrink_stock_of.required' => 'pack size field is required.',
                    'shrink_default_stock_type.required' => 'default stock type field is required.',
                    'shrink_stoc_wt.required' => 'stock weight field is required.',
                    // 'shrink_stock_gst.required' => 'stock VAT field is required.',
                    'shrink_tax_id.required' => 'tax field is required.',
                    'shrink_unit_value.required' => 'unit field is required.',
                    'shrink_unit_data.required' => 'UOM field is required.',
                    'shrink_description.required' => 'description field is required.',
                    'shrink_short_description.required' => 'short description field is required.',
                    'shrink_base_image_file.required' => 'Image field is required.',
                ]);
            } else {
                return redirect(route("$route.create"))->withErrors(['Plz add atleast one variant']);
            }
        }

        // dd($request->variant_id);
        // DB::enableQueryLog(); // Enable query log
        $searchMP = $product->where('barcode', 'request->single_barcode')
            ->orWhere('barcode', 'request->pallets_barcode')
            ->orWhere('barcode', 'request->case_barcode')
            ->orWhere('barcode', 'request->shrink_barcode')->first();
        // // dd(DB::getQueryLog()); // Show results of log
        // // dd($searchMP);
        // dd($request->all()); 

        $productImage = '';
        $single_productImage = '';
        $shrink_productImage = '';
        $case_productImage = '';
        $pallets_productImage = '';
        $single_unit = '';
        $single_unit_name = '';
        $pallets_unit = '';
        $pallets_unit_name = '';
        $case_unit = '';
        $case_unit_name = '';
        $shrink_unit = '';
        $shrink_unit_name = '';

        if ($request->hasFile('base_image_file') && $request->file('base_image_file')->isValid()) {
            $documentFile = $product->uploadMedia($request->file('base_image_file'));
            $document = $documentFile['path'] . $documentFile['name'];
            $request->merge(['base_image' => $document]);
            $productImage = $document;
        }

        if ($request->hasFile('single_base_image_file') && $request->file('single_base_image_file')->isValid()) {
            $documentFile = $product->uploadMedia($request->file('single_base_image_file'));
            $document = $documentFile['path'] . $documentFile['name'];
            $request->merge(['base_image' => $document]);
            $single_productImage = $document;
        }

        if ($request->hasFile('case_base_image_file') && $request->file('case_base_image_file')->isValid()) {
            $documentFile = $product->uploadMedia($request->file('case_base_image_file'));
            $document = $documentFile['path'] . $documentFile['name'];
            $request->merge(['base_image' => $document]);
            $case_productImage = $document;
        }

        if ($request->hasFile('shrink_base_image_file') && $request->file('shrink_base_image_file')->isValid()) {
            $documentFile = $product->uploadMedia($request->file('shrink_base_image_file'));
            $document = $documentFile['path'] . $documentFile['name'];
            $request->merge(['base_image' => $document]);
            $shrink_productImage = $document;
        }

        if ($request->hasFile('pallets_base_image_file') && $request->file('pallets_base_image_file')->isValid()) {
            $documentFile = $product->uploadMedia($request->file('pallets_base_image_file'));
            $document = $documentFile['path'] . $documentFile['name'];
            $request->merge(['base_image' => $document]);
            $pallets_productImage = $document;
        }

        $unitData = $request->get('single_unit_data');
        $unitDataArray = explode('|', $unitData);
        if (count($unitDataArray) > 1) {
            $request->merge([
                'unit' => $unitDataArray[0],
                'unit_name' => $unitDataArray[1]
            ]);

            $single_unit = $unitDataArray[0];
            $single_unit_name = $unitDataArray[1];
        }

        $unitData = $request->get('pallets_unit_data');
        $unitDataArray = explode('|', $unitData);
        if (count($unitDataArray) > 1) {
            $request->merge([
                'unit' => $unitDataArray[0],
                'unit_name' => $unitDataArray[1]
            ]);

            $pallets_unit = $unitDataArray[0];
            $pallets_unit_name = $unitDataArray[1];
        }

        $unitData = $request->get('case_unit_data');
        $unitDataArray = explode('|', $unitData);
        if (count($unitDataArray) > 1) {
            $request->merge([
                'unit' => $unitDataArray[0],
                'unit_name' => $unitDataArray[1]
            ]);

            $case_unit = $unitDataArray[0];
            $case_unit_name = $unitDataArray[1];
        }

        $unitData = $request->get('shrink_unit_data');
        $unitDataArray = explode('|', $unitData);
        if (count($unitDataArray) > 1) {
            $request->merge([
                'unit' => $unitDataArray[0],
                'unit_name' => $unitDataArray[1]
            ]);

            $shrink_unit = $unitDataArray[0];
            $shrink_unit_name = $unitDataArray[1];
        }

        // dd($single_unit_name);
        // dd($request->all());

        if (isset($searchMP) && !empty($searchMP)) {
        } else {

            $masterProduct = Product::create([
                'name' => $request['name'],
                'slug' => $request['slug'],
                'brand_id' => $request['brand_id'],
                'arrival_type' => $request['arrival_type'],
                'base_image' => $productImage,
                'status' => 'INACTIVE',
                'parent_id' => '0',
                'variant_id' => mt_rand(1000000000, 9999999999),
            ]);

            if ($request->has('categories')) {
                $categories = $request->get('categories');
                foreach ($categories as $category) {
                    $masterProduct->productCategory()->create([
                        'category_id' => $category
                    ]);
                }
            }

            if (isset($request['single_barcode']) && !empty($request['single_barcode'])) {

                $singleProduct = Product::create([
                    'name' => $request['name'],
                    'slug' => $request['slug'],
                    'brand_id' => $request['brand_id'],
                    'arrival_type' => $request['arrival_type'],
                    'base_image' => $single_productImage,
                    'status' => $request['status'],
                    'barcode' => $request['single_barcode'],
                    'base_price' => $request['single_base_price'],
                    'stock_type' => $request['single_stock_type'],
                    'stock_of' => $request['single_stock_of'],
                    'default_stock_type' => $request['single_default_stock_type'],
                    'stoc_wt' => $request['single_stoc_wt'],
                    'stock_gst' => $request['single_stock_gst'],
                    'tax_id' => $request['single_tax_id'],
                    'unit_value' => $request['single_unit_value'],
                    'unit' => $single_unit,
                    'unit_name' => $single_unit_name,
                    'description' => $request['single_description'],
                    'short_description' => $request['single_short_description'],
                    'search_keyword' => $request['single_search_keyword'],
                    'meta_title' => $request['single_meta_title'],
                    'meta_keyword' => $request['single_meta_keyword'],
                    'meta_description' => $request['single_meta_description'],
                    'status' => 'INACTIVE',
                    'parent_id' => $masterProduct['uuid'],
                    'variant_id' => $masterProduct['variant_id'],
                ]);

                if ($request->has('categories')) {
                    $categories = $request->get('categories');
                    foreach ($categories as $category) {
                        $singleProduct->productCategory()->create([
                            'category_id' => $category
                        ]);
                    }
                }
            }
            if (isset($request['pallets_barcode']) && !empty($request['pallets_barcode'])) {

                $palletsProduct = Product::create([
                    'name' => $request['name'],
                    'slug' => $request['slug'],
                    'brand_id' => $request['brand_id'],
                    'arrival_type' => $request['arrival_type'],
                    'base_image' => $pallets_productImage,
                    'status' => $request['status'],
                    'barcode' => $request['pallets_barcode'],
                    'base_price' => $request['pallets_base_price'],
                    'stock_type' => $request['pallets_stock_type'],
                    'stock_of' => $request['pallets_stock_of'],
                    'default_stock_type' => $request['pallets_default_stock_type'],
                    'stoc_wt' => $request['pallets_stoc_wt'],
                    'stock_gst' => $request['pallets_stock_gst'],
                    'tax_id' => $request['pallets_tax_id'],
                    'unit_value' => $request['pallets_unit_value'],
                    'unit' => $pallets_unit,
                    'unit_name' => $pallets_unit_name,
                    'description' => $request['pallets_description'],
                    'short_description' => $request['pallets_short_description'],
                    'search_keyword' => $request['pallets_search_keyword'],
                    'meta_title' => $request['pallets_meta_title'],
                    'meta_keyword' => $request['pallets_meta_keyword'],
                    'meta_description' => $request['pallets_meta_description'],
                    'status' => 'INACTIVE',
                    'parent_id' => $masterProduct['uuid'],
                    'variant_id' => $masterProduct['variant_id'],
                ]);

                if ($request->has('categories')) {
                    $categories = $request->get('categories');
                    foreach ($categories as $category) {
                        $palletsProduct->productCategory()->create([
                            'category_id' => $category
                        ]);
                    }
                }
            }
            if (isset($request['case_barcode']) && !empty($request['case_barcode'])) {

                $caseProduct = Product::create([
                    'name' => $request['name'],
                    'slug' => $request['slug'],
                    'brand_id' => $request['brand_id'],
                    'arrival_type' => $request['arrival_type'],
                    'base_image' => $case_productImage,
                    'status' => $request['status'],
                    'barcode' => $request['case_barcode'],
                    'base_price' => $request['case_base_price'],
                    'stock_type' => $request['case_stock_type'],
                    'stock_of' => $request['case_stock_of'],
                    'default_stock_type' => $request['case_default_stock_type'],
                    'stoc_wt' => $request['case_stoc_wt'],
                    'stock_gst' => $request['case_stock_gst'],
                    'tax_id' => $request['case_tax_id'],
                    'unit_value' => $request['case_unit_value'],
                    'unit' => $case_unit,
                    'unit_name' => $case_unit_name,
                    'description' => $request['case_description'],
                    'short_description' => $request['case_short_description'],
                    'search_keyword' => $request['case_search_keyword'],
                    'meta_title' => $request['case_meta_title'],
                    'meta_keyword' => $request['case_meta_keyword'],
                    'meta_description' => $request['case_meta_description'],
                    'status' => 'INACTIVE',
                    'parent_id' => $masterProduct['uuid'],
                    'variant_id' => $masterProduct['variant_id'],
                ]);

                if ($request->has('categories')) {
                    $categories = $request->get('categories');
                    foreach ($categories as $category) {
                        $caseProduct->productCategory()->create([
                            'category_id' => $category
                        ]);
                    }
                }
            }
            if (isset($request['shrink_barcode']) && !empty($request['shrink_barcode'])) {

                $shrinkProduct = Product::create([
                    'name' => $request['name'],
                    'slug' => $request['slug'],
                    'brand_id' => $request['brand_id'],
                    'arrival_type' => $request['arrival_type'],
                    'base_image' => $shrink_productImage,
                    'status' => $request['status'],
                    'barcode' => $request['shrink_barcode'],
                    'base_price' => $request['shrink_base_price'],
                    'stock_type' => $request['shrink_stock_type'],
                    'stock_of' => $request['shrink_stock_of'],
                    'default_stock_type' => $request['shrink_default_stock_type'],
                    'stoc_wt' => $request['shrink_stoc_wt'],
                    'stock_gst' => $request['shrink_stock_gst'],
                    'tax_id' => $request['shrink_tax_id'],
                    'unit_value' => $request['shrink_unit_value'],
                    'unit' => $shrink_unit,
                    'unit_name' => $shrink_unit_name,
                    'description' => $request['shrink_description'],
                    'short_description' => $request['shrink_short_description'],
                    'search_keyword' => $request['shrink_search_keyword'],
                    'meta_title' => $request['shrink_meta_title'],
                    'meta_keyword' => $request['shrink_meta_keyword'],
                    'meta_description' => $request['shrink_meta_description'],
                    'status' => 'INACTIVE',
                    'parent_id' => $masterProduct['uuid'],
                    'variant_id' => $masterProduct['variant_id'],
                ]);

                if ($request->has('categories')) {
                    $categories = $request->get('categories');
                    foreach ($categories as $category) {
                        $shrinkProduct->productCategory()->create([
                            'category_id' => $category
                        ]);
                    }
                }
            }
        }

        $route = $this->route;

        $redirectRoute = route("$route.index");

        if ($request->has('save_continue')) {
            $redirectRoute = route("$route.edit", $masterProduct->uuid);
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

    public function edit(Product $product, Brand $brandModel, Tax $taxModel, Category $categoryModel, ProductUnit $productUnitModel): View
    {

        // DB::enableQueryLog(); // Enable query log
        // dd(DB::getQueryLog()); // Show results of log
        $singleProduct = Product::where('parent_id', $product->uuid)->where('stock_type', 'single')->first();
        $palletsProduct = Product::where('parent_id', $product->uuid)->where('stock_type', 'pallets')->first();
        $caseProduct = Product::where('parent_id', $product->uuid)->where('stock_type', 'case')->first();
        $shrinkProduct = Product::where('parent_id', $product->uuid)->where('stock_type', 'shrink')->first();

        $single = '';
        $pallets = '';
        $case = '';
        $shrink = '';
        isset($singleProduct) ? $single = $singleProduct : $single = '';
        isset($palletsProduct) ? $pallets = $palletsProduct : $pallets = '';
        isset($caseProduct) ? $case = $caseProduct : $case = '';
        isset($shrinkProduct) ? $shrink = $shrinkProduct : $shrink = '';

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

        return view('supplier.product.form', compact('product', 'pageTitle', 'route', 'statuses', 'copy', 'brands', 'taxes', 'categories', 'selectedCategories', 'productUnits', 'productAttribute', 'productgst', 'arrival', 'defaultStockType', 'single', 'pallets', 'case', 'shrink'));
    }

    /**

     * @param AdminProductRequest $request

     * @param Product $product

     * @return \Illuminate\Http\RedirectResponse

     */

    public function update(Request $request, Product $product)
    {

        // dd($request->all());

        $singleProduct = Product::where('parent_id', $product->uuid)->where('stock_type', 'single')->first();
        $palletsProduct = Product::where('parent_id', $product->uuid)->where('stock_type', 'pallets')->first();
        $caseProduct = Product::where('parent_id', $product->uuid)->where('stock_type', 'case')->first();
        $shrinkProduct = Product::where('parent_id', $product->uuid)->where('stock_type', 'shrink')->first();

        $single = '';
        $pallets = '';
        $case = '';
        $shrink = '';
        isset($singleProduct) ? $single = $singleProduct : $single = '';
        isset($palletsProduct) ? $pallets = $palletsProduct : $pallets = '';
        isset($caseProduct) ? $case = $caseProduct : $case = '';
        isset($shrinkProduct) ? $shrink = $shrinkProduct : $shrink = '';

        // dd($shrink);

        $productImage = '';
        $single_productImage = '';
        $shrink_productImage = '';
        $case_productImage = '';
        $pallets_productImage = '';
        $single_unit = '';
        $single_unit_name = '';
        $pallets_unit = '';
        $pallets_unit_name = '';
        $case_unit = '';
        $case_unit_name = '';
        $shrink_unit = '';
        $shrink_unit_name = '';

        if (isset($request['shrink_barcode']) && !empty($request['shrink_barcode'])) {

            $request->validate([
                'shrink_barcode' => 'required',
                'shrink_base_price' => 'required',
                'shrink_stock_of' => 'required',
                // 'shrink_default_stock_type' => 'required',
                'shrink_stoc_wt' => 'required',
                // 'shrink_stock_gst' => 'required',
                // 'shrink_tax_id' => 'required',
                'shrink_unit_value' => 'required',
                // 'shrink_unit_data' => 'required',
                'shrink_description' => 'required',
                'shrink_short_description' => 'required',
                'shrink_base_image_file' => 'required|mimes:jpeg,jpg,bmp,png',
            ], [
                'shrink_barcode.required' => 'barcode field is required.',
                'shrink_base_price.required' => 'base price field is required.',
                'shrink_stock_of.required' => 'pack size field is required.',
                // 'shrink_default_stock_type.required' => 'default stock type field is required.',
                'shrink_stoc_wt.required' => 'stock weight field is required.',
                // 'shrink_stock_gst.required' => 'stock VAT field is required.',
                // 'shrink_tax_id.required' => 'tax field is required.',
                'shrink_unit_value.required' => 'unit field is required.',
                // 'shrink_unit_data.required' => 'UOM field is required.',
                'shrink_description.required' => 'description field is required.',
                'shrink_short_description.required' => 'short description field is required.',
                'shrink_base_image_file.required' => 'Image field is required.',
            ]);
        } elseif (isset($request['single_barcode']) && !empty($request['single_barcode'])) {

            $request->validate([
                'single_barcode' => 'required',
                'single_base_price' => 'required',
                'single_stock_of' => 'required',
                // 'single_default_stock_type' => 'required',
                'single_stoc_wt' => 'required',
                // 'single_stock_gst' => 'required',
                // 'single_tax_id' => 'required',
                'single_unit_value' => 'required',
                // 'single_unit_data' => 'required',
                'single_description' => 'required',
                'single_short_description' => 'required',
                'single_base_image_file' => 'required|mimes:jpeg,jpg,bmp,png',
            ], [
                'single_barcode.required' => 'barcode field is required.',
                'single_base_price.required' => 'base price field is required.',
                'single_stock_of.required' => 'pack size field is required.',
                // 'single_default_stock_type.required' => 'default stock type field is required.',
                'single_stoc_wt.required' => 'stock weight field is required.',
                // 'single_stock_gst.required' => 'stock VAT field is required.',
                // 'single_tax_id.required' => 'tax field is required.',
                'single_unit_value.required' => 'unit field is required.',
                // 'single_unit_data.required' => 'UOM field is required.',
                'single_description.required' => 'description field is required.',
                'single_short_description.required' => 'short description field is required.',
                'single_base_image_file.required' => 'Image field is required.',
            ]);
        } elseif (isset($request['case_barcode']) && !empty($request['case_barcode'])) {

            $request->validate([
                'case_barcode' => 'required',
                'case_base_price' => 'required',
                'case_stock_of' => 'required',
                // 'case_default_stock_type' => 'required',
                'case_stoc_wt' => 'required',
                // 'case_stock_gst' => 'required',
                // 'case_tax_id' => 'required',
                'case_unit_value' => 'required',
                // 'case_unit_data' => 'required',
                'case_description' => 'required',
                'case_short_description' => 'required',
                'case_base_image_file' => 'required|mimes:jpeg,jpg,bmp,png',
            ], [
                'case_barcode.required' => 'barcode field is required.',
                'case_base_price.required' => 'base price field is required.',
                'case_stock_of.required' => 'pack size field is required.',
                // 'case_default_stock_type.required' => 'default stock type field is required.',
                'case_stoc_wt.required' => 'stock weight field is required.',
                // 'case_stock_gst.required' => 'stock VAT field is required.',
                // 'case_tax_id.required' => 'tax field is required.',
                'case_unit_value.required' => 'unit field is required.',
                // 'case_unit_data.required' => 'UOM field is required.',
                'case_description.required' => 'description field is required.',
                'case_short_description.required' => 'short description field is required.',
                'case_base_image_file.required' => 'Image field is required.',
            ]);
        } elseif (isset($request['pallets_barcode']) && !empty($request['pallets_barcode'])) {

            $request->validate([
                'pallets_barcode' => 'required',
                'pallets_base_price' => 'required',
                'pallets_stock_of' => 'required',
                // 'pallets_default_stock_type' => 'required',
                'pallets_stoc_wt' => 'required',
                // 'pallets_stock_gst' => 'required',
                // 'pallets_tax_id' => 'required',
                'pallets_unit_value' => 'required',
                // 'pallets_unit_data' => 'required',
                'pallets_description' => 'required',
                'pallets_short_description' => 'required',
                'pallets_base_image_file' => 'required|mimes:jpeg,jpg,bmp,png',
            ], [
                'pallets_barcode.required' => 'barcode field is required.',
                'pallets_base_price.required' => 'base price field is required.',
                'pallets_stock_of.required' => 'pack size field is required.',
                // 'pallets_default_stock_type.required' => 'default stock type field is required.',
                'pallets_stoc_wt.required' => 'stock weight field is required.',
                // 'pallets_stock_gst.required' => 'stock VAT field is required.',
                // 'pallets_tax_id.required' => 'tax field is required.',
                'pallets_unit_value.required' => 'unit field is required.',
                // 'pallets_unit_data.required' => 'UOM field is required.',
                'pallets_description.required' => 'description field is required.',
                'pallets_short_description.required' => 'short description field is required.',
                'pallets_base_image_file.required' => 'Image field is required.',
            ]);
        } else {
            return redirect(route("$route.edit"))->withErrors(['Plz add atleast one variant']);
        }

        if (isset($pallets) && !empty($pallets) && $pallets->status == 'INACTIVE') {

            $unitData = $request->get('pallets_unit_data');
            $unitDataArray = explode('|', $unitData);
            if (count($unitDataArray) > 1) {
                $request->merge([
                    'unit' => $unitDataArray[0],
                    'unit_name' => $unitDataArray[1]
                ]);

                $pallets_unit = $unitDataArray[0];
                $pallets_unit_name = $unitDataArray[1];
            }

            if ($request->hasFile('pallets_base_image_file') && $request->file('pallets_base_image_file')->isValid()) {
                $documentFile = $product->uploadMedia($request->file('pallets_base_image_file'));
                $document = $documentFile['path'] . $documentFile['name'];
                $request->merge(['base_image' => $document]);
                $pallets_productImage = $document;
            }

            $palletsProduct = $pallets->update([
                // 'barcode' => $request['pallets_barcode'],
                'base_price' => $request['pallets_base_price'],
                'stock_type' => $request['pallets_stock_type'],
                'stock_of' => $request['pallets_stock_of'],
                'default_stock_type' => $request['pallets_default_stock_type'],
                'stoc_wt' => $request['pallets_stoc_wt'],
                'stock_gst' => $request['pallets_stock_gst'],
                'tax_id' => $request['pallets_tax_id'],
                'unit_value' => $request['pallets_unit_value'],
                'unit' => $pallets_unit,
                'unit_name' => $pallets_unit_name,
                'description' => $request['pallets_description'],
                'short_description' => $request['pallets_short_description'],
                'search_keyword' => $request['pallets_search_keyword'],
                'meta_title' => $request['pallets_meta_title'],
                'meta_keyword' => $request['pallets_meta_keyword'],
                'meta_description' => $request['pallets_meta_description'],
                'base_image' => $pallets_productImage,
                // 'status' => 'INACTIVE',
                // 'parent_id' => $masterProduct['uuid'],
                // 'variant_id' => $masterProduct['variant_id'],
            ]);
        } elseif (isset($pallets) && !empty($pallets) && $pallets->status == 'ACTIVE') {
            //
        } else {

            if (isset($request['pallets_barcode']) && !empty($request['pallets_barcode'])) {

                $unitData = $request->get('pallets_unit_data');
                $unitDataArray = explode('|', $unitData);
                if (count($unitDataArray) > 1) {
                    $request->merge([
                        'unit' => $unitDataArray[0],
                        'unit_name' => $unitDataArray[1]
                    ]);

                    $pallets_unit = $unitDataArray[0];
                    $pallets_unit_name = $unitDataArray[1];
                }

                $palletsProduct = Product::create([
                    'name' => $request['name'],
                    'slug' => $request['slug'],
                    'brand_id' => $product['brand_id'],
                    'arrival_type' => $product['arrival_type'],
                    'base_image' => $product['base_image'],
                    'status' => $request['status'],
                    'barcode' => $request['pallets_barcode'],
                    'base_price' => $request['pallets_base_price'],
                    'stock_type' => $request['pallets_stock_type'],
                    'stock_of' => $request['pallets_stock_of'],
                    'default_stock_type' => $request['pallets_default_stock_type'],
                    'stoc_wt' => $request['pallets_stoc_wt'],
                    'stock_gst' => $request['pallets_stock_gst'],
                    'tax_id' => $request['pallets_tax_id'],
                    'unit_value' => $request['pallets_unit_value'],
                    'unit' => $pallets_unit,
                    'unit_name' => $pallets_unit_name,
                    'description' => $request['pallets_description'],
                    'short_description' => $request['pallets_short_description'],
                    'search_keyword' => $request['pallets_search_keyword'],
                    'meta_title' => $request['pallets_meta_title'],
                    'meta_keyword' => $request['pallets_meta_keyword'],
                    'meta_description' => $request['pallets_meta_description'],
                    'status' => 'INACTIVE',
                    'parent_id' => $product['uuid'],
                    'variant_id' => $product['variant_id'],
                ]);

                if ($request->has('categories')) {
                    $categories = $request->get('categories');
                    foreach ($categories as $category) {
                        $palletsProduct->productCategory()->create([
                            'category_id' => $category
                        ]);
                    }
                }
            }
        }

        if (isset($single) && !empty($single) && $single->status == 'INACTIVE') {

            $unitData = $request->get('single_unit_data');
            $unitDataArray = explode('|', $unitData);
            if (count($unitDataArray) > 1) {
                $request->merge([
                    'unit' => $unitDataArray[0],
                    'unit_name' => $unitDataArray[1]
                ]);

                $single_unit = $unitDataArray[0];
                $single_unit_name = $unitDataArray[1];
            }

            if ($request->hasFile('single_base_image_file') && $request->file('single_base_image_file')->isValid()) {
                $documentFile = $product->uploadMedia($request->file('single_base_image_file'));
                $document = $documentFile['path'] . $documentFile['name'];
                $request->merge(['base_image' => $document]);
                $single_productImage = $document;
            }

            $singleProduct = $single->update([
                // 'barcode' => $request['single_barcode'],
                'base_price' => $request['single_base_price'],
                'stock_type' => $request['single_stock_type'],
                'stock_of' => $request['single_stock_of'],
                'default_stock_type' => $request['single_default_stock_type'],
                'stoc_wt' => $request['single_stoc_wt'],
                'stock_gst' => $request['single_stock_gst'],
                'tax_id' => $request['single_tax_id'],
                'unit_value' => $request['single_unit_value'],
                'unit' => $single_unit,
                'unit_name' => $single_unit_name,
                'description' => $request['single_description'],
                'short_description' => $request['single_short_description'],
                'search_keyword' => $request['single_search_keyword'],
                'meta_title' => $request['single_meta_title'],
                'meta_keyword' => $request['single_meta_keyword'],
                'meta_description' => $request['single_meta_description'],
                'base_image' => $single_productImage,
                // 'status' => 'INACTIVE',
                // 'parent_id' => $masterProduct['uuid'],
                // 'variant_id' => $masterProduct['variant_id'],
            ]);
        } elseif (isset($single) && !empty($single) && $single->status == 'ACTIVE') {
            //
        } else {

            if (isset($request['single_barcode']) && !empty($request['single_barcode'])) {

                $unitData = $request->get('single_unit_data');
                $unitDataArray = explode('|', $unitData);
                if (count($unitDataArray) > 1) {
                    $request->merge([
                        'unit' => $unitDataArray[0],
                        'unit_name' => $unitDataArray[1]
                    ]);

                    $single_unit = $unitDataArray[0];
                    $single_unit_name = $unitDataArray[1];
                }

                $singleProduct = Product::create([
                    'name' => $request['name'],
                    'slug' => $request['slug'],
                    'brand_id' => $product['brand_id'],
                    'arrival_type' => $product['arrival_type'],
                    'base_image' => $product['base_image'],
                    'status' => $request['status'],
                    'barcode' => $request['single_barcode'],
                    'base_price' => $request['single_base_price'],
                    'stock_type' => $request['single_stock_type'],
                    'stock_of' => $request['single_stock_of'],
                    'default_stock_type' => $request['single_default_stock_type'],
                    'stoc_wt' => $request['single_stoc_wt'],
                    'stock_gst' => $request['single_stock_gst'],
                    'tax_id' => $request['single_tax_id'],
                    'unit_value' => $request['single_unit_value'],
                    'unit' => $single_unit,
                    'unit_name' => $single_unit_name,
                    'description' => $request['single_description'],
                    'short_description' => $request['single_short_description'],
                    'search_keyword' => $request['single_search_keyword'],
                    'meta_title' => $request['single_meta_title'],
                    'meta_keyword' => $request['single_meta_keyword'],
                    'meta_description' => $request['single_meta_description'],
                    'status' => 'INACTIVE',
                    'parent_id' => $product['uuid'],
                    'variant_id' => $product['variant_id'],
                ]);

                if ($request->has('categories')) {
                    $categories = $request->get('categories');
                    foreach ($categories as $category) {
                        $singleProduct->productCategory()->create([
                            'category_id' => $category
                        ]);
                    }
                }
            }
        }

        if (isset($case) && !empty($case) && $case->status == 'INACTIVE') {

            $unitData = $request->get('case_unit_data');
            $unitDataArray = explode('|', $unitData);
            if (count($unitDataArray) > 1) {
                $request->merge([
                    'unit' => $unitDataArray[0],
                    'unit_name' => $unitDataArray[1]
                ]);

                $case_unit = $unitDataArray[0];
                $case_unit_name = $unitDataArray[1];
            }

            if ($request->hasFile('case_base_image_file') && $request->file('case_base_image_file')->isValid()) {
                $documentFile = $product->uploadMedia($request->file('case_base_image_file'));
                $document = $documentFile['path'] . $documentFile['name'];
                $request->merge(['base_image' => $document]);
                $case_productImage = $document;
            }

            $caseProduct = $case->update([
                // 'barcode' => $request['case_barcode'],
                'base_price' => $request['case_base_price'],
                'stock_type' => $request['case_stock_type'],
                'stock_of' => $request['case_stock_of'],
                'default_stock_type' => $request['case_default_stock_type'],
                'stoc_wt' => $request['case_stoc_wt'],
                'stock_gst' => $request['case_stock_gst'],
                'tax_id' => $request['case_tax_id'],
                'unit_value' => $request['case_unit_value'],
                'unit' => $case_unit,
                'unit_name' => $case_unit_name,
                'description' => $request['case_description'],
                'short_description' => $request['case_short_description'],
                'search_keyword' => $request['case_search_keyword'],
                'meta_title' => $request['case_meta_title'],
                'meta_keyword' => $request['case_meta_keyword'],
                'meta_description' => $request['case_meta_description'],
                'base_image' => $case_productImage,
                // 'status' => 'INACTIVE',
                // 'parent_id' => $masterProduct['uuid'],
                // 'variant_id' => $masterProduct['variant_id'],
            ]);
        } elseif (isset($case) && !empty($case) && $case->status == 'ACTIVE') {
            //
        } else {

            if (isset($request['case_barcode']) && !empty($request['case_barcode'])) {

                $unitData = $request->get('case_unit_data');
                $unitDataArray = explode('|', $unitData);
                if (count($unitDataArray) > 1) {
                    $request->merge([
                        'unit' => $unitDataArray[0],
                        'unit_name' => $unitDataArray[1]
                    ]);

                    $case_unit = $unitDataArray[0];
                    $case_unit_name = $unitDataArray[1];
                }

                $caseProduct = Product::create([
                    'name' => $request['name'],
                    'slug' => $request['slug'],
                    'brand_id' => $product['brand_id'],
                    'arrival_type' => $product['arrival_type'],
                    'base_image' => $product['base_image'],
                    'status' => $request['status'],
                    'barcode' => $request['case_barcode'],
                    'base_price' => $request['case_base_price'],
                    'stock_type' => $request['case_stock_type'],
                    'stock_of' => $request['case_stock_of'],
                    'default_stock_type' => $request['case_default_stock_type'],
                    'stoc_wt' => $request['case_stoc_wt'],
                    'stock_gst' => $request['case_stock_gst'],
                    'tax_id' => $request['case_tax_id'],
                    'unit_value' => $request['case_unit_value'],
                    'unit' => $case_unit,
                    'unit_name' => $case_unit_name,
                    'description' => $request['case_description'],
                    'short_description' => $request['case_short_description'],
                    'search_keyword' => $request['case_search_keyword'],
                    'meta_title' => $request['case_meta_title'],
                    'meta_keyword' => $request['case_meta_keyword'],
                    'meta_description' => $request['case_meta_description'],
                    'status' => 'INACTIVE',
                    'parent_id' => $product['uuid'],
                    'variant_id' => $product['variant_id'],
                ]);

                if ($request->has('categories')) {
                    $categories = $request->get('categories');
                    foreach ($categories as $category) {
                        $caseProduct->productCategory()->create([
                            'category_id' => $category
                        ]);
                    }
                }
            }
        }

        if (isset($shrink) && !empty($shrink) && $shrink->status == 'INACTIVE') {

            $unitData = $request->get('shrink_unit_data');
            $unitDataArray = explode('|', $unitData);
            if (count($unitDataArray) > 1) {
                $request->merge([
                    'unit' => $unitDataArray[0],
                    'unit_name' => $unitDataArray[1]
                ]);

                $shrink_unit = $unitDataArray[0];
                $shrink_unit_name = $unitDataArray[1];
            }

            if ($request->hasFile('shrink_base_image_file') && $request->file('shrink_base_image_file')->isValid()) {
                $documentFile = $product->uploadMedia($request->file('shrink_base_image_file'));
                $document = $documentFile['path'] . $documentFile['name'];
                $request->merge(['base_image' => $document]);
                $shrink_productImage = $document;
            }

            $shrinkProduct = $shrink->update([
                // 'barcode' => $request['shrink_barcode'],
                'base_price' => $request['shrink_base_price'],
                'stock_type' => $request['shrink_stock_type'],
                'stock_of' => $request['shrink_stock_of'],
                'default_stock_type' => $request['shrink_default_stock_type'],
                'stoc_wt' => $request['shrink_stoc_wt'],
                'stock_gst' => $request['shrink_stock_gst'],
                'tax_id' => $request['shrink_tax_id'],
                'unit_value' => $request['shrink_unit_value'],
                'unit' => $shrink_unit,
                'unit_name' => $shrink_unit_name,
                'description' => $request['shrink_description'],
                'short_description' => $request['shrink_short_description'],
                'search_keyword' => $request['shrink_search_keyword'],
                'meta_title' => $request['shrink_meta_title'],
                'meta_keyword' => $request['shrink_meta_keyword'],
                'meta_description' => $request['shrink_meta_description'],
                'base_image' => $shrink_productImage,
                // 'status' => 'INACTIVE',
                // 'parent_id' => $masterProduct['uuid'],
                // 'variant_id' => $masterProduct['variant_id'],
            ]);
        } elseif (isset($shrink) && !empty($shrink) && $shrink->status == 'ACTIVE') {
            // dd('hii');
        } else {
            if (isset($request['shrink_barcode']) && !empty($request['shrink_barcode'])) {

                $unitData = $request->get('shrink_unit_data');
                $unitDataArray = explode('|', $unitData);
                if (count($unitDataArray) > 1) {
                    $request->merge([
                        'unit' => $unitDataArray[0],
                        'unit_name' => $unitDataArray[1]
                    ]);

                    $shrink_unit = $unitDataArray[0];
                    $shrink_unit_name = $unitDataArray[1];
                }

                $shrinkProduct = Product::create([
                    'name' => $request['name'],
                    'slug' => $request['slug'],
                    'brand_id' => $product['brand_id'],
                    'arrival_type' => $product['arrival_type'],
                    'base_image' => $product['base_image'],
                    'status' => $request['status'],
                    'barcode' => $request['shrink_barcode'],
                    'base_price' => $request['shrink_base_price'],
                    'stock_type' => $request['shrink_stock_type'],
                    'stock_of' => $request['shrink_stock_of'],
                    'default_stock_type' => $request['shrink_default_stock_type'],
                    'stoc_wt' => $request['shrink_stoc_wt'],
                    'stock_gst' => $request['shrink_stock_gst'],
                    'tax_id' => $request['shrink_tax_id'],
                    'unit_value' => $request['shrink_unit_value'],
                    'unit' => $shrink_unit,
                    'unit_name' => $shrink_unit_name,
                    'description' => $request['shrink_description'],
                    'short_description' => $request['shrink_short_description'],
                    'search_keyword' => $request['shrink_search_keyword'],
                    'meta_title' => $request['shrink_meta_title'],
                    'meta_keyword' => $request['shrink_meta_keyword'],
                    'meta_description' => $request['shrink_meta_description'],
                    'status' => 'INACTIVE',
                    'parent_id' => $product['uuid'],
                    'variant_id' => $product['variant_id'],
                ]);

                if ($request->has('categories')) {
                    $categories = $request->get('categories');
                    foreach ($categories as $category) {
                        $shrinkProduct->productCategory()->create([
                            'category_id' => $category
                        ]);
                    }
                }
            }
        }

        $route = $this->route;

        $redirectRoute = route("$route.index");

        if ($request->has('save_continue')) {
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

    public function getImport(User $userModel)
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

            $message = "We would like to inform you that your KYC is not completed. please complete your KYC";

            $email = EmailTemplate::where('name', '=', 'supplier_KYC_pending_notification')->first();

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
                    ->subject('Supplier - KYC Pending Notification')
                    ->setBody($emailContent, 'text/html'); // for HTML rich messages
            });

            return redirect(route($route_doc))->withErrors(['status' => 'warning', 'message' => trans($message)]);
        }

        $route = "supplier.product";
        $pageTitle = "IMPORTS PRODUCTS";
        return view("$route.import", compact('pageTitle', 'route'));
    }

    /**

     * @param Product submit $product

     * @return \Illuminate\Http\RedirectResponse

     */

    public function import_parse(FrontSupplierImportCsvRequest $request, Excel $excel)
    {
        ini_set('memory_limit', '-1');
        if ($request->hasFile('product_csv_file')) {
            //set heaging to default
            HeadingRowFormatter::default('none');
            $excel->import(new ProductImport, $request->file('product_csv_file'));
            return redirect(route("supplier.productimport"))->with(['status' => 'success', 'message' => "Stock Updated"]);
        }
    }

    public function import_product_parse(ImportCsvSupplierRequest $request, Excel $excel)
    {
        ini_set('memory_limit', '-1');
        if ($request->hasFile('real_product_csv_file')) {

            //set heaging to default
            HeadingRowFormatter::default('none');

            $import = new SupplierProductImport;
            $excel->import($import, $request->file('real_product_csv_file'));
            //(new SupplierProductImport)->import($request->file('file_import'), null, \Maatwebsite\Excel\Excel::XLSX);
            return redirect(route("supplier.productimport"))->with(['status' => 'success', 'message' => "Product uploaded"]);
        }
    }
}
