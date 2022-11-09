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


class ProductDescriptionController extends Controller
{
    use DataGrid;

    public $dataUrl = '/admin/product_description';

    public $route = 'admin.product_description';

    public function index(Request $request, Product $productModel, Excel $excel, Brand $brandModel, User $userModel)
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
            'title' => 'Description',
            'column' => 'description',
            'operator' => 'LIKE',
            'sorting' => true,
            'search' => [
                'type' => 'text',
                'placeholder' => 'Search description'
            ]
        ];


        // $filters[] = [
        //     'title' => 'Description',
        //     'column' => 'description',
        //     'operator' => 'LIKE',
        //     'sorting' => true,
        //     // 'search' => [
        //     //     'type' => 'text',
        //     //     'placeholder' => 'Search name'
        //     // ]
        // ];

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
            'title' => 'Manufacturer',
            'column' => 'manufacturer',
            'operator' => 'LIKE',
            'sorting' => true,
            'search' => [
                'type' => 'text',
                'placeholder' => 'Search manufacturer'
            ]
        ];

        $tableName = $productModel->getTable();
        $url = $this->dataUrl;
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
            $data = $this->getGridData();
        } else {

            $data = $this->getGridData();

            $dataGridTitle = $this->gridTitles();
            $dataGridSearch = $this->gridSearch();
            $dataGridPagination = $this->gridPagination($data);
        }

        if ($request->has('export_data')) {
            $fileName = 'PRODUCT_DATA';
            $adminQuickView = AdminQuickView::where('user_id', Auth::user()->uuid)->first();

            return $excel->download(new DataGridExport('admin.product.export', [$data, $adminQuickView]), "$fileName.xlsx");
        }

        $route = $this->route;

        $pageTitle = "PRODUCT DESCRIPTION";


        if (Session::has('ProductPage')) {
            Session::forget('ProductPage');
        }
        Session::put('ProductPage', $request->input('page') ?? 1);



        if ($request->ajax()) {
            return view('admin.product_description.grid', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'route'));
        } else {
            return view('admin.product_description.index', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'pageTitle', 'route'));
        }
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
    }

    public function show()
    {
    }


    public function edit($id, Brand $brandModel, Tax $taxModel, Category $categoryModel, ProductUnit $productUnitModel)
    {

        // $product = Product::withoutGlobalScopes()->where('uuid',$id)->first();
        // $categories = $categoryModel->getParentCategories();
        $brands = $brandModel->getDropDown();
        // $taxes = $taxModel->getDropDown();
        // $statuses = $product->getStatusesDropDown();
        // $productUnits = $productUnitModel->getDropDown();
        // $productAttribute=$product->getStockProductAttribute();
        // $productgst = $product->getStockGST();
        // $defaultStockType = $product->getDefaultStockType();
        // $selectedCategories = $product->productCategory()->pluck('category_id')->toArray();
        // $arrival = ArrivalType::all();
        // $copy = request()->has('copy') ? true : false;
        // $pageTitle = $copy ? "COPY PRODUCT" : "EDIT PRODUCT";
        // $route = $this->route;
        // $role = auth()->user()->role;

        $product = Product::withoutGlobalScopes()->where('uuid', $id)->first();
        $pageTitle = "EDIT PRODUCT DESCRIPTION";
        $route = $this->route;
        $role = auth()->user()->role;
        return view('admin.product_description.form', compact('product', 'pageTitle', 'route', 'brands'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::withoutGlobalScopes()->where('uuid', $id)->first();

        $productUpdate = $product->update($request->all());

        $route = $this->route;

        $redirectRoute = route("$route.index");
        if ($request->has('save_continue')) {
            $redirectRoute = route("$route.edit", $product->uuid);
        } elseif (Session::has('ProductPage')) {
            $page = session()->get("ProductPage");
            return redirect()->route("$route.index", ['page' => $page])->with(['status' => 'success', 'message' => trans('success.admin|product|updated')]);
        }

        return redirect($redirectRoute)->with(['status' => 'success', 'message' => trans('success.admin|product|updated')]);
    }


    public function destroy()
    {
    }
}
