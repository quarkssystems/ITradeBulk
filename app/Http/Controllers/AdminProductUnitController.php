<?php



namespace App\Http\Controllers;



use App\Exports\DataGridExport;

use App\Http\Controllers\Helpers\DataGrid;

use App\Http\Requests\AdminProductUnitRequest;

use App\Models\ProductUnit;
use App\Models\Product;
use App\Models\History\ProductHistory;

use App\Models\Tax;

use Illuminate\Http\Request;

use Illuminate\View\View;

use Maatwebsite\Excel\Excel;

use Session;


class AdminProductUnitController extends Controller

{

    use DataGrid;



    public $route = 'admin.product-unit';

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function index(Request $request, ProductUnit $productUnitModel, Excel $excel)

    {

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

            'title' => 'Unit',

            'column' => 'unit',

            'operator' => 'LIKE',

            'sorting' => true,

            'search' => [

                'type' => 'text',

                'placeholder' => 'Search tax'

            ]

        ];



        $filters[] = [

            'title' => 'Action'

        ];



        $tableName = $productUnitModel->getTable();

        $url = route($this->route . ".index");

        $this->setGridModel($productUnitModel);

        $this->setGridRequest($request);

        $this->setFilters($filters);



        $this->setSorting(['sorting_field' => $tableName.'_sorting_field', 'sort' => $tableName.'_sort', 'default_field' => 'created_at', 'default_sort' => 'DESC']);



        $this->setGridUrl($url);



        $this->setGridVariables();



        if($request->has('export_data'))

        {

            $this->setPaginationEnable(false);

            $data = $this->getGridData();

        }

        else

        {

            $data = $this->getGridData();

            $dataGridTitle = $this->gridTitles();

            $dataGridSearch = $this->gridSearch();

            $dataGridPagination = $this->gridPagination($data);

        }



        if($request->has('export_data'))

        {

            $fileName = 'TAX_DATA';

            return $excel->download(new DataGridExport('admin.productUnit.export', $data), "$fileName.xlsx");

        }





        $route = $this->route;



        $pageTitle = "MANAGE PRODUCT UNIT";

        if(Session::has('ProductUnitPage')){
            Session::forget('ProductUnitPage');
        }
        Session::put('ProductUnitPage', $request->input('page') ?? 1);

        if ($request->ajax()) {

            return view('admin.productUnit.grid', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'route'));

        } else {

            return view('admin.productUnit.index', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'pageTitle', 'route'));

        }

    }



    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function create(ProductUnit $product_unit) : View

    {

        $pageTitle = "CREATE PRODUCT UNIT";

        $route = $this->route;

        return view('admin.productUnit.form', compact('product_unit', 'pageTitle', 'route'));

    }



    /**

     * Store a newly created resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\Response

     */

    public function store(AdminProductUnitRequest $request, ProductUnit $product_unit)

    {

        $productUnitModel = $product_unit->create($request->all());

        $route = $this->route;

        $redirectRoute = route("$route.index");

        if($request->has('save_continue'))

        {

            $redirectRoute = route("$route.edit", $productUnitModel->uuid);

        }

        return redirect($redirectRoute)->with(['status' => 'success', 'message' => trans('success.admin|productUnit|created')]);

    }



    /**

     * Display the specified resource.

     *

     * @param  \App\Models\ProductUnit  $productUnit

     * @return \Illuminate\Http\Response

     */

    public function show(ProductUnit $productUnit)

    {

        //

    }



    /**

     * Show the form for editing the specified resource.

     *

     * @param  \App\Models\ProductUnit  $productUnit

     * @return \Illuminate\Http\Response

     */

    public function edit(ProductUnit $product_unit) : View

    {

        $copy = request()->has('copy') ? true : false;

        $pageTitle = $copy ? "COPY PRODUCT UNIT" : "EDIT PRODUCT UNIT";

        $route = $this->route;

        return view('admin.productUnit.form', compact('product_unit', 'pageTitle', 'route', 'copy'));

    }



    /**

     * Update the specified resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  \App\Models\ProductUnit  $productUnit

     * @return \Illuminate\Http\Response

     */

    public function update(AdminProductUnitRequest $request, ProductUnit $product_unit)

    {

       Product::where('unit_name',$product_unit->unit)->update(['unit_name' => $request->unit]);
       ProductHistory::where('unit_name',$product_unit->unit)->update(['unit_name' => $request->unit]);

        $productUnitModel = $product_unit->update($request->all());

        $route = $this->route;

        $redirectRoute = route("$route.index");

        if($request->has('save_continue'))

        {

            $redirectRoute = route("$route.edit", $productUnitModel->uuid);

        }

        elseif(Session::has('ProductUnitPage')){
            // echo 11;die;
            $page = session()->get("ProductUnitPage");
            // $redirectRoute = ;
            return redirect()->route("$route.index", ['page'=>$page])->with(['status' => 'success', 'message' => trans('success.admin|productUnit|updated')]);
        }

        return redirect($redirectRoute)->with(['status' => 'success', 'message' => trans('success.admin|productUnit|created')]);

    }



    /**

     * Remove the specified resource from storage.

     *

     * @param  \App\Models\ProductUnit  $productUnit

     * @return \Illuminate\Http\Response

     */

    public function destroy(ProductUnit $product_unit)

    {

        $route = $this->route;

        if($product_unit->canDelete())

        {

            try{

                $product_unit->delete();

                return redirect(route("$route.index"))->with(['status' => 'success', 'message' => trans('success.admin|productUnit|deleted')]);



            }catch (\Exception $exception)

            {

                return redirect(route("$route.index"))->with(['status' => 'warning', 'message' => trans('warning.admin|productUnit|deleteNotPossible')]);

            }

        }

        else

        {

            return redirect(route("$route.index"))->with(['status' => 'warning', 'message' => trans('warning.admin|productUnit|deleteNotPossible')]);

        }

    }

}