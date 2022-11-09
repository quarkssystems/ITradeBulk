<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\DataGrid;
use App\Http\Requests\FrontSupplierOfferRequest;
use Illuminate\Http\Request;
use App\Models\OfferDeals;
use App\Models\Product;
use App\Models\ArrivalType;
use App\Models\Promotion;
use App\PromoType;
use App\User;
use Carbon\Carbon;

use Illuminate\View\View;
use stdClass;

class FrontSupplierOfferController extends Controller
{
    use DataGrid;

    public $route = 'user.offers';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Promotion $offer)
    // public function index(Request $request, OfferDeals $offer)
    {

        $filters = [];
        $filters[] = ['title' => 'No'];

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

        $filters[] = [
            'title' => 'Promotion',
            'column' => 'promotion_price',
            'operator' => '=',
            'sorting' => true
        ];

        $filters[] = [
            'title' => 'Date',
            'column' => 'created_at',
            'operator' => '=',
            'sorting' => true
        ];

        $filters[] = [
            'title' => 'Action'
        ];

        $tableName = $offer->getTable();
        $url = route($this->route . ".index");
        $this->setGridModel($offer);

        $this->setScopesWithValue(['UserID' => auth()->user()->uuid]);
        $this->setScopes(["ActivePromotionn"]);


        $this->setGridRequest($request);
        $this->setFilters($filters);


        $this->setSorting(['sorting_field' => $tableName . '_sorting_field', 'sort' => $tableName . '_sort', 'default_field' => 'created_at', 'default_sort' => 'DESC']);

        $this->setGridUrl($url);

        $this->setGridVariables();


        $data = $this->getGridData();
        $dataGridTitle = $this->gridTitles();
        $dataGridSearch = $this->gridSearch();
        $dataGridPagination = $this->gridPagination($data);

        $todayDate = Carbon::now()->format('Y-m-d');

        $route = $this->route;

        $pageTitle = "Manage Promotions";

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
            return view('user.offerDeals.grid', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'route', 'todayDate'));
        } else {
            return view('user.offerDeals.index', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'pageTitle', 'route', 'todayDate'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Promotion $offerdeal, User $user, Product $product)
    // public function create(OfferDeals $offerDeal, Product $product)
    {

        $route = $this->route;
        $role = auth()->user()->role;
        $suppliers = $user->where('uuid', auth()->user()->uuid)->get()->pluck('first_name', 'uuid');
        // $suppliers = $user->getDropDownSuppiler();

        $products = $product->where('user_id', auth()->user()->uuid)->get()->pluck('name', 'uuid');
        $promoType = PromoType::where('status', '1')->get()->pluck('type', 'type');
        $pageTitle = "ADD PROMOTIONS";

        return view('user.offerDeals.form', compact('offerdeal', 'pageTitle', 'route', 'role', 'suppliers',  'products', 'promoType'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Promotion $offer, Request $request)
    {
        // dd($request->all());
        $validation = $request->validate([
            'promotion_id' => 'required',
            'product_id' => 'required',
            'promotion_type' => 'required',
            'user_id' => 'required',
            'period_from' => 'required',
            'period_to' => 'required',
            'promotion_price' => 'required',
            // 'current_price' => 'required'
        ]);

        $route = $this->route;
        $redirectRoute = route("$route.index");
        $offer->create($request->all());


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
    public function edit($id,  Product $product, User $user)
    {

        $offerdeal = Promotion::where('uuid', $id)->first();

        $route = $this->route;
        $role = auth()->user()->role;
        $suppliers = $user->where('uuid', auth()->user()->uuid)->get()->pluck('first_name', 'uuid');

        $products = $product->where('user_id', auth()->user()->uuid)->get()->pluck('name', 'uuid');
        $productsData = $product->where('uuid', $offerdeal->product_id)->select('base_price', 'stock_expiry_date')->first();
        if ($productsData == null) {
            $productsData = new stdClass();
            $productsData->base_price = '';
            $productsData->stock_expiry_date = '';
        }

        $promoType = PromoType::where('status', '1')->get()->pluck('type', 'type');
        $pageTitle = "ADD PROMOTIONS";

        return view('user.offerDeals.edit', compact('offerdeal', 'pageTitle', 'route', 'role', 'suppliers',  'products', 'promoType', 'productsData'));
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

        $validation = $request->validate([
            'promotion_id' => 'required',
            'product_id' => 'required',
            'promotion_type' => 'required',
            'user_id' => 'required',
            'period_from' => 'required',
            'period_to' => 'required',
            'promotion_price' => 'required',
            // 'current_price' => 'required'
        ]);

        $route = $this->route;
        $redirectRoute = route("$route.index");
        $data = $request->except(['_method', '_token', 'save_exit', 'base_price', 'stock_expiry']);
        $offerData = Promotion::where('uuid', $id)->update($data);

        return redirect("$redirectRoute")->with(['status' => 'success', 'message' => trans('success.supplier|offerDeals|updated')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Promotion $offer)
    {

        $route = $this->route;
        if ($offer->canDelete()) {
            try {

                $offer->delete();
                return redirect(route("$route.index"))->with(['status' => 'success', 'message' => trans('success.supplier|offerDeals|deleted')]);
            } catch (\Exception $exception) {
                return redirect(route("$route.index"))->with(['status' => 'warning', 'message' => trans('warning.supplier|offerDeals|deleteNotPossible')]);
            }
        } else {
            return redirect(route("$route.index"))->with(['status' => 'warning', 'message' => trans('warning.supplier|offerDeals|deleteNotPossible')]);
        }
    }
}
