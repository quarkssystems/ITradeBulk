<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\DataGridExport;
use App\Http\Controllers\Helpers\DataGrid;
use App\Http\Requests\FrontSupplierItemInventoryRequest;
use App\User;
use App\Models\SupplierItemInventory;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\History\ProductHistory;
use App\Models\Tax;
use Illuminate\View\View;
use Maatwebsite\Excel\Excel;

class FrontSupplierItemStockController extends Controller
{
    use DataGrid;

    public $dataUrl = '/supplier/stock';

    public $route = 'supplier.stock';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Product $productModel, Excel $excel, Brand $brandModel, Tax $taxModel, SupplierItemInventory $inventory, user $userModel)
    {

        $data = $productModel->where('user_id', auth()->user()->uuid)->paginate();

        $filters = [];

        $filters[] = ['title' => 'No'];

        $filters[] = [
            'title' => 'Name',
            'column' => 'product_name',
            'operator' => 'SCOPE',
            'sorting' => true,
            'search' => [
                'type' => 'scope',
                'scope' => 'productNameFilter',
                'placeholder' => 'Search name'
            ]
        ];

        $filters[] = [
            'title' => 'Brand',
            'column' => 'brand',
            'operator' => 'SCOPE',
            'sorting' => true,
            'search' => [
                'type' => 'scope',
                'scope' => 'productBrandFilter',
                'placeholder' => 'Search brand'
            ]
        ];

        $filters[] = [
            'title' => 'Stock Type',
            'column' => 'product_stock_type',
            'operator' => 'SCOPE',
            'sorting' => true,
            'search' => [
                'type' => 'scope',
                'scope' => 'productStockTypeFilter',
                'placeholder' => 'Search stock type'
            ]
        ];




        $filters[] = [
            'title' => 'Quantity/Price'
        ];

        //        $filters[] = [
        //            'title' => 'Action'
        //        ];
        $user_uuid = auth()->user()->uuid;
        $tableName = $inventory->getTable();
        $url = route($this->route . ".index", $user_uuid);
        $this->setGridModel($inventory);
        $this->setScopesWithValue(['ofSupplier' => $user_uuid]);
        $this->setScopes(['onlyActive']);
        $this->setScopes(['OfNoParent']);
        $this->setGridRequest($request);
        $this->setFilters($filters);

        $this->setSorting(['sorting_field' => $tableName . '_sorting_field', 'sort' => $tableName . '_sort', 'default_field' => 'created_at', 'default_sort' => 'DESC']);

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

        $route = $this->route;
        //$role = $this->userRole;
        //$navTab = $this->navTab;
        $user = $userModel->where('uuid', $user_uuid)->first();
        $pageTitle = "MANAGE Stock";

        $data = tap($data, function ($query) {
            return $query->getCollection()->transform(function ($value) {
                $product = Product::where('uuid', $value->product_id)->select('base_price')->first();
                if ($product != null) {
                    $value->base_price = $product->base_price;
                } else {
                    $value->base_price = null;
                }
                return $value;
            });
        });

        if ($request->has('export_data')) {
            $fileName = 'SUPPLIER_STOCK_DATA';
            return $excel->download(new DataGridExport('admin.supplierStock.export', $data), "$fileName.xlsx");
        }

        if ($request->ajax()) {
            return view('supplier.stock.grid', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'route', 'user'));
        } else {
            return view('supplier.stock.index', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'pageTitle', 'route', 'user'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, SupplierItemInventory $inventory, Product $productModel)
    {
        $route = $this->route;
        $productId = $request->product_id;
        $product = $productModel->where('uuid', $productId)->first();
        $inventory = $inventory->where(['user_id' => auth()->user()->uuid, 'product_id' => $productId])->count() > 0 ? $inventory->where(['user_id' => auth()->user()->uuid, 'product_id' => $productId])->first() : $inventory;
        return view('supplier.inventory.add-item-form', compact('inventory', 'route', 'product'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FrontSupplierItemInventoryRequest $request, SupplierItemInventory $inventory)
    {

        $route = $this->route;
        $productId = $request->product_id;
        if ($inventory->where(['user_id' => auth()->user()->uuid, 'product_id' => $productId])->count() > 0) {
            $inventory->where(['user_id' => auth()->user()->uuid, 'product_id' => $productId])->update($request->except(['_token']));
        } else {
            if ($request->get("single") > 0 || $request->get("shrink") > 0 || $request->get("case") > 0 || $request->get("pallet") > 0) {
                $inventory->create($request->all());
            }
        }
        $redirectRoute = route("$route.index");

        return redirect($redirectRoute)->with(['status' => 'success', 'message' => trans('success.supplier|add-item-inventory|success'), 'modal' => 'open']);
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
    public function destroy($id)
    {
        //
    }
}
