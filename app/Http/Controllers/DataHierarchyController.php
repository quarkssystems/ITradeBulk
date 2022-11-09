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
use App\Imports\AdminHierarchyImport;
use DB;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Session;
use Auth;

class DataHierarchyController extends Controller
{
    use DataGrid;

    public $dataUrl = '/admin/data-hierarchy';

    public $route = 'admin.data-hierarchy';

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
                'placeholder' => 'Search name'
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
            'title' => 'Category Group',
            'column' => 'category_group',
            'operator' => 'LIKE',
            'sorting' => true,
            'search' => [
                'type' => 'text',
                'placeholder' => 'Search category group'
            ]
        ];

        $filters[] = [
            'title' => 'Department',
            'column' => 'department',
            'operator' => 'LIKE',
            'sorting' => true,
            'search' => [
                'type' => 'text',
                'placeholder' => 'Search department'
            ]
        ];

        $filters[] = [
            'title' => 'Category',
            'column' => 'category',
            'operator' => 'LIKE',
            'sorting' => true,
            'search' => [
                'type' => 'text',
                'placeholder' => 'Search category'
            ]
        ];

        $filters[] = [
            'title' => 'Sub Category',
            'column' => 'sub_category',
            'operator' => 'LIKE',
            'sorting' => true,
            'search' => [
                'type' => 'text',
                'placeholder' => 'Search sub category'
            ]
        ];

        $filters[] = [
            'title' => 'Segment',
            'column' => 'segment',
            'operator' => 'LIKE',
            'sorting' => true,
            'search' => [
                'type' => 'text',
                'placeholder' => 'Search segment'
            ]
        ];

        $filters[] = [
            'title' => 'Sub Segment',
            'column' => 'sub_segment',
            'operator' => 'LIKE',
            'sorting' => true,
            'search' => [
                'type' => 'text',
                'placeholder' => 'Search sub segment'
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
            $fileName = 'DATA HIERARCHY';
            $adminQuickView = AdminQuickView::where('user_id', Auth::user()->uuid)->first();

            return $excel->download(new DataGridExport('admin.data_hierarchy.export', [$data, $adminQuickView]), "$fileName.xlsx");
        }

        $route = $this->route;

        $pageTitle = "DATA HIERARCHY";


        if (Session::has('ProductPage')) {
            Session::forget('ProductPage');
        }
        Session::put('ProductPage', $request->input('page') ?? 1);



        // $selectedCategories = $product->productCategory()->pluck('category_id')->toArray();
        $data = tap($data, function ($query) {
            return $query->getCollection()->transform(function ($value) {

                $categoryId = $value->productCategory()->pluck('category_id');
                $category = Category::whereIn('uuid', $categoryId)->get()->pluck('name', 'parent_category_id',);

                $categoryData = '';
                $subCategoryData = '';
                foreach ($category as $key => $data) {
                    if ($key == "") {
                        $categoryData = $data;
                    } else {
                        $subCategoryData = $data;
                    }
                }
                $value->category = $categoryData;
                $value->subCategory = $subCategoryData;
                return $value;
            });
        });

        if ($request->ajax()) {
            return view('admin.data_hierarchy.grid', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'route'));
        } else {
            return view('admin.data_hierarchy.index', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'pageTitle', 'route'));
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

        $product = Product::withoutGlobalScopes()->where('uuid', $id)->first();
        $pageTitle = "EDIT DATA HIERARCHY";
        $route = $this->route;
        $role = auth()->user()->role;

        // dd($product);
        $product->category_group = $product->department . '_' . $product->category . '_' . $product->subcategory . '_' . $product->segment . '_' . $product->subsegment;

        return view('admin.data_hierarchy.form', compact('product', 'pageTitle', 'route', 'categories', 'selectedCategories'));
        // return view('admin.data_hierarchy.form', compact('product', 'pageTitle', 'route', 'role', 'statuses', 'copy', 'brands', 'taxes', 'categories', 'selectedCategories', 'productUnits','arrival','productAttribute','productgst','defaultStockType'));

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

    public function getHierarchyImport()
    {
        $pageTitle = 'DATA HIERARCHY IMPORT';
        return view('admin.data_hierarchy.import', compact('pageTitle'));
    }

    public function import_data_hierarchy(Request $request, Excel $excel)
    {
        if ($request->hasFile('file_import')) {

            //set heaging to default
            HeadingRowFormatter::default('none');

            $import = new AdminHierarchyImport;
            $excel->import($import, $request->file('file_import'));

            $errbarcode = AdminHierarchyImport::getError();
            $errbarcode = implode(", ", $errbarcode);

            $message = $import->getProductAddCount() . " Hierarchy Add successfully And  " . $import->getProductUpdateCount() . " Hierarchy Update successfully.";

            $errmsg = $errbarcode;
            // $errmsg = "There is some problem in variant id with the following Barcode : " . $errbarcode . ". may be this product already exist (please check sheet) . may be something wrong with variant id.";

            if (isset($errbarcode) && !empty($errbarcode) && $import->getProductAddCount() > 0 || $import->getProductUpdateCount() > 0) {

                return redirect(route("admin.hierarchyimport"))
                    ->with(['status' => 'success', 'message' => $message]);
                // ->with(['errstatus' => 'danger', 'errmessage' => $errmsg]);
            } elseif ($import->getProductAddCount() == 0 && $import->getProductUpdateCount() == 0) {

                return redirect(route("admin.hierarchyimport"))
                    ->with(['errstatus' => 'danger', 'errmessage' => $errmsg]);
            } else {

                return redirect(route("admin.hierarchyimport"))
                    ->with(['status' => 'success', 'message' => $message]);
            }
        }
    }
}
