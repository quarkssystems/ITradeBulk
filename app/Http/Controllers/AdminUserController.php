<?php



namespace App\Http\Controllers;

use App\AdminDetails;
use App\Exports\DataGridExport;

use App\Http\Controllers\Helpers\DataGrid;

use App\Http\Requests\AdminUserRequest;

use App\Http\Requests\AdminZipUploadRequest;

use App\User;

use Carbon\Carbon;

use Illuminate\Http\Request;

use Illuminate\View\View;

use Maatwebsite\Excel\Excel;

use Session;

class AdminUserController extends Controller

{



    use DataGrid;



    public $dataUrl = '/admin/users';



    public $userRole = 'ADMIN';



    public $route = 'admin.users';



    public $navTab = 'admin.users.admin.navTab';



    /**

     * @param Request $request

     * @param User $userModel

     * @param Excel $excel

     * @return View

     * @throws \PhpOffice\PhpSpreadsheet\Exception

     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception

     */

    public function index(Request $request, User $userModel, Excel $excel)

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

                'data' => $userModel->getStatusesDropDown()

            ]

        ];





        $filters[] = [

            'title' => 'Action'

        ];



        $tableName = $userModel->getTable();

        $url = $this->dataUrl;

        $this->setGridModel($userModel);

        $this->setScopesWithValue(['userRole' => $this->userRole]);

        $this->setGridRequest($request);

        $this->setFilters($filters);



        $this->setSorting(['sorting_field' => $tableName . '_sorting_field', 'sort' => $tableName . '_sort', 'default_field' => 'created_at', 'default_sort' => 'DESC']);



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

        $role = $this->userRole;

        if ($role == 'VENDOR') {

            $pageTitle = "MANAGE TRADER";

            $roleLabel = "TRADER";
        } else if ($role == 'DRIVER') {

            $pageTitle = "MANAGE TRANSPORTER";

            $roleLabel = "TRANSPORTER";
        } else if ($role == 'COMPANY') {

            $pageTitle = "MANAGE TRANSPORT COMPANY";

            $roleLabel = "TRANSPORT COMPANY";
        } else {

            $pageTitle = "MANAGE " . $role;

            $roleLabel = $role;
        }



        if ($request->has('export_data')) {

            $fileName = 'USERS_DATA';

            return $excel->download(new DataGridExport('admin.users.export', $data), "$fileName.xlsx");
        }

        if (Session::has('UserPage')) {
            Session::forget('UserPage');
        }
        Session::put('UserPage', $request->input('page') ?? 1);

        if ($request->ajax()) {

            return view('admin.users.grid', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'route', 'role', 'roleLabel'));
        } else {

            return view('admin.users.index', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'pageTitle', 'route', 'role', 'roleLabel'));
        }
    }



    /**

     * @param User $user

     * @return \Illuminate\Contracts\View\Factory|View

     */

    public function create(User $user): View

    {

        $type = array();

        $gender = $user->getGenderDropDown();

        $title = $user->getTitleDropDown();

        $logisticCompanies = $user->getLogisticCompanyDropDown();



        $roles = $user->getRoleDropDown();

        $status = $user->getStatusesDropDown();

        $route = $this->route;

        $role = $this->userRole;

        if ($role == 'VENDOR') {

            $pageTitle = "CREATE TRADER";

            $roleLabel = "TRADER";
        } else if ($role == 'DRIVER') {

            $pageTitle = "CREATE TRANSPORTER";

            $roleLabel = "TRANSPORTER";
        } else {

            $pageTitle = "CREATE " . $role;

            $roleLabel = $role;
        }

        return view('admin.users.form', compact('user', 'gender', 'title', 'type', 'roles', 'status', 'pageTitle', 'route', 'role', 'logisticCompanies', 'roleLabel'));
    }





    public function store(AdminUserRequest $request, User $user)

    {

        $request->merge([

            'password' => bcrypt($request->get('password')),

            'role' => $this->userRole

        ]);

        if ($request->hasFile('image_file') && $request->file('image_file')->isValid()) {

            $documentFile = $user->uploadMedia($request->file('image_file'));

            $document = $documentFile['path'] . $documentFile['name'];

            $request->merge(['image' => $document]);
        }



        $company_name = $user->getCompanyName($request->get('logistic_company_id'));



        if ($company_name != '') {

            $request->merge(['transporter_name' => $company_name]);
        }

        $userModel = $user->create($request->all());

        $userModel->sendEmailVerificationNotification();

        $route = $this->route;

        return redirect(route("$route.index"))->with(['status' => 'success', 'message' => trans('success.admin|user|created')]);
    }



    /**

     * Display the specified resource.

     *

     * @param  \App\User  $user

     * @return \Illuminate\Http\Response

     */

    public function show(User $user)

    {

        //

    }



    /**

     * @param User $user

     * @return View

     */

    public function edit(User $user): View

    {

        $type = array();

        $gender = $user->getGenderDropDown();

        $title = $user->getTitleDropDown();

        $logisticCompanies = $user->getLogisticCompanyDropDown();

        $roles = $user->getRoleDropDown();

        $status = $user->getStatusesDropDown();

        $route = $this->route;

        $role = $this->userRole;

        $copy = request()->has('copy') ? true : false;

        if ($role == 'VENDOR') {

            $pageTitle = $copy ? "COPY TRADER" : "EDIT TRADER";

            $roleLabel = "TRADER";
        } else if ($role == 'DRIVER') {

            $pageTitle = $copy ? "COPY TRANSPORTER" : "EDIT TRANSPORTER";

            $roleLabel = "TRANSPORTER";
        } else {

            $pageTitle = $copy ? "COPY $role" : "EDIT $role";

            $roleLabel = $role;
        }

        $navTab = $this->navTab;



        return view('admin.users.form', compact('user', 'logisticCompanies', 'gender', 'title', 'type', 'roles', 'status', 'pageTitle', 'route', 'role', 'navTab', 'copy', 'roleLabel'));
    }



    /**

     * @param AdminUserRequest $request

     * @param User $user

     * @return \Illuminate\Http\RedirectResponse

     */

    public function update(AdminUserRequest $request, User $user)

    {

        if (empty($request->get('password'))) {

            unset($request['password']);

            unset($request['password_confirmation']);
        } else {

            $request->replace(['password' => bcrypt($request->get('password'))]);
        }

        if ($request->hasFile('image_file') && $request->file('image_file')->isValid()) {

            $documentFile = $user->uploadMedia($request->file('image_file'));

            $document = $documentFile['path'] . $documentFile['name'];

            $request->merge(['image' => $document]);
        }

        $user->update($request->all());

        $route = $this->route;

        if (Session::has('UserPage')) {
            // echo 11;die;
            $page = session()->get("UserPage");
            // $redirectRoute = ;
            return redirect()->route("$route.index", ['page' => $page])->with(['status' => 'success', 'message' => trans('success.admin|user|updated')]);
        }

        return redirect(route("$route.index"))->with(['status' => 'success', 'message' => trans('success.admin|user|updated')]);
    }



    /**

     * Remove the specified resource from storage.

     *

     * @param  \App\User  $user

     * @return \Illuminate\Http\Response

     */

    public function destroy(User $user)

    {

        $route = $this->route;

        if ($user->canDelete()) {

            try {

                $user->delete();

                return redirect(route("$route.index"))->with(['status' => 'success', 'message' => trans('success.admin|user|deleted')]);
            } catch (\Exception $exception) {

                return redirect(route("$route.index"))->with(['status' => 'warning', 'message' => trans('warning.admin|user|deleteNotPossible')]);
            }
        } else {

            return redirect(route("$route.index"))->with(['status' => 'warning', 'message' => trans('warning.admin|user|deleteNotPossible')]);
        }
    }



    public function uploadZipImages(AdminZipUploadRequest $request)
    {

        $input = $request->all();

        switch ($input['foldername']) {

            case 'product':

                $route = 'admin.products';

                break;

            case 'categories':

                $route = 'admin.categories';

                break;

            case 'manufacturer':

                $route = 'admin.brands';

                break;
        }

        $result = uploadZip($input);
        //added
        if ($result['status'] == '422') {
            return redirect(route("$route.index"))->with(['errstatus' => 'danger', 'errmessage' => $result['msg']]);
        }
        //end added
        return redirect(route("$route.index"))->with(['status' => 'success', 'message' => $result['msg']]);
    }

    public function adminDetails()
    {
        $pageTitle = 'Admin Details';
        $data = AdminDetails::first();
        return view('admin.adminDetails.index', compact('pageTitle', 'data'));
    }

    public function adminDetailsStore(Request $request)
    {
        $validation = $request->validate([
            'address' => 'required',
            'icon' => 'mimes:png,jpg,jpeg',
        ]);

        $adminData = AdminDetails::where('id', $request->id)->first();
        if ($adminData == null) {
            $validation = $request->validate([
                'icon' => 'required|mimes:png,jpg,jpeg',
            ]);
        }

        $data = $request->all();
        if ($request->hasFile('icon') && $request->file('icon')->isValid()) {
            $adminDetails = new AdminDetails;
            $documentFile = $adminDetails->uploadMedia($request->file('icon'));
            $document = $documentFile['path'] . $documentFile['name'];
            $data['icon'] = $document;
        } else if ($adminData != null) {
            $data['icon'] = $adminData->icon;
        } else {
            $data['icon'] = '';
        }

        AdminDetails::updateOrCreate(['id' => $data['id']], [
            'address' => $data['address'],
            'icon' =>  $data['icon'],
            // 'icon' => 'uploads/media/common/' . auth()->user()->uuid . '/' . $request->icon,
        ]);
        return redirect(route("admin.admin-details"))->with(['status' => 'success', 'message' => 'Admin details updated successfully.']);
    }
}
