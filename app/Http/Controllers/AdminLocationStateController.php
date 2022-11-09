<?php

namespace App\Http\Controllers;

use App\Exports\DataGridExport;
use App\Http\Controllers\Helpers\DataGrid;
use App\Http\Requests\AdminLocationStateRequest;
use App\Models\LocationCountry;
use App\Models\LocationState;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Excel;

class AdminLocationStateController extends Controller
{
    use DataGrid;

    public $dataUrl = '/admin/location-master/:country_uuid/state';

    public $route = 'admin.state';

    /**
     * @param Request $request
     * @param $country_uuid
     * @param LocationState $locationStateModel
     * @param LocationCountry $locationCountryModel
     * @param Excel $excel
     * @return \Illuminate\Contracts\View\Factory|View|Excel|\Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function index(Request $request, $country_uuid, LocationState $locationStateModel, LocationCountry $locationCountryModel, Excel $excel)
    {
        $this->dataUrl = strtr($this->dataUrl, [':country_uuid' => $country_uuid]);
        $filters = [];
        $filters[] = ['title' => 'No'];

        $filters[] = [
            'title' => 'Name',
            'column' => 'state_name',
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
                'data' => $locationStateModel->getStatusesDropDown()
            ]
        ];

        $filters[] = [
            'title' => 'Action'
        ];

        $tableName = $locationStateModel->getTable();
        $url = $this->dataUrl;
        $locationStateModel->setCountryUuid($country_uuid);

        $this->setGridModel($locationStateModel);
        $this->setScopes(['ofCountry']);
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
            $fileName = 'PROVINCE_DATA';
            return $excel->download(new DataGridExport('admin.locationState.export', $data), "$fileName.xlsx");
        }



        $country = $locationCountryModel->where('uuid', $country_uuid)->first();

        $route = $this->route;
        $pageTitle = "MANAGE PROVINCE";

        if ($request->ajax()) {
            return view('admin.locationState.grid', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'route', 'country_uuid', 'country'));
        } else {
            return view('admin.locationState.index', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'pageTitle', 'route', 'country_uuid', 'country'));
        }
    }

    /**
     * @param $country_uuid
     * @param LocationState $state
     * @param LocationCountry $locationCountryModel
     * @return View
     */
    public function create($country_uuid, LocationState $state, LocationCountry $locationCountryModel) : View
    {
        $pageTitle = "CREATE PROVINCE";
        $status = $state->getStatusesDropDown();
        $route = $this->route;
        $country = $locationCountryModel->where('uuid', $country_uuid)->first();
        return view('admin.locationState.form', compact('state', 'status', 'pageTitle', 'route', 'country_uuid', 'country'));
    }

    /**
     * @param AdminLocationStateRequest $request
     * @param $country_uuid
     * @param LocationState $state
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(AdminLocationStateRequest $request, $country_uuid, LocationState $state)
    {
        $stateModel = $state->create($request->all());
        $route = $this->route;
        $redirectRoute = route("$route.index", $country_uuid);
        if($request->has('save_continue'))
        {
            $redirectRoute = route("$route.edit", [$country_uuid, $stateModel->uuid] );
        }
        return redirect($redirectRoute)->with(['status' => 'success', 'message' => trans('success.admin|locationState|created')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LocationState  $locationState
     * @return \Illuminate\Http\Response
     */
    public function show(LocationState $locationState)
    {
        //
    }

    /**
     * @param $country_uuid
     * @param LocationState $state
     * @param LocationCountry $locationCountryModel
     * @return View
     */
    public function edit($country_uuid, LocationState $state, LocationCountry $locationCountryModel) : View
    {
        $status = $state->getStatusesDropDown();

        $copy = request()->has('copy') ? true : false;
        $pageTitle = $copy ? "COPY PROVINCE" : "EDIT PROVINCE";
        $route = $this->route;
        $country = $locationCountryModel->where('uuid', $country_uuid)->first();
        return view('admin.locationState.form', compact('state', 'status', 'pageTitle', 'route', 'country_uuid', 'country', 'copy'));
    }

    /**
     * @param AdminLocationStateRequest $request
     * @param $country_uuid
     * @param LocationState $state
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(AdminLocationStateRequest $request, $country_uuid, LocationState $state)
    {
        $state->update($request->all());
        $route = $this->route;
        $redirectRoute = route("$route.index", $country_uuid);
        if($request->has('save_continue'))
        {
            $redirectRoute = route("$route.edit", [$country_uuid, $state->uuid] );
        }
        return redirect($redirectRoute)->with(['status' => 'success', 'message' => trans('success.admin|locationState|updated')]);
    }

    /**
     * @param $country_uuid
     * @param LocationState $state
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($country_uuid, LocationState $state)
    {
        $route = $this->route;
        if($state->canDelete())
        {
            try{
                $state->delete();
                return redirect(route("$route.index", $country_uuid))->with(['status' => 'success', 'message' => trans('success.admin|locationState|deleted')]);

            }catch (\Exception $exception)
            {
                return redirect(route("$route.index", $country_uuid))->with(['status' => 'warning', 'message' => trans('warning.admin|locationState|deleteNotPossible')]);
            }
        }
        else
        {
            return redirect(route("$route.index", $country_uuid))->with(['status' => 'warning', 'message' => trans('warning.admin|locationState|deleteNotPossible')]);
        }
    }
}
