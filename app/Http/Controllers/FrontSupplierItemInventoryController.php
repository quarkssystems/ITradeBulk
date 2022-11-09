<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\DataGridExport;
use App\Http\Controllers\Helpers\DataGrid;
use App\Http\Requests\FrontSupplierItemInventoryRequest;
use App\Models\SupplierItemInventory;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\History\ProductHistory;
use App\Models\Tax;
use App\Models\UserDocument;
use Illuminate\View\View;
use Maatwebsite\Excel\Excel;

class FrontSupplierItemInventoryController extends Controller
{
    use DataGrid;

    public $dataUrl = '/supplier/inventory';

    public $route = 'supplier.inventory';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Product $productModel, Excel $excel, Brand $brandModel, Tax $taxModel, SupplierItemInventory $inventory)
    {

        $data = $productModel->withoutGlobalScopes()->where('user_id', auth()->user()->uuid)->paginate();

        $filters = [];
        $filters[] = ['title' => 'No'];


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
            'title' => 'Brand',
            'column' => 'brand_id',
            'operator' => '=',
            'sorting' => true,
            'search' => [
                'type' => 'select',
                'placeholder' => 'Show all',
                'data' => $brandModel->getDropDown()
            ]
        ];

        $filters[] = [
            'title' => 'Stock Type',
            'column' => 'stock_type',
            'operator' => 'LIKE',
            'sorting' => true,
            'search' => [
                'type' => 'text',
                'placeholder' => 'Search stock type'
            ]
        ];


        $filters[] = [
            'title' => 'Quantity',
        ];
        /*$filters[] = [
            'title' => 'Shrink',
        ];
        $filters[] = [
            'title' => 'Case',
        ];
        $filters[] = [
            'title' => 'Pallet',
        ];*/
        // $filters[] = [
        //     'title' => 'Action'
        // ];

        if (auth()->user()->fact_access == '1') {
            $filters[] = [
                'title' => 'Edit'
            ];
        }


        $tableName = $productModel->getTable();
        $productModel->setSupplierId(auth()->user()->uuid);
        $url = $this->dataUrl;
        $this->setGridModel($productModel);
        $this->setGridRequest($request);
        $this->setFilters($filters);
        $this->setScopes(['active']);
        $this->setScopes(['OfNoParent']);
        // $this->setscopeOfSupplier($productModel);
        $this->model->setSupplierId(auth()->user()->uuid);

        /* $this->setSorting(['sorting_field' => $tableName . '_sorting_field', 'sort' => $tableName . '_sort', 'default_field' => 'supplier_stock', 'default_sort' => 'DESC']);*/

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

        if ($request->has('export_data')) {
            $fileName = 'PRODUCT_DATA';
            return $excel->download(new DataGridExport('admin.product.export', $data), "$fileName.xlsx");
        }


        $userdoc = new UserDocument;
        $route_doc = 'supplier.document.create';
        $curr_id = auth()->user()->uuid;
        $route_err = route($route_doc, $curr_id);
        $doc_approve = $userdoc->getDocumentStatus();

        $data = tap($data, function ($query) {
            return $query->getCollection()->transform(function ($value) {
                $product = Product::where('uuid', $value->uuid)->select('base_price')->first();
                if ($product != null) {
                    $value->base_price = $product->base_price;
                } else {
                    $value->base_price = null;
                    // $value->edit = '';
                }
                // $value->edit = '<a href="/supplier/updateFact/' . $value->uuid . '"  class="ajax-modal btn-small btn btn-primary btn-xs my-2 my-sm-0">Edit</a>';
                return $value;
            });
        });

        $route = $this->route;

        $pageTitle = "Inventory";

        if ($request->ajax()) {
            return view('supplier.inventory.grid', compact('data', 'doc_approve', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'route'));
        } else {
            return view('supplier.inventory.index', compact('data', 'doc_approve', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'pageTitle', 'route', 'inventory'));
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
        Product::where('uuid', $productId)->update([
            'price' => $request->single_price,
            'quantity' => $request->single,
        ]);
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

    public function updateFact(Request $request, $id, SupplierItemInventory $inventory, Product $productModel)
    {
        // dd($id);
        $route = $this->route;
        // $productId = $request->product_id;
        $inventory = $inventory->where('product_id', $id)->first();
        // dd($inventory);
        // $inventory = $inventory->where(['user_id' => auth()->user()->uuid, 'product_id' => $productId])->count() > 0 ? $inventory->where(['user_id' => auth()->user()->uuid, 'product_id' => $productId])->first() : $inventory;
        $product = $productModel->where('uuid', $id)->first();
        // dd($product);

        $pageTitle = '';
        return view('supplier.inventory.edit-item-form', compact('inventory', 'route', 'product', 'pageTitle'));

        // $supplierItemInventory = SupplierItemInventory::where('uuid', $id)->first();
        // $in Product::where('uuid',$id)->first();
        // return view('supplier.inventory.edit-item-form', compact('supplierItemInventory'));
    }

    public function updateStoreFact(Request $request)
    {

        Product::where('uuid', $request->product_id)->update([
            'vat' => $request->stoc_vat,
            'cost' => $request->cost,
            'markup' => $request->markup,
            'autoprice' => $request->autoprice,
            'price' => $request->single_price,
            'quantity' => $request->single,
            'min_order_quantity' => $request->min_order_quantity,
            'stock_expiry_date' => $request->stock_expiry_date,
        ]);  // Product::where('uuid', $request->product_id)->where('user_id', $request->user_id)->update([
        //     'vat' => $request->vat,
        //     'cost' => $request->cost,
        //     'markup' => $request->markup,
        //     'autoprice' => $request->autoprice,
        //     'price' => $request->price,
        //     'quantity' => $request->quantity,
        //     'min_order_quantity' => $request->min_order_quantity,
        //     'stock_expiry_date' => $request->stock_expiry_date,
        // ]);
        $data = SupplierItemInventory::where('product_id', $request->product_id)->first();

        if ($data != null) {
            $data->update([
                'stoc_vat' =>  $request->stoc_vat,
                'cost' =>  $request->cost,
                'markup' =>  $request->markup,
                'autoprice' =>  $request->autoprice,
                'min_order_quantity' =>  $request->min_order_quantity,
                'stock_expiry_date' =>  $request->stock_expiry_date,
                'single' =>  $request->single,
                'single_price' =>  $request->single_price
            ]);
        } else {
            SupplierItemInventory::create([
                'user_id' => $request->user_id,
                'product_id' => $request->product_id,
                'store_id' => $request->store_id,
                'stoc_vat' => $request->stoc_vat,
                'cost' => $request->cost,
                'markup' => $request->markup,
                'autoprice' => $request->autoprice,
                'min_order_quantity' => $request->min_order_quantity,
                'stock_expiry_date' => $request->stock_expiry_date,
                'single' => $request->single,
                'single_price' => $request->single_price
            ]);
        }
        return redirect()->back()->with('message', 'Stock updated successfully.');
    }
}
