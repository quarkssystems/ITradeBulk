<?php

namespace App\Http\Controllers;

use App\PromoType;
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
use DB;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Session;
use Auth;

class PromoTypeController extends Controller
{
    use DataGrid;

    public $dataUrl = '/admin/promo-type';

    public $route = 'admin.promo-type';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, PromoType $promoType)
    {
        $filters = [];
        $filters[] = ['title' => 'No'];

        $filters[] = [
            'title' => 'Type',
            'column' => 'type',
            'operator' => 'LIKE',
            'sorting' => true,
            'search' => [
                'type' => 'text',
                'placeholder' => 'Search type'
            ]
        ];

        $filters[] = [
            'title' => 'Status',
            'column' => 'status',

        ];

        $tableName = $promoType->getTable();
        $url = $this->dataUrl;
        $this->setGridModel($promoType);
        $this->setGridRequest($request);
        $this->setFilters($filters);


        // dd($tableName);
        // $this->setSorting(['sorting_field' => $tableName . '_sorting_field', 'sort' => $tableName . '_sort', 'default_field' => 'type', 'default_sort' => 'ASC']);

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

        // if ($request->has('export_data')) {
        //     $fileName = 'PRODUCT_DATA';
        //     $adminQuickView = AdminQuickView::where('user_id', Auth::user()->uuid)->first();

        //     return $excel->download(new DataGridExport('admin.product.export', [$data, $adminQuickView]), "$fileName.xlsx");
        // }

        $route = $this->route;

        $pageTitle = "PROMO TYPE";


        if (Session::has('ProductPage')) {
            Session::forget('ProductPage');
        }
        Session::put('ProductPage', $request->input('page') ?? 1);

        $data = tap($data, function ($query) {
            return $query->getCollection()->transform(function ($value) {
                $check = '';
                $cval = 1;

                $onOff = '';
                if ($value->status == 1) {
                    $onOff = 'checked';
                    $cval = 0;
                }

                $check =  '<label class="switchNew">
                        <input type="checkbox" ' . $onOff . ' class="onoff" data-id="' . $value->id . '" data-onoff="' . $value->status . '" data-conoff="' . $cval . '" >
                        <span class="slider round"></span>
                        </label>';

                $value->switch = $check;
                return $value;
            });
        });


        if ($request->ajax()) {
            return view('admin.promo_type.grid', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'route'));
        } else {
            return view('admin.promo_type.index', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'pageTitle', 'route'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, PromoType $promoType)
    {
        $pageTitle = "CREATE PROMO TYPE";
        $route = $this->route;

        return view('admin.promo_type.form', compact('pageTitle', 'route', 'promoType'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation = $request->validate([
            'type' => 'required',
            'status' => 'required|in:0,1',
        ]);

        PromoType::create($request->all());
        return redirect('admin/promo-type')->with('message', 'Promo type created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PromoType  $promoType
     * @return \Illuminate\Http\Response
     */
    public function show(PromoType $promoType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PromoType  $promoType
     * @return \Illuminate\Http\Response
     */
    public function edit(PromoType $promoType)
    {
        $pageTitle = "EDIT PROMO TYPE";
        $route = $this->route;

        return view('admin.promo_type.form', compact('pageTitle', 'route', 'promoType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PromoType  $promoType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PromoType $promoType)
    {
        $validation = $request->validate([
            'type' => 'required',
            'status' => 'required|in:0,1',
        ]);

        $promoType->update($request->all());
        // PromoType::create($request->all());
        return redirect('admin/promo-type')->with('message', 'Promo type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PromoType  $promoType
     * @return \Illuminate\Http\Response
     */
    public function destroy(PromoType $promoType)
    {
        $promoType->delete();
        return redirect('admin/promo-type')->with('message', 'Promo type deleted successfully.');
    }

    public function promoTypeOn($id)
    {
        $data = PromoType::where('id', $id)->select('status')->first();
        if ($data->status == 1) {
            PromoType::where('id', $id)->update(['status' => '0']);
        } else {
            PromoType::where('id', $id)->update(['status' => '1']);
        }
        return $data;
    }
}
