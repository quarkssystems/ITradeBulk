<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\DataGrid;
use App\Http\Requests\AdminSupplierOfferRequest;
use Illuminate\Http\Request;
use App\Models\OfferDeals;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Promotion;
use App\PromoType;
use Carbon\Carbon;
use App\User;

use Illuminate\View\View;

use Session;

class AdminSupplierOffersController extends Controller
{
    use DataGrid;

    public $route = 'admin.offerdeals';
    public $dataUrl = '/admin/offerdeals';


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Promotion $offerdeal)
    // public function index(Request $request, OfferDeals $offerdeal)
    {


        $filters = [];
        $filters[] = ['title' => 'No'];

        // $filters[] = [
        //     'title' => 'Promotion',
        //     'column' => 'title',
        //     'operator' => 'LIKE',
        //     'sorting' => true,
        //     'search' => [
        //         'type' => 'text',
        //         'placeholder' => 'Search Offer'
        //     ]
        // ];

        $filters[] = [
            'title' => 'Product',
            'column' => 'product_name',
            'operator' => 'LIKE',
            'sorting' => true,
        ];

        $filters[] = [
            'title' => 'Supplier',
            'column' => 'user_id',
            'operator' => 'LIKE',
            'sorting' => true,
        ];

        $filters[] = [
            'title' => 'Start Date',
            'column' => 'period_from',
            'operator' => '=',
            'sorting' => true,

        ];

        $filters[] = [
            'title' => 'End Date',
            'column' => 'period_to',
            'operator' => '=',
            'sorting' => true
        ];

        // $filters[] = [
        //     'title' => 'Promotion Type',
        //     'column' => 'offer_type',
        //     'operator' => '=',
        //     'sorting' => true,
        //     'search' => [
        //         'type' => 'select',
        //         'placeholder' => 'Show all',
        //         'data' => $offerdeal->getOfferTypesDropDown()
        //     ]
        // ];

        $filters[] = [
            'title' => 'Promotion',
            'column' => 'promotion_price',
            'operator' => '=',
            'sorting' => true
        ];



        /* $filters[] = [
            'title' => 'Status',
            'column' => 'status',
            'operator' => '=',
            'sorting' => true,
            'search' => [
                'type' => 'select',
                'placeholder' => 'Show all',
                //'data' => $offerdeal->getStatusDropdown()
            ]
        ];*/

        $filters[] = [
            'title' => 'Date',
            'column' => 'created_at',
            'operator' => '=',
            'sorting' => true
        ];

        $filters[] = [
            'title' => 'Action'
        ];

        $tableName = $offerdeal->getTable();
        $url = route($this->route . ".index");
        $this->setGridModel($offerdeal);
        $this->setGridRequest($request);
        $this->setFilters($filters);


        $this->setSorting(['sorting_field' => $tableName . '_sorting_field', 'sort' => $tableName . '_sort', 'default_field' => 'created_at', 'default_sort' => 'DESC']);

        $this->setGridUrl($url);

        $this->setGridVariables();


        $data = $this->getGridData();
        $dataGridTitle = $this->gridTitles();
        $dataGridSearch = $this->gridSearch();
        $dataGridPagination = $this->gridPagination($data);


        $route = $this->route;

        $pageTitle = "Manage PROMOTIONS";

        if (Session::has('SupplierOfferPage')) {
            Session::forget('SupplierOfferPage');
        }
        Session::put('SupplierOfferPage', $request->input('page') ?? 1);

        $data = tap($data, function ($query) {
            return $query->getCollection()->transform(function ($value) {
                $user = User::where('uuid', $value->user_id)->first();
                $product = Product::where('uuid', $value->product_id)->select('name')->first();
                if ($user != null) {
                    $supplier = $user->first_name . ' ' . $user->last_name;
                    $value->suppiler_name = $supplier;
                }
                if ($product != null) {
                    $product_name = $product->name;
                    $value->product_name = $product_name;
                }
                // Your code here
                return $value;
            });
        });

        if ($request->ajax()) {
            return view('admin.offerDeals.grid', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'route'));
        } else {
            return view('admin.offerDeals.index', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'pageTitle', 'route'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Promotion $offerdeal, Brand $brandModel, Category $catModel, User $user, Product $product)
    // public function create(OfferDeals $offerdeal, Brand $brandModel, Category $catModel, User $user)
    {
        $route = $this->route;
        $role = auth()->user()->role;
        $suppliers = $user->getDropDownSuppiler();
        $products = $product->getProductData();
        $brands = $brandModel->getDropDown();
        $categories = $catModel->getDropDown();
        // $statuses = $offerdeal->getStatusesDropDown();
        // $offer_type = $offerdeal->getOfferTypesDropDown();
        $promoType = PromoType::where('status', '1')->get()->pluck('type', 'type');
        $pageTitle = "ADD PROMOTIONS";

        return view('admin.offerDeals.form', compact('offerdeal', 'pageTitle', 'route', 'role', 'suppliers', 'brands', 'categories', 'products', 'promoType'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Promotion $offerdeal, Request $request)
    // public function store(OfferDeals $offerdeal, AdminSupplierOfferRequest $request)
    {
        $validation = $request->validate([
            'promotion_id' => 'required',
            'product_id' => 'required',
            'promotion_type' => 'required',
            'user_id' => 'required',
            'period_from' => 'required',
            'period_to' => 'required',
            'promotion_price' => 'required',
        ]);

        $offerdeal->create($request->all());
        // if ($request->hasFile('image_file') && $request->file('image_file')->isValid()) {

        //     $documentFile = $offerdeal->uploadMedia($request->file('image_file'));
        //     $document = $documentFile['path'] . $documentFile['name'];
        //     $request->merge(['image' => $document]);
        // }
        // $request->merge(['start_date' => \Carbon\Carbon::parse($request->startdate)]);
        // $request->merge(['end_date' => \Carbon\Carbon::parse($request->enddate)]);
        // $offerdeal->create($request->all());


        $route = $this->route;
        $redirectRoute = route("$route.index");


        return redirect("$redirectRoute")->with(['status' => 'success', 'message' => trans('success.supplier|offerDeals|created')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Promotion $offerdeal)
    // public function destroy(OfferDeals $offerdeal)
    {
        $route = $this->route;
        // if ($offerdeal->canDelete()) {
        try {

            $offerdeal->delete();

            return redirect(route("$route.index"))->with(['status' => 'success', 'message' => trans('success.supplier|offerDeals|deleted')]);
        } catch (\Exception $exception) {
            return redirect(route("$route.index"))->with(['status' => 'warning', 'message' => trans('warning.supplier|offerDeals|deleteNotPossible')]);
        }
        // } else {
        //     return redirect(route("$route.index"))->with(['status' => 'warning', 'message' => trans('warning.supplier|offerDeals|deleteNotPossible')]);
        // }
    }
}
