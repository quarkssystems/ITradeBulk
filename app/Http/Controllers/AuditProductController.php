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
use DB;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Session;

class AuditProductController extends Controller
{
    use DataGrid;

    public $dataUrlNew = '/admin/supplier-view';
    public $dataUrl = '/admin/audit-product';

    public $route = 'admin.supplier-view';

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

        $perPage = 10;
        if ($request->has('per_page_count')) {
            $perPage = $request->per_page_count;
        }
        if ($request->has('users_first_name')) {
            $data = $userModel->where('role', 'SUPPLIER')->where('first_name', 'like', '%' . $request->users_first_name . '%')->paginate($perPage);
        } else {
            $data = $userModel->where('role', 'SUPPLIER')->paginate($perPage);
        }




        if (Session::has('ProductPage')) {
            Session::forget('ProductPage');
        }
        Session::put('ProductPage', $request->input('page') ?? 1);


        $route = $this->route;

        $pageTitle = "SUPPLIER VIEW";

        $data = tap($data, function ($query) {
            return $query->getCollection()->transform(function ($value) {
                $check = '';
                $cvalpublished = '';
                $cval = 1;
                $cvalpublished = 1;

                // $fileExist = file_exists( public_path() . $value->icon_file) ? 1 : 0;
                $onOff = '';
                $onOffpublished = '';
                if ($value->product_access == 1) {
                    $onOff = 'checked';
                    $cval = 0;
                }
                if ($value->fact_access == 1) {
                    $onOffpublished = 'checked';
                    $cvalpublished = 0;
                }

                $check =  '<label class="switchNew">
                        <input type="checkbox" ' . $onOff . ' class="onoff" data-id="' . $value->uuid . '" data-onoff="' . $value->product_access . '" data-conoff="' . $cval . '" >
                        <span class="slider round"></span>
                        </label>';

                $checkpublished =  '<label class="switchNewpublished">
                        <input type="checkbox" ' . $onOffpublished . ' class="onoffpublished" data-id="' . $value->uuid . '" data-onoff="' . $value->fact_access . '" data-conoff="' . $cvalpublished . '" >
                        <span class="slider round"></span>
                        </label>';

                $value->switch = $check;
                $value->switchpublished = $checkpublished;
                // Your code here
                return $value;
            });
        });

        $filters = [];
        $filters[] = ['title' => 'Action'];

        $filters[] = [
            'title' => 'Supplier Name',
            'column' => 'first_name',
            'operator' => 'LIKE',
            'sorting' => true,
            'search' => [
                'type' => 'text',
                'placeholder' => 'Search name'
            ]
        ];

        $filters[] = [
            'title' => 'Fact',
            'column' => 'fact',
        ];

        $filters[] = [
            'title' => 'Product',
            'column' => 'product',
        ];



        $tableName = $userModel->getTable();
        $url = $this->dataUrlNew;
        $this->setGridModel($userModel);
        $this->setGridRequest($request);
        $this->setFilters($filters);



        $this->setSorting(['sorting_field' => $tableName . '_sorting_field', 'sort' => $tableName . '_sort', 'default_field' => 'name', 'default_sort' => 'ASC']);

        $this->setGridUrl($url);

        $this->setGridVariables();

        if ($request->has('export_data')) {
            $this->setPaginationEnable(false);
            // $data = $this->getGridData();
        } else {

            // $data = $this->getGridData();

            $dataGridTitle = $this->gridTitles();
            $dataGridSearch = $this->gridSearch();
            $dataGridPagination = $this->gridPagination($data);
        }

