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

class ProductFactController extends Controller
{
    use DataGrid;

    public $dataUrl = '/admin/product-fact';

    public $route = 'admin.product-fact';

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

        $pageTitle = "PRODUCT FACT";


        if (Session::has('ProductPage')) {
            Session::forget('ProductPage');
        }
        Session::put('ProductPage', $request->input('page') ?? 1);



        if ($request->ajax()) {
            return view('admin.product_fact.grid', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'route'));
        } else {
            return view('admin.product_fact.index', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'pageTitle', 'route'));
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
        $product = Product::withoutGlobalScopes()->where('uuid', $id)->first();
        $pageTitle = "EDIT PRODUCT FACT";
        $route = $this->route;
        $role = auth()->user()->role;
        return view('admin.product_fact.form', compact('product', 'pageTitle', 'route'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::withoutGlobalScopes()->where('uuid', $id)->first();

        $data = $request->all();
        $stockGST = $data['vat'];
        $stockGST = (strtolower($stockGST) == 'n') ? 0 : 1;
        $data['stock_type'] = $stockGST;
        $data['base_price'] = $request->price;


        $productUpdate = $product->update($data);

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

    public function editFact($id, $user_id)
    {
        $product = Product::withoutGlobalScopes()->where('uuid', $id)->first();
        $pageTitle = "EDIT FACT";
        $route = $this->route;
        $role = auth()->user()->role;
        return view('admin.product_fact.fact_form', compact('product', 'pageTitle', 'route', 'user_id'));
    }

    public function updateFact(Request $request, $id)
    {
        $product = Product::withoutGlobalScopes()->where('uuid', $id)->first();

        $data = $request->all();
        $stockGST = $data['vat'];
        $stockGST = (strtolower($stockGST) == 'n') ? 0 : 1;
        $data['stock_type'] = $stockGST;


        $productUpdate = $product->update($data);

        $route = $this->route;
        $user_id = $request->user_id;
        return redirect(route('admin.fact-list', [$user_id]))->with(['status' => 'success', 'message' => trans('success.admin|product|updated')]);
    }

    public function editProduct($id, $user_id)
    {
        $product = Product::withoutGlobalScopes()->where('uuid', $id)->first();
        $brandModel = new Brand;
        $brands = $brandModel->getDropDown();
        $pageTitle = "EDIT PRODUCT";
        $route = $this->route;
        $role = auth()->user()->role;
        return view('admin.product_fact.product_form', compact('product', 'pageTitle', 'route', 'user_id', 'brands'));
    }

    public function updateProduct(Request $request, $id)
    {
        $product = Product::withoutGlobalScopes()->where('uuid', $id)->first();

        $data = $request->all();

        if ($request->hasFile('base_image') && $request->file('base_image')->isValid()) {
            $documentFile = $product->uploadMedia($request->file('base_image'));
            $document = $documentFile['path'] . $documentFile['name'];
            $data['base_image'] = $document;
        }

        $productUpdate = $product->update($data);

        $route = $this->route;
        $user_id = $request->user_id;
        return redirect(route('admin.product-list', [$user_id]))->with(['status' => 'success', 'message' => trans('success.admin|product|updated')]);
    }
}
