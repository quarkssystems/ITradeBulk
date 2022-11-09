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
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class AdminFieldsController extends Controller
{
    use DataGrid;

    public $dataUrl = '/admin/admin-fields';

    public $route = 'admin.admin-fields';

    public function index(Request $request, Product $productModel, Excel $excel, Brand $brandModel, User $userModel)
    {


        $perPage = 10;
        if ($request->has('per_page_count')) {
            $perPage = $request->per_page_count;
        }
        if ($request->has('products_name')) {
            $data = Product::withoutGlobalScopes()->where('name', 'like', '%' . $request->products_name . '%')->paginate($perPage);
        }
        if ($request->has('products_barcode')) {
            $data = Product::withoutGlobalScopes()->where('barcode', 'like', '%' . $request->products_barcode . '%')->paginate($perPage);
        }
        if ($request->has('products_description')) {
            $data = Product::withoutGlobalScopes()->where('description', 'like', '%' . $request->products_description . '%')->paginate($perPage);
        }
        if (!$request->has('products_name') && !$request->has('products_barcode') && !$request->has('products_description')) {

            $data = Product::withoutGlobalScopes()->paginate($perPage);
        }


        if ($request->has('export_data')) {
            $fileName = 'PRODUCT_DATA';
            $adminQuickView = AdminQuickView::where('user_id', Auth::user()->uuid)->first();

            return $excel->download(new DataGridExport('admin.product.export', [$data, $adminQuickView]), "$fileName.xlsx");
        }

        $route = $this->route;

        $pageTitle = "PRODUCT ADMIN FIELDS";


        if (Session::has('ProductPage')) {
            Session::forget('ProductPage');
        }
        Session::put('ProductPage', $request->input('page') ?? 1);

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
            'title' => 'Audited',
            'column' => 'name',
            'operator' => 'LIKE',
            'sorting' => true,

        ];

        $filters[] = [
            'title' => 'Published',
            'column' => 'name',
            'operator' => 'LIKE',
            'sorting' => true,

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
            // $data = $this->getGridData();
        } else {

            // $data = $this->getGridData();

            $dataGridTitle = $this->gridTitles();
            $dataGridSearch = $this->gridSearch();
            $dataGridPagination = $this->gridPagination($data);
        }

        // dd($data);
        if ($request->ajax()) {
            // return view('admin.admin_fields.grid', compact('data', 'route'));
            return view('admin.admin_fields.grid', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'route'));
        } else {
            // return view('admin.admin_fields.index', compact('data', 'pageTitle', 'route'));
            return view('admin.admin_fields.index', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'pageTitle', 'route'));
        }
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
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
        $pageTitle = "EDIT PRODUCT ADMIN FIELDS";
        $route = $this->route;
        $role = auth()->user()->role;
        return view('admin.admin_fields.form', compact('product', 'pageTitle', 'route'));
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
