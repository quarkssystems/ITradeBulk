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


class PackingDimensionsController extends Controller
{
    use DataGrid;

    public $dataUrl = '/admin/packing-dimension';

    public $route = 'admin.packing-dimension';

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
            'title' => 'Packing',
            'column' => 'packing',
            'operator' => 'LIKE',
            'sorting' => true,
            'search' => [
                'type' => 'text',
                'placeholder' => 'Search packing'
            ]
        ];

        $filters[] = [
            'title' => 'Units Per Packing',
            'column' => 'units_per_packing',
            'operator' => 'LIKE',
            'sorting' => true,
            'search' => [
                'type' => 'text',
                'placeholder' => 'Search units per packing'
            ]
        ];

        $filters[] = [
            'title' => 'Size',
            'column' => 'size',
            'operator' => 'LIKE',
            'sorting' => true,
            'search' => [
                'type' => 'text',
                'placeholder' => 'Search size'
            ]
        ];

        $filters[] = [
            'title' => 'Unit Of Measure',
            'column' => 'unit_of_measure',
            'operator' => 'LIKE',
            'sorting' => true,
            'search' => [
                'type' => 'text',
                'placeholder' => 'Search unit of measure'
            ]
        ];

        $filters[] = [
            'title' => 'Size Description',
            'column' => 'size_description',
            'operator' => 'LIKE',
            'sorting' => true,
            'search' => [
                'type' => 'text',
                'placeholder' => 'Search size description'
            ]
        ];

        $filters[] = [
            'title' => 'Height',
            'column' => 'height',
            'operator' => 'LIKE',
            'sorting' => true,
            'search' => [
                'type' => 'text',
                'placeholder' => 'Search height'
            ]
        ];

        $filters[] = [
            'title' => 'Width',
            'column' => 'width',
            'operator' => 'LIKE',
            'sorting' => true,
            'search' => [
                'type' => 'text',
                'placeholder' => 'Search width'
            ]
        ];

        $filters[] = [
            'title' => 'Depth',
            'column' => 'depth',
            'operator' => 'LIKE',
            'sorting' => true,
            'search' => [
                'type' => 'text',
                'placeholder' => 'Search depth'
            ]
        ];

        $filters[] = [
            'title' => 'Weight',
            'column' => 'weight',
            'operator' => 'LIKE',
            'sorting' => true,
            'search' => [
                'type' => 'text',
                'placeholder' => 'Search weight'
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

        $pageTitle = "PRODUCT PACKING AND DIMENSION";


        if (Session::has('ProductPage')) {
            Session::forget('ProductPage');
        }
        Session::put('ProductPage', $request->input('page') ?? 1);



        if ($request->ajax()) {
            return view('admin.packing_dimension.grid', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'route'));
        } else {
            return view('admin.packing_dimension.index', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'pageTitle', 'route'));
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
        $pageTitle = "EDIT PRODUCT PACKING AND DIMENSION";
        $route = $this->route;
        $role = auth()->user()->role;
        $productUnit = $productUnitModel->get();
        // dd($productUnit);
        return view('admin.packing_dimension.form', compact('product', 'pageTitle', 'route', 'productUnit'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::withoutGlobalScopes()->where('uuid', $id)->first();

        $data = $request->all();
        $data['stock_of'] = $data['units_per_packing'];

        $stockType = $data['packing'];
        if (isset($stockType) && !empty($stockType)) {
            $stockType = strtolower($stockType);
        }
        $data['stock_type'] = $stockType;

        $unitMsr = trim($data['unit_of_measure']);
        $unitMeasure = array('kg', 'gr', 'ea', 's', 'mm', 'mt');
        $unitIn = (in_array(strtolower($unitMsr), $unitMeasure)) ? 'Weight' : 'Unit';

        $data['unit'] = $unitIn;
        $data['unit_name'] = strtoupper($unitMsr);
        $data['stoc_wt'] = $data['weight'];
        $data['unit_value'] = $data['size'];


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
}