        // dd($data);      
        if ($request->ajax()) {
            return view('admin.auditProduct.showUsersGrid', compact('data', 'route', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination'));
        } else {
            return view('admin.auditProduct.showUsers', compact('data', 'pageTitle', 'route', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination'));
        }
        // return view('admin.auditProduct.showUsers', compact('data', 'pageTitle', 'route', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination'));
    }


    public function getSupplierProduct(Request $request, Product $productModel, Excel $excel, Brand $brandModel, User $userModel)
    {
        // added
        $id = $request->id;
        $perPage = 10;
        // dd($request->all());
        if ($request->has('products_barcode')) {
            $data = Product::withoutGlobalScopes()->where('user_id', $id)->where('barcode', 'like', '%' . $request->products_barcode . '%')->paginate($perPage);
        }
        if ($request->has('products_name')) {
            $data = Product::withoutGlobalScopes()->where('user_id', $id)->where('name', 'like', '%' . $request->products_name . '%')->paginate($perPage);
        }
        if ($request->has('products_slug')) {
            $data = Product::withoutGlobalScopes()->where('user_id', $id)->where('slug', 'like', '%' . $request->products_slug . '%')->paginate($perPage);
        }
        if ($request->has('products_brand_id')) {
            $data = Product::withoutGlobalScopes()->where('user_id', $id)->where('brand_id', 'like', '%' . $request->products_brand_id . '%')->paginate($perPage);
        }
        // if ($request->has('products_user_id')) {
        //     $data = Product::withoutGlobalScopes()->where('user_id', $id)->where('user_id', 'like', '%' . $request->products_user_id . '%')->paginate($perPage);
        // }
        if (
            !$request->has('products_barcode')
            && !$request->has('products_name')
            && !$request->has('products_slug')
            && !$request->has('products_brand_id')
            && !$request->has('products_user_id')
        ) {
            $data = $productModel->where('user_id', $id)->withoutGlobalScopes()->OfNoParent()->paginate();
        }
        // dd('hi',$request->id);

        $route = $this->route;

        $pageTitle = "SUPPLIER PRODUCTS";

        if (Session::has('ProductPage')) {
            Session::forget('ProductPage');
        }
        Session::put('ProductPage', $request->input('page') ?? 1);

        // dd($data);

        $data = tap($data, function ($query) {
            return $query->getCollection()->transform(function ($value) {
                $check = '';
                $cvalpublished = '';
                $cval = 1;
                $cvalpublished = 1;

                // $fileExist = file_exists( public_path() . $value->icon_file) ? 1 : 0;
                $onOff = '';
                $onOffpublished = '';
                if ($value->audited == 1) {
                    $onOff = 'checked';
                    $cval = 0;
                }
                if ($value->published == 1) {
                    $onOffpublished = 'checked';
                    $cvalpublished = 0;
                }

                $check =  '<label class="switchNew">
                        <input type="checkbox" ' . $onOff . ' class="onoff" data-id="' . $value->uuid . '" data-onoff="' . $value->audited . '" data-conoff="' . $cval . '" >
                        <span class="slider round"></span>
                        </label>';

                $checkpublished =  '<label class="switchNewpublished">
                        <input type="checkbox" ' . $onOffpublished . ' class="onoffpublished" data-id="' . $value->uuid . '" data-onoff="' . $value->published . '" data-conoff="' . $cvalpublished . '" >
                        <span class="slider round"></span>
                        </label>';

                $value->switch = $check;
                $value->switchpublished = $checkpublished;
                // Your code here
                return $value;
            });
        });

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
                'placeholder' => 'Search barcode'
            ]
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
            'operator' => 'LIKE',
            'sorting' => true,
            'search' => [
                'type' => 'select',
                'placeholder' => 'Show all',
                'data' => $brandModel->getDropDown()
            ]
        ];

        $filters[] = [
            'title' => 'Audited',
            'column' => 'audited',
        ];

        $filters[] = [
            'title' => 'Published',
            'column' => 'published',
        ];



        $tableName = $productModel->getTable();
        $url = 'admin/product-list/' . $id;
        $this->setGridModel($productModel);
        $this->setGridRequest($request);
        $this->setFilters($filters);

        if (\Route::current()->getName() !== 'admin.products.index') {

            // $this->setScopes(["OfUserNotAdmin"]);

        }

        // $this->setScopes(["OfNoParent"]);

        $this->setSorting(['sorting_field' => $tableName . '_sorting_field', 'sort' => $tableName . '_sort', 'default_field' => 'name', 'default_sort' => 'ASC']);

        $this->setGridUrl($url);

        $this->setGridVariables();

        if ($request->has('export_data')) {
            $this->setPaginationEnable(false);
            // $data = $this->getGridData();
        } else {

            // $data = $this->getGridData();

            $dataGridTitle = $this->gridTitles();
            $dataGridSearch = $this->gridSearch();
            $dataGridPagination = $this->gridPagination($data);
        }

        if ($request->ajax()) {
            return view('admin.auditProduct.grid', compact('data', 'route', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination'));
        } else {
            return view('admin.auditProduct.index', compact('data', 'pageTitle', 'route', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination'));
        }
    }

    public function getSupplierFact(Request $request, Product $productModel, Excel $excel, Brand $brandModel, User $userModel)
    {
        // added

        $id = $request->id;

        $perPage = 10;
        if ($request->has('products_name')) {
            $data = Product::withoutGlobalScopes()->where('user_id', $id)->where('name', 'like', '%' . $request->products_name . '%')->paginate($perPage);
        }
        if ($request->has('products_barcode')) {
            $data = Product::withoutGlobalScopes()->where('user_id', $id)->where('barcode', 'like', '%' . $request->products_barcode . '%')->paginate($perPage);
        }
        if ($request->has('products_vat')) {
            $data = Product::withoutGlobalScopes()->where('user_id', $id)->where('vat', 'like', '%' . $request->products_vat . '%')->paginate($perPage);
        }
        if ($request->has('products_cost')) {
            $data = Product::withoutGlobalScopes()->where('user_id', $id)->where('cost', 'like', '%' . $request->products_cost . '%')->paginate($perPage);
        }
        if ($request->has('products_markup')) {
            $data = Product::withoutGlobalScopes()->where('user_id', $id)->where('markup', 'like', '%' . $request->products_markup . '%')->paginate($perPage);
        }
        if ($request->has('products_autoprice')) {
            $data = Product::withoutGlobalScopes()->where('user_id', $id)->where('autoprice', 'like', '%' . $request->products_autoprice . '%')->paginate($perPage);
        }
        if ($request->has('products_price')) {
            $data = Product::withoutGlobalScopes()->where('user_id', $id)->where('price', 'like', '%' . $request->products_price . '%')->paginate($perPage);
        }
        if ($request->has('products_quantity')) {
            $data = Product::withoutGlobalScopes()->where('user_id', $id)->where('quantity', 'like', '%' . $request->products_quantity . '%')->paginate($perPage);
        }
        if ($request->has('products_min_order_quantity')) {
            $data = Product::withoutGlobalScopes()->where('user_id', $id)->where('min_order_quantity', 'like', '%' . $request->products_min_order_quantity . '%')->paginate($perPage);
        }
        if ($request->has('products_stock_expiry_date')) {
            $data = Product::withoutGlobalScopes()->where('user_id', $id)->where('stock_expiry_date', 'like', '%' . $request->products_stock_expiry_date . '%')->paginate($perPage);
        }

        if (
            !$request->has('products_name') &&
            !$request->has('products_barcode') &&
            !$request->has('products_vat') &&
            !$request->has('products_cost') &&
            !$request->has('products_markup') &&
            !$request->has('products_autoprice') &&
            !$request->has('products_price') &&
            !$request->has('products_quantity') &&
            !$request->has('products_min_order_quantity') &&
            !$request->has('products_stock_expiry_date')
        ) {
            $data = $productModel->where('user_id', $id)->withoutGlobalScopes()->OfNoParent()->paginate();
        }

        $route = $this->route;

        $pageTitle = "SUPPLIER FACTS";

        if (Session::has('ProductPage')) {
            Session::forget('ProductPage');
        }
        Session::put('ProductPage', $request->input('page') ?? 1);

        // $data = $productModel->where('user_id', $id)->withoutGlobalScopes()->OfNoParent()->paginate();

        $data = tap($data, function ($query) {
            return $query->getCollection()->transform(function ($value) {
                $check = '';
                $cvalpublished = '';
                $cval = 1;
                $cvalpublished = 1;

                // $fileExist = file_exists( public_path() . $value->icon_file) ? 1 : 0;
                $onOff = '';
                $onOffpublished = '';
                if ($value->audited == 1) {
                    $onOff = 'checked';
                    $cval = 0;
                }
                if ($value->published == 1) {
                    $onOffpublished = 'checked';
                    $cvalpublished = 0;
                }

                $check =  '<label class="switchNew">
                        <input type="checkbox" ' . $onOff . ' class="onoff" data-id="' . $value->uuid . '" data-onoff="' . $value->audited . '" data-conoff="' . $cval . '" >
                        <span class="slider round"></span>
                        </label>';

                $checkpublished =  '<label class="switchNewpublished">
                        <input type="checkbox" ' . $onOffpublished . ' class="onoffpublished" data-id="' . $value->uuid . '" data-onoff="' . $value->published . '" data-conoff="' . $cvalpublished . '" >
                        <span class="slider round"></span>
                        </label>';

                $value->switch = $check;
                $value->switchpublished = $checkpublished;
                // Your code here
                return $value;
            });
        });

        $filters = [];
        $filters[] = ['title' => 'No'];

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
            'title' => 'Barcode',
            'column' => 'barcode',
            'operator' => 'LIKE',
            'sorting' => true,
            'search' => [
                'type' => 'text',
                'placeholder' => 'Search barcode'
            ]
        ];

        $filters[] = [
            'title' => 'Vat',
            'column' => 'vat',
            'operator' => 'LIKE',
            'sorting' => true,
            'search' => [
                'type' => 'text',
                'placeholder' => 'Search vat'
            ]
        ];

        $filters[] = [
            'title' => 'Cost',
            'column' => 'cost',
            'operator' => 'LIKE',
            'sorting' => true,
            'search' => [
                'type' => 'text',
                'placeholder' => 'Search cost'
            ]
        ];

        $filters[] = [
            'title' => 'Markup',
            'column' => 'markup',
            'operator' => 'LIKE',
            'sorting' => true,
            'search' => [
                'type' => 'text',
                'placeholder' => 'Search markup'
            ]
        ];

        $filters[] = [
            'title' => 'Autoprice',
            'column' => 'autoprice',
            'operator' => 'LIKE',
            'sorting' => true,
            'search' => [
                'type' => 'text',
                'placeholder' => 'Search autoprice'
            ]
        ];

        $filters[] = [
            'title' => 'Price',
            'column' => 'price',
            'operator' => 'LIKE',
            'sorting' => true,
            'search' => [
                'type' => 'text',
                'placeholder' => 'Search price'
            ]
        ];

        $filters[] = [
            'title' => 'Quantity',
            'column' => 'quantity',
            'operator' => 'LIKE',
            'sorting' => true,
            'search' => [
                'type' => 'text',
                'placeholder' => 'Search quantity'
            ]
        ];

        $filters[] = [
            'title' => 'Min Order Quantity',
            'column' => 'min_order_quantity',
            'operator' => 'LIKE',
            'sorting' => true,
            'search' => [
                'type' => 'text',
                'placeholder' => 'Search min order quantity'
            ]
        ];

        $filters[] = [
            'title' => 'Stock Expiry Date',
            'column' => 'stock_expiry_date',
            'operator' => 'LIKE',
            'sorting' => true,
            'search' => [
                'type' => 'date',
                'placeholder' => 'Search stock expiry date'
            ]
        ];



        $tableName = $productModel->getTable();
        $url = 'admin/fact-list/' . $id;
        $this->setGridModel($productModel);
        $this->setGridRequest($request);
        $this->setFilters($filters);

        if (\Route::current()->getName() !== 'admin.products.index') {

            // $this->setScopes(["OfUserNotAdmin"]);

        }

        // $this->setScopes(["OfNoParent"]);

        $this->setSorting(['sorting_field' => $tableName . '_sorting_field', 'sort' => $tableName . '_sort', 'default_field' => 'name', 'default_sort' => 'ASC']);

        $this->setGridUrl($url);

        $this->setGridVariables();

        if ($request->has('export_data')) {
            $this->setPaginationEnable(false);
            // $data = $this->getGridData();
        } else {

            // $data = $this->getGridData();

            $dataGridTitle = $this->gridTitles();
            $dataGridSearch = $this->gridSearch();
            $dataGridPagination = $this->gridPagination($data);
        }


        if ($request->ajax()) {
            return view('admin.auditProduct.index_fact_grid', compact('data', 'route', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination'));
        } else {
            return view('admin.auditProduct.index_fact', compact('data', 'pageTitle', 'route', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination'));
        }
    }

    public function auditOn($id)
    {
        $data = Product::withoutGlobalScopes()->where('uuid', $id)->select('audited')->first();
        if ($data->audited == 1) {
            Product::withoutGlobalScopes()->where('uuid', $id)->update(['audited' => '0']);
        } else {
            Product::withoutGlobalScopes()->where('uuid', $id)->update(['audited' => '1']);
        }
        return $data;
    }

    public function published($id)
    {
        $data = Product::withoutGlobalScopes()->where('uuid', $id)->select('published')->first();
        if ($data->published == 1) {
            Product::withoutGlobalScopes()->where('uuid', $id)->update(['published' => '0']);
        } else {
            Product::withoutGlobalScopes()->where('uuid', $id)->update(['published' => '1']);
        }
        return $data;
    }


    public function userProduct($id)
    {
        $data = User::where('uuid', $id)->select('product_access')->first();
        if ($data->product_access == '1') {
            User::where('uuid', $id)->update(['product_access' => '0']);
        } else {
            User::where('uuid', $id)->update(['product_access' => '1']);
        }
        return $data;
    }

    public function userFact($id)
    {
        $data = User::where('uuid', $id)->select('fact_access')->first();
        if ($data->fact_access == '1') {
            User::where('uuid', $id)->update(['fact_access' => '0']);
        } else {
            User::where('uuid', $id)->update(['fact_access' => '1']);
        }
        return $data;
    }

    public function productData($id)
    {
        $data = Product::where('uuid', $id)->select('barcode', 'store_id', 'price as base_price', 'stock_expiry_date')->first();
        return $data;
    }
}
