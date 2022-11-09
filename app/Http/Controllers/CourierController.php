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
use App\Courier;
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

class CourierController extends Controller
{
    use DataGrid;

    public $dataUrl = '/admin/courier';

    public $route = 'admin.courier';

    public function index(Request $request, Courier $courierModel, Excel $excel)
    {

        $filters = [];
        $filters[] = ['title' => 'No'];



        $filters[] = [
            'title' => 'Pic',
            'column' => 'upload_option_pic',
        ];

        $filters[] = [
            'title' => 'Name',
            'column' => 'name',
            // 'operator' => 'LIKE',
            // 'sorting' => true,
            // 'search' => [
            //     'type' => 'text',
            //     'placeholder' => 'Search delivery option'
            // ]
        ];

        $filters[] = [
            'title' => 'Default Courier',
        ];

        $filters[] = [
            'title' => 'Delivery Option',
            'column' => 'delivery_option',
            // 'operator' => 'LIKE',
            // 'sorting' => true,
            // 'search' => [
            //     'type' => 'text',
            //     'placeholder' => 'Search delivery option'
            // ]
        ];



        $filters[] = [
            'title' => 'Std Lead Time',
            'column' => 'std_lead_time',
            // 'operator' => 'LIKE',
            // 'sorting' => true,
            // 'search' => [
            //     'type' => 'text',
            //     'placeholder' => 'Search std lead time'
            // ]
        ];




        $filters[] = [
            'title' => 'Courier Lead Time',
            'column' => 'courier_lead_time',
            // 'operator' => 'LIKE',
            // 'sorting' => true,
            // 'search' => [
            //     'type' => 'text',
            //     'placeholder' => 'Search courier lead time'
            // ]
        ];

        $filters[] = [
            'title' => 'Delivery Markup',
            'column' => 'delivery_markup',
            // 'operator' => 'LIKE',
            // 'sorting' => true,
            // 'search' => [
            //     'type' => 'text',
            //     'placeholder' => 'Search delivery markup'
            // ]
        ];

        $filters[] = [
            'title' => 'Min Delivery Fee',
            'column' => 'min_delivery_fee',
            // 'operator' => 'LIKE',
            // 'sorting' => true,
            // 'search' => [
            //     'type' => 'text',
            //     'placeholder' => 'Search min delivery fee'
            // ]
        ];

        $filters[] = [
            'title' => 'Available',
            'column' => 'status',
            // 'operator' => 'LIKE',
            // 'sorting' => true,
            // 'search' => [
            //     'type' => 'text',
            //     'placeholder' => 'Search min delivery fee'
            // ]
        ];

        $filters[] = [
            'title' => 'Action',
        ];

        $tableName = $courierModel->getTable();
        $url = $this->dataUrl;
        $this->setGridModel($courierModel);
        $this->setGridRequest($request);
        $this->setFilters($filters);

        if (\Route::current()->getName() !== 'admin.products.index') {

            // $this->setScopes(["OfUserNotAdmin"]);

        }

        // $this->setScopes(["OfNoParent"]);

        $this->setSorting(['sorting_field' => $tableName . '_sorting_field', 'sort' => $tableName . '_sort', 'default_field' => 'created_at', 'default_sort' => 'ASC']);

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

        $route = $this->route;

        $pageTitle = "Courier Management";

        $data = tap($data, function ($query) {
            return $query->getCollection()->transform(function ($value) {
                $check = '';
                $cval = 1;

                // $fileExist = file_exists( public_path() . $value->icon_file) ? 1 : 0;
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
                // Your code here
                return $value;
            });
        });


        if (Session::has('ProductPage')) {
            Session::forget('ProductPage');
        }
        Session::put('ProductPage', $request->input('page') ?? 1);


        if ($request->ajax()) {
            return view('admin.courier.grid', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'route'));
        } else {
            return view('admin.courier.index', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'pageTitle', 'route'));
        }
    }

    public function create(Request $request, Courier $courier)
    {
        $route = $this->route;
        $pageTitle = "CREATE COURIER";
        $users = User::where('status', 'ACTIVE')->where('role', 'SUPPLIER')->whereNull('deleted_at')->select('uuid', 'first_name', 'last_name')->get();

        return view('admin.courier.form', compact('pageTitle',  'route', 'courier', 'users'));
    }

    public function store(Request $request, Courier $courier)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'account' => 'required',
            'link_to_portal' => 'required',
            'address' => 'required',
            'default_courier' => 'required',
            'delivery_option' => 'required',
            'upload_option_pic' => 'required',
            'std_lead_time' => 'required',
            'courier_lead_time' => 'required',
            'delivery_markup' => 'required',
            'min_delivery_fee' => 'required',
            'is_own' => 'required',
            'own_user_id' => 'required_if:is_own,==,1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->except(['_token', 'hidden']);
        if ($request->hasFile('upload_option_pic') && $request->file('upload_option_pic')->isValid()) {
            $documentFile = $courier->uploadMedia($request->file('upload_option_pic'));
            $document = $documentFile['path'] . $documentFile['name'];
            $data['upload_option_pic'] = $document;
        }

        $data['status'] = '1';
        Courier::create($data);

        $route = $this->route;

        $redirectRoute = route("$route.index");

        return redirect($redirectRoute)->with(['status' => 'success', 'message' => trans('success.admin|courier|created')]);
    }

    public function show()
    {
    }


    public function edit($id)
    {

        $route = $this->route;
        $pageTitle = "EDIT COURIER";
        $users = User::where('status', 'ACTIVE')->where('role', 'SUPPLIER')->whereNull('deleted_at')->select('uuid', 'first_name', 'last_name')->get();

        $courier = Courier::where('id', $id)->first();
        return view('admin.courier.form', compact('pageTitle',  'route', 'courier', 'users'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'account' => 'required',
            'link_to_portal' => 'required',
            'address' => 'required',
            'default_courier' => 'required',
            'delivery_option' => 'required',
            // 'upload_option_pic' => 'required',
            'std_lead_time' => 'required',
            'courier_lead_time' => 'required',
            'delivery_markup' => 'required',
            'min_delivery_fee' => 'required',
            'is_own' => 'required',
            'own_user_id' => 'required_if:is_own,==,1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $courier = Courier::where('id', $id)->first();
        $data = $request->except(['_token', '_method', 'hidden']);
        if ($request->hasFile('upload_option_pic') && $request->file('upload_option_pic')->isValid()) {
            $documentFile = $courier->uploadMedia($request->file('upload_option_pic'));
            $document = $documentFile['path'] . $documentFile['name'];
            $data['upload_option_pic'] = $document;
        }

        Courier::where('id', $id)->update($data);

        $route = $this->route;

        $redirectRoute = route("$route.index");

        return redirect($redirectRoute)->with(['status' => 'success', 'message' => trans('success.admin|courier|updated')]);
    }


    public function destroy($id)
    {
        Courier::where('id', $id)->delete();
        $route = $this->route;
        $redirectRoute = route("$route.index");
        return redirect($redirectRoute)->with(['status' => 'success', 'message' => trans('success.admin|courier|updated')]);
    }

    public function getCourierData()
    {
        $courierData = Courier::where('status', '1')->get();
        return $courierData;
    }

    public function changeCourierStatus($id)
    {
        $data = Courier::where('id', $id)->select('status')->first();
        if ($data->status == '1') {
            Courier::where('id', $id)->update(['status' => '0']);
        } else {
            Courier::where('id', $id)->update(['status' => '1']);
        }
        return $data;
    }
}
