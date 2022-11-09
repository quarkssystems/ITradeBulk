<?php

namespace App\Http\Controllers;

use App\Exports\DataGridExport;
use App\Http\Controllers\Helpers\DataGrid;
use App\Http\Requests\AdminLocationZipcodeRequest;
use App\Models\LocationCity;
use App\Models\LocationZipcode;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Excel;

class AdminLocationZipcodeController extends Controller
{
    use DataGrid;

    public $dataUrl = '/admin/location-master/:city_uuid/zipcode';

    public $route = 'admin.zipcode';

    /**
     * @param Request $request
     * @param $city_uuid
     * @param LocationZipcode $locationZipcodeModel
     * @param LocationCity $locationCityModel
     * @param Excel $excel
     * @return \Illuminate\Contracts\View\Factory|View|Excel|\Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function index(Request $request, $city_uuid, LocationZipcode $locationZipcodeModel, LocationCity $locationCityModel, Excel $excel)
    {
        $this->dataUrl = strtr($this->dataUrl, [':city_uuid' => $city_uuid]);
        $filters = [];
        $filters[] = ['title' => 'No'];

        $filters[] = [
            'title' => 'Name',
            'column' => 'zipcode_name',
            'operator' => 'LIKE',
            'sorting' => true,
            'search' => [
                'type' => 'text',
                'placeholder' => 'Search name'
            ]
        ];

        $filters[] = [
            'title' => 'Postal Code',
            'column' => 'zipcode',
            'operator' => 'LIKE',
            'sorting' => true,
            'search' => [
                'type' => 'text',
                'placeholder' => 'Search postal code'
            ]
        ];

        $filters[] = [
            'title' => 'Status',
            'column' => 'status',
            'operator' => '=',
            'sorting' => true,
            'search' => [
                'type' => 'select',
                'placeholder' => 'Show all',
                'data' => $locationZipcodeModel->getStatusesDropDown()
            ]
        ];

        $filters[] = [
            'title' => 'Action'
        ];

        $tableName = $locationZipcodeModel->getTable();
        $url = $this->dataUrl;
        $locationZipcodeModel->setCityUuid($city_uuid);

        $this->setGridModel($locationZipcodeModel);
        $this->setScopes(['ofCity']);
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
            $fileName = 'POSTAL_CODE_DATA';
            return $excel->download(new DataGridExport('admin.locationZipcode.export', $data), "$fileName.xlsx");
        }

        $city = $locationCityModel->with(['country', 'state'])->where('uuid', $city_uuid)->first();

        $route = $this->route;
        $pageTitle = "MANAGE POSTAL CODES";

        if ($request->ajax()) {
            return view('admin.locationZipcode.grid', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'route', 'city_uuid', 'city'));
        } else {
            return view('admin.locationZipcode.index', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'pageTitle', 'route', 'city_uuid', 'city'));
        }
    }

    /**
     * @param $city_uuid
     * @param LocationZipcode $zipcode
     * @param LocationCity $locationCityModel
     * @return View
     */
    public function create($city_uuid, LocationZipcode $zipcode, LocationCity $locationCityModel) : View
    {
        $pageTitle = "CREATE POSTAL CODE";
        $status = $zipcode->getStatusesDropDown();
        $route = $this->route;
        $city = $locationCityModel->with(['country', 'state'])->where('uuid', $city_uuid)->first();
        return view('admin.locationZipcode.form', compact('zipcode', 'status', 'pageTitle', 'route', 'city_uuid', 'city'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdminLocationZipcodeRequest $request, $city_uuid, LocationZipcode $zipcode)
    {
        $zipCodeModel = $zipcode->create($request->all());
        $route = $this->route;
        $redirectRoute = route("$route.index", $city_uuid);
        if($request->has('save_continue'))
        {
            $redirectRoute = route("$route.edit", [$city_uuid, $zipCodeModel->uuid] );
        }
        return redirect($redirectRoute)->with(['status' => 'success', 'message' => trans('success.admin|locationZipcode|created')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LocationZipcode  $locationZipcode
     * @return \Illuminate\Http\Response
     */
    public function show(LocationZipcode $locationZipcode)
    {
        //
    }

    /**
     * @param $city_uuid
     * @param LocationZipcode $zipcode
     * @param LocationCity $locationCityModel
     * @return View
     */
    public function edit($city_uuid, LocationZipcode $zipcode, LocationCity $locationCityModel) : View
    {
        $copy = request()->has('copy') ? true : false;
        $pageTitle = $copy ? "COPY POSTAL CODE" : "EDIT POSTAL CODE";
        $status = $zipcode->getStatusesDropDown();
        $route = $this->route;
        $city = $locationCityModel->with(['country', 'state'])->where('uuid', $city_uuid)->first();
        return view('admin.locationZipcode.form', compact('zipcode', 'status', 'pageTitle', 'route', 'city_uuid', 'city', 'copy'));
    }

    /**
     * @param AdminLocationZipcodeRequest $request
     * @param $city_uuid
     * @param LocationZipcode $zipcode
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(AdminLocationZipcodeRequest $request, $city_uuid, LocationZipcode $zipcode)
    {
        $zipcode->update($request->all());
        $route = $this->route;
        $redirectRoute = route("$route.index", $city_uuid);
        if($request->has('save_continue'))
        {
            $redirectRoute = route("$route.edit", [$city_uuid, $zipcode->uuid] );
        }
        return redirect($redirectRoute)->with(['status' => 'success', 'message' => trans('success.admin|locationZipcode|updated')]);
    }

    /**
     * @param $city_uuid
     * @param LocationZipcode $zipcode
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($city_uuid, LocationZipcode $zipcode)
    {
        $route = $this->route;
        if($zipcode->canDelete())
        {
            try{
                $zipcode->delete();
                return redirect(route("$route.index", $city_uuid))->with(['status' => 'success', 'message' => trans('success.admin|locationZipcode|deleted')]);

            }catch (\Exception $exception)
            {
                return redirect(route("$route.index", $city_uuid))->with(['status' => 'warning', 'message' => trans('warning.admin|locationZipcode|deleteNotPossible')]);
            }
        }
        else
        {
            return redirect(route("$route.index", $city_uuid))->with(['status' => 'warning', 'message' => trans('warning.admin|locationZipcode|deleteNotPossible')]);
        }
    }
}
