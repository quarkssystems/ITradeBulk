<?php

namespace App\Http\Controllers;

use App\Exports\DataGridExport;
use App\Http\Controllers\Helpers\BaseController;
use App\Http\Controllers\Helpers\DataGrid;
use App\Models\LogisticCompany;
use App\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Excel;
use App\Http\Requests\AdminUserRequest;

class AdminManageLogisticsCompaniesController extends Controller
{
    use DataGrid;
    use BaseController;
    
    public $dataUrl = '/admin/logistic-company';
    public $route = 'admin.logistic-company';
    public $navTab = 'admin.logisticCompanyDetails.navTab';

    /**
     * @param Request $request
     * @param User $userModel
     * @param Excel $excel
     * @return View
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function index(Request $request, LogisticCompany $logisticCompanyModel, Excel $excel)
    {
        $filters = [];

        $filters[] = ['title' => 'No'];

        $filters[] = [
            'title' => 'Name',
            'column' => 'first_name',
            'operator' => 'LIKE',
            'sorting' => true,
            'search' => [
                'type' => 'text',
                'placeholder' => 'Search name'
            ]
        ];

        $filters[] = [
            'title' => 'Email',
            'column' => 'email',
            'operator' => 'LIKE',
            'sorting' => true,
            'search' => [
                'type' => 'text',
                'placeholder' => 'Search email'
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
                'data' => $logisticCompanyModel->getStatusesDropDown()
            ]
        ];


        $filters[] = [
            'title' => 'Action'
        ];

        $tableName = $logisticCompanyModel->getTable();
        $url = $this->dataUrl;
        $this->setGridModel($logisticCompanyModel);
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

        $route = $this->route;
        $pageTitle = "MANAGE Logistic Companies";

        if($request->has('export_data'))
        {
            $fileName = 'USERS_DATA';
            return $excel->download(new DataGridExport('admin.users.export', $data), "$fileName.xlsx");
        }

        if ($request->ajax()) {
            return view('admin.logisticCompanyDetails.grid', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'route', 'role'));
        } else {
            return view('admin.logisticCompanyDetails.index', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'pageTitle', 'route', 'role'));
        }
    }

    /**
     * @param logisticCompany $logisticCompany
     * @return \Illuminate\Contracts\View\Factory|View
     */
    public function create(LogisticCompany $logisticCompany,User $user) : View
    {
        $type = array();
        $gender = $user->getGenderDropDown();
        $title = $user->getTitleDropDown();
        $roles = $user->getRoleDropDown();
        $status = $logisticCompany->getStatusesDropDown();
        $route = $this->route;
        $pageTitle = "CREATE LOGISTIC COMPANY";
        return view('admin.logisticCompanyDetails.form', compact('user', 'gender', 'title', 'type', 'roles', 'status', 'pageTitle', 'route','logisticCompany'));
    }

    public function store(AdminUserRequest $request, LogisticCompany $logisticCompany)
    {
        $request->merge(['password' => bcrypt($request->get('password'))]);
        if($request->hasFile('image_file') && $request->file('image_file')->isValid())
        {
            $documentFile = $logisticCompany->uploadMedia($request->file('image_file'));
            $document = $documentFile['path'].$documentFile['name'];
            $request->merge(['image' => $document]);
        }
        $logisticCompanyModel = $logisticCompany->create($request->all());
        $route = $this->route;
        return redirect(route("$route.index"))->with(['status' => 'success', 'message' => trans('success.admin|user|created')]);
        
    }

    public function edit(LogisticCompany $logisticCompany,User $user)
    {
        $type = array();
        $gender = $user->getGenderDropDown();
        $title = $user->getTitleDropDown();
        $roles = $user->getRoleDropDown();
        $status = $user->getStatusesDropDown();
        $route = $this->route;
        $navTab = $this->navTab;
        $pageTitle = "EDIT LOGISTIC COMPANY";

        return view('admin.logisticCompanyDetails.form', compact('user','logisticCompany','gender', 'title', 'type', 'roles', 'status', 'pageTitle', 'route', 'role', 'navTab'));
    }
    public function destroy(LogisticCompany $manage_logistic)
    {
        $route = $this->route;
        if($manage_logistic->canDelete())
        {
            try{
                $manage_logistic->delete();
                return redirect(route("$route.index"))->with(['status' => 'success', 'message' => trans('success.admin|user|deleted')]);

            }catch (\Exception $exception)
            {
                return redirect(route("$route.index"))->with(['status' => 'warning', 'message' => trans('warning.admin|user|deleteNotPossible')]);
            }
        }
        else
        {
            return redirect(route("$route.index"))->with(['status' => 'warning', 'message' => trans('warning.admin|user|deleteNotPossible')]);
        }
    }
}
