<?php

namespace App\Http\Controllers;

use App\Exports\DataGridExport;
use App\Http\Controllers\Helpers\DataGrid;
use App\Http\Requests\AdminLocationCityRequest;
use App\Http\Requests\AdminLocationStateRequest;
use App\Models\LocationCity;
use App\Models\LocationState;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Excel;

class AdminLocationCityController extends Controller
{

    use DataGrid;

    public $dataUrl = '/admin/location-master/:state_uuid/city';

    public $route = 'admin.city';

    /**
     * @param Request $request
     * @param $state_uuid
     * @param LocationCity $locationCityModel
     * @param LocationState $locationStateModel
     * @param Excel $excel
     * @return \Illuminate\Contracts\View\Factory|View|Excel|\Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function index(Request $request, $state_uuid, LocationCity $locationCityModel, LocationState $locationStateModel, Excel $excel)
    {
        $this->dataUrl = strtr($this->dataUrl, [':state_uuid' => $state_uuid]);
        $filters = [];
        $filters[] = ['title' => 'No'];

        $filters[] = [
            'title' => 'Name',
            'column' => 'city_name',
            'operator' => 'LIKE',
            'sorting' => true,
            'search' => [
                'type' => 'text',
                'placeholder' => 'Search name'
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
                'data' => $locationCityModel->getStatusesDropDown()
            ]
        ];

        $filters[] = [
            'title' => 'Action'
        ];

        $tableName = $locationCityModel->getTable();
        $url = $this->dataUrl;
        $locationCityModel->setStateUuid($state_uuid);

        $this->setGridModel($locationCityModel);
        $this->setScopes(['ofState']);
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
            $fileName = 'CITY_DATA';
            return $excel->download(new DataGridExport('admin.locationCity.export', $data), "$fileName.xlsx");
        }

        $state = $locationStateModel->with('country')->where('uuid', $state_uuid)->first();

        $route = $this->route;
        $pageTitle = "MANAGE CITIES";

        if ($request->ajax()) {
            return view('admin.locationCity.grid', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'route', 'state_uuid', 'state'));
        } else {
            return view('admin.locationCity.index', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'pageTitle', 'route', 'state_uuid', 'state'));
        }
    }

    /**
     * @param $state_uuid
     * @param LocationCity $city
     * @param LocationState $locationStateModel
     * @return View
     */
    public function create($state_uuid, LocationCity $city, LocationState $locationStateModel) : View
    {
        $pageTitle = "CREATE CITY";
        $status = $city->getStatusesDropDown();
        $route = $this->route;
        $state = $locationStateModel->with('country')->where('uuid', $state_uuid)->first();
        return view('admin.locationCity.form', compact('city', 'status', 'pageTitle', 'route', 'state_uuid', 'state'));
    }

    /**
     * @param AdminLocationStateRequest $request
     * @param $state_uuid
     * @param LocationCity $city
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(AdminLocationCityRequest $request, $state_uuid, LocationCity $city)
    {
        $cityModel = $city->create($request->all());
        $route = $this->route;
        $redirectRoute = route("$route.index", $state_uuid);
        if($request->has('save_continue'))
        {
            $redirectRoute = route("$route.edit", [$state_uuid, $cityModel->uuid]);
        }
        return redirect($redirectRoute)->with(['status' => 'success', 'message' => trans('success.admin|locationCity|created')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LocationCity  $locationCity
     * @return \Illuminate\Http\Response
     */
    public function show(LocationCity $locationCity)
    {
        //
    }

    /**
     * @param $state_uuid
     * @param LocationCity $city
     * @param LocationState $locationStateModel
     * @return View
     */
    public function edit($state_uuid, LocationCity $city, LocationState $locationStateModel) : View
    {
        $copy = request()->has('copy') ? true : false;
        $pageTitle = $copy ? "COPY CITY" : "EDIT CITY";
        $status = $city->getStatusesDropDown();
        $route = $this->route;
        $state = $locationStateModel->with('country')->where('uuid', $state_uuid)->first();
        return view('admin.locationCity.form', compact('city', 'status', 'pageTitle', 'route', 'state_uuid', 'state', 'copy'));
    }

    /**
     * @param AdminLocationCityRequest $request
     * @param $state_uuid
     * @param LocationCity $city
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(AdminLocationCityRequest $request, $state_uuid, LocationCity $city)
    {
        $city->update($request->all());
        $route = $this->route;
        $redirectRoute = route("$route.index", $state_uuid);
        if($request->has('save_continue'))
        {
            $redirectRoute = route("$route.edit", [$state_uuid, $city->uuid]);
        }
        return redirect($redirectRoute)->with(['status' => 'success', 'message' => trans('success.admin|locationCity|updated')]);
    }

    /**
     * @param $state_uuid
     * @param LocationCity $city
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($state_uuid, LocationCity $city)
    {
        $route = $this->route;
        if($city->canDelete())
        {
            try{
                $city->delete();
                return redirect(route("$route.index", $state_uuid))->with(['status' => 'success', 'message' => trans('success.admin|locationCity|deleted')]);

            }catch (\Exception $exception)
            {
                return redirect(route("$route.index", $state_uuid))->with(['status' => 'warning', 'message' => trans('warning.admin|locationCity|deleteNotPossible')]);
            }
        }
        else
        {
            return redirect(route("$route.index", $state_uuid))->with(['status' => 'warning', 'message' => trans('warning.admin|locationCity|deleteNotPossible')]);
        }
    }
}
