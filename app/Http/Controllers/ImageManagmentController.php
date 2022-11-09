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

class ImageManagmentController extends Controller
{

    use DataGrid;

    public $dataUrl = '/admin/image-management';

    public $route = 'admin.image-management';

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
            'title' => 'Base Image',
            'column' => 'base_image',

        ];

        $filters[] = [
            'title' => 'Alternate Image 1',
            'column' => 'alternate_image_1',

        ];

        $filters[] = [
            'title' => 'Alternate Image 2',
            'column' => 'alternate_image_2',

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

        $pageTitle = "PRODUCT IMAGE";


        if (Session::has('ProductPage')) {
            Session::forget('ProductPage');
        }
        Session::put('ProductPage', $request->input('page') ?? 1);


        if ($request->ajax()) {
            return view('admin.imageManagement.grid', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'route'));
        } else {
            return view('admin.imageManagement.index', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'pageTitle', 'route'));
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
        $pageTitle = "EDIT PRODUCT IMAGE";
        $route = $this->route;
        $role = auth()->user()->role;
        $basePhoto = explode('/', $product->base_image);
        $total = count($basePhoto);
        $product->base_image_new = $basePhoto[$total - 1];
        return view('admin.imageManagement.form', compact('product', 'pageTitle', 'route'));
    }

    public function update(Request $request, $id)
    {

        $product = Product::withoutGlobalScopes()->where('uuid', $id)->first();
        $data = $request->all();
        if ($request->hasFile('base_image') && $request->file('base_image')->isValid()) {
            $documentFile = $product->uploadMedia($request->file('base_image'));
            $document = $documentFile['path'] . $documentFile['name'];
            $data['base_image'] = $document;
        }

        if ($request->hasFile('alternate_image_1') && $request->file('alternate_image_1')->isValid()) {
            $documentFile = $product->uploadMedia($request->file('alternate_image_1'));
            $document = $documentFile['path'] . $documentFile['name'];
            $data['alternate_image_1'] = $document;
        }

        if ($request->hasFile('alternate_image_2') && $request->file('alternate_image_2')->isValid()) {
            $documentFile = $product->uploadMedia($request->file('alternate_image_2'));
            $document = $documentFile['path'] . $documentFile['name'];
            $data['alternate_image_2'] = $document;
        }

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
