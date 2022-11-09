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
use App\Models\OfferDeals;
use App\Models\Promotion;
use App\PromoType;
use DB;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Session;
use Auth;

class PromotionsController extends Controller
{
    use DataGrid;

    public $dataUrl = '/admin/promotions';

    public $route = 'admin.promotions';

    public function index(Request $request, Product $productModel, Excel $excel, Brand $brandModel, User $userModel, PromoType $promoType)
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
            'title' => 'Promotion Type',
            'column' => 'promotion_type',
            'operator' => 'LIKE',
            'sorting' => true,
            'search' => [
                'type' => 'select',
                'placeholder' => 'Show all',
                'data' => $promoType->getDropDown()
            ]
        ];

        $filters[] = [
            'title' => 'Period From',
            'column' => 'period_from',
            'operator' => 'LIKE',
            'sorting' => true,
            'search' => [
                'type' => 'date',
                'placeholder' => 'Search name'
            ]
        ];

        $filters[] = [
            'title' => 'Period To',
            'column' => 'period_to',
            'operator' => 'LIKE',
            'sorting' => true,
            'search' => [
                'type' => 'date',
                'placeholder' => 'Search name'
            ]
        ];

        $filters[] = [
            'title' => 'Promotion Price',
            'column' => 'promotion_price',
            'operator' => 'LIKE',
            'sorting' => true,
            'search' => [
                'type' => 'text',
                'placeholder' => 'Search promotion price'
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

        $pageTitle = "PRODUCT PROMOTIONS";


        if (Session::has('ProductPage')) {
            Session::forget('ProductPage');
        }
        Session::put('ProductPage', $request->input('page') ?? 1);



        if ($request->ajax()) {
            return view('admin.promotions.grid', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'route'));
        } else {
            return view('admin.promotions.index', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'pageTitle', 'route'));
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
        $pageTitle = "EDIT PRODUCT PROMOTIONS";
        $route = $this->route;
        $role = auth()->user()->role;
        $promoType = PromoType::where('status', '1')->get()->pluck('type', 'type');
        // $promoTypeData = PromoType::where('product_id', '1')->get()->pluck('type', 'type');

        return view('admin.promotions.form', compact('product', 'pageTitle', 'route', 'promoType'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::withoutGlobalScopes()->where('uuid', $id)->first();

        // dd($data);
        $data = $request->except(['uuid', '_method', '_token', 'hidden']);
        $data['user_id'] = $product->user_id;
        $data['product_id'] = $id;
        $data['store_id'] = $product->store_id;

        // $data['base_price'] = $request->price;
        // $data['price'] = $request->price;

        // dd($data);
        Promotion::updateOrCreate(['product_id' => $id], $data);
        // $promotion = Promotion::where('product_id',$id)->first();
        // if($promotion != null){

        // }
        // $data = OfferDeals::where('products_id', $id)->first();
        // if ($data != null) {
        //     $data->update([
        //         // 'user_id' => auth()->user()->uuid,
        //         // 'title' => '',
        //         'start_date' => date('Y-m-d', strtotime($request->period_from)),
        //         'end_date' => date('Y-m-d', strtotime($request->period_to)),
        //         'brands_id' => $product->brandid,
        //         'categories_id' => $product->category,
        //         'products_id' => $id,
        //         'offer_method' => '',
        //         'offer_type' => $request->promotion_type,
        //         'offer_value' => $request->promotion_price,
        //         'description' => '',
        //         'image' => '',
        //         'offercode' => '',
        //         'status' => 'ACTIVE'
        //     ]);
        // } else {
        //     OfferDeals::create([
        //         'user_id' => auth()->user()->uuid,
        //         'title' => '',
        //         'start_date' => date('Y-m-d', strtotime($request->period_from)),
        //         'end_date' => date('Y-m-d', strtotime($request->period_to)),
        //         'brands_id' => $product->brandid,
        //         'categories_id' => $product->category,
        //         'products_id' => $id,
        //         'offer_method' => '',
        //         'offer_type' => $request->promotion_type,
        //         'offer_value' => $request->promotion_price,
        //         'description' => '',
        //         'image' => '',
        //         'offercode' => '',
        //         'status' => 'ACTIVE'
        //     ]);
        // }

        $productNew = $request->all();
        // $productNew['base_price'] = $request->price;
        // $productNew['price'] = $request->price;

        $productUpdate = $product->update($productNew);
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
