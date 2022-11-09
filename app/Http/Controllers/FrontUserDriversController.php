<?php



namespace App\Http\Controllers;



use App\Http\Requests\FrontDriverRequest;

use App\Exports\DataGridExport;

use App\Http\Controllers\Helpers\DataGrid;

use App\User;

use Illuminate\Http\Request;

use Illuminate\View\View;

use Maatwebsite\Excel\Excel;

use App\Models\UserDocument;

use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Mail;

class FrontUserDriversController extends Controller

{

    use DataGrid;



    public $route = 'supplier.drivers';

    public $dataUrl = '/supplier/drivers';

    public $userRole = 'DRIVER';



    /**

     * @param Request $request

     * @param Category $categoryModel

     * @param Excel $excel

     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|Excel|\Symfony\Component\HttpFoundation\BinaryFileResponse

     * @throws \PhpOffice\PhpSpreadsheet\Exception

     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception

     */

    public function index(Request $request, User $userModel, Excel $excel)

    {



        $userdoc = New UserDocument; 

        $route_doc = 'supplier.document.create';

        $curr_id = auth()->user()->uuid;

        $user = $userModel->where('uuid',$curr_id)->first();
        $userEmail = $user->email;
        
        $phone = '+88 0123 4567 890, +88 0123 4567 999';
        $facebook_url = 'https://www.facebook.com/';
        $instagram_url = 'https://www.instagram.com/';
        $twitter_url = 'https://www.twitter.com/';
        $pinterest_url = 'https://www.pinterest.com/';

        $route_err = route($route_doc,$curr_id);

        if(!$userdoc->getDocumentStatus()){

             $message = "We would like to inform you that your KYC is not completed. please complete your KYC";

            $email = EmailTemplate::where('name','=','transport_company_KYC_pending_notification')->first();

            if(isset($email)){
                $email->description = str_replace('[CUSTOMER_NAME]', $user['first_name'].' '.$user['last_name'], $email->description);
                $email->description = str_replace('[PHONE]', $phone, $email->description);
                $email->description = str_replace('[FACEBOOK_URL]', $facebook_url, $email->description);
                $email->description = str_replace('[TWITTER_URL]', $twitter_url, $email->description);
                $email->description = str_replace('[INSTAGRAM_URL]', $instagram_url, $email->description);
                $email->description = str_replace('[PINTEREST_URL]', $pinterest_url, $email->description);
                $email->description = str_replace('[SITE_NAME]', env('WEBSITE'), $email->description);
                $email->description = str_replace('[LOGO]', asset('assets/images/logo.png'), $email->description);
            } 

            $emailContent = $email->description;

            Mail::send([], [], function ($message) use ($userEmail , $emailContent) {
              $message->to($userEmail)
                ->subject('Trasport Company - KYC Pending Notification')
                ->setBody($emailContent, 'text/html'); // for HTML rich messages
            });

              return redirect(route($route_doc))->withErrors(['status' => 'warning', 'message' => trans($message)]);

         }

            

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

        $this->setScopesWithValue(['userRole' => $this->userRole,'LogisticCompanyId' => auth()->user()->uuid]);

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

        $role = $this->userRole;



        $pageTitle = "MANAGE $role";



        if ($request->ajax()) {

            return view('supplier.drivers.grid', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'route', 'role'));

        } else {

            return view('supplier.drivers.index', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'pageTitle', 'route', 'role'));

        }

    }



    /*public function edit(SalesOrder $sales_order) : View

    {

        $statuses = $sales_order->getStatusesDropDown();

        $pageTitle = "View Order #". $sales_order->order_number;

        $route = $this->route;

        return view('user.salesOrder.view', compact('sales_order', 'pageTitle', 'route', 'role', 'statuses', 'copy'));

    }

   */



    public function create(User $driver) 

    {



        $userdoc = New UserDocument; 

        $route_doc = 'supplier.document.create';

        $curr_id = auth()->user()->uuid;

        $route_err = route($route_doc,$curr_id);

        if(!$userdoc->getDocumentStatus()){

             $message = "We would like to inform you that your KYC is not completed. please complete your KYC";



              return redirect(route($route_doc))->withErrors(['status' => 'warning', 'message' => trans($message)]);

         }



        $type = array();

        $gender = $driver->getGenderDropDown();

        $title = $driver->getTitleDropDown();

        $logisticCompanies = $driver->getLogisticCompanyDropDown();

        $roles = $driver->getRoleDropDown();

        $status = $driver->getStatusesDropDown();

        $route = $this->route;

        $role = $this->userRole;

      

            $pageTitle = "CREATE TRANSPORTER";

            $roleLabel = "TRANSPORTER";

       

        return view('supplier.drivers.form', compact('gender', 'title', 'type', 'roles', 'status', 'pageTitle', 'route', 'role','logisticCompanies', 'roleLabel','driver'));

    }





    public function store(FrontDriverRequest $request, User $user)

    {

        $request->merge([

            'password' => bcrypt($request->get('password')),

            'role' => $this->userRole,

            'latitude' => auth()->user()->latitude,

            'longitude' =>  auth()->user()->longitude,

            'logistic_company_id' => auth()->user()->uuid,

            'logistic_type'     => 'COMPANY',

            'transporter_name' => auth()->user()->transporter_name

        ]);

        if($request->hasFile('image_file') && $request->file('image_file')->isValid())

        {

            $documentFile = $user->uploadMedia($request->file('image_file'));

            $document = $documentFile['path'].$documentFile['name'];

            $request->merge(['image' => $document]);

        }

        $userModel = $user->create($request->all());

       // $userModel->sendEmailVerificationNotification();

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

    public function edit(User $driver) : View

    {

        

        $type = array();

        $gender = $driver->getGenderDropDown();

        $title = $driver->getTitleDropDown();

        $logisticCompanies = $driver->getLogisticCompanyDropDown();

       

        $roles = $driver->getRoleDropDown();

        $status = $driver->getStatusesDropDown();

        $route = $this->route;

        $role = $this->userRole;

        $copy = request()->has('copy') ? true : false;

        

        $pageTitle = $copy ? "COPY $role" : "DRIVER $role";

        $roleLabel = $role;

      



        return view('supplier.drivers.form', compact('driver', 'gender', 'title', 'type', 'roles', 'status', 'pageTitle', 'route', 'role','logisticCompanies', 'roleLabel'));

    }



    /**

     * @param AdminUserRequest $request

     * @param User $user

     * @return \Illuminate\Http\RedirectResponse

     */

    public function update(FrontDriverRequest $request, User $driver)

    {

        if(empty($request->get('password')))

        {

             unset($request['password']);

             unset($request['password_confirmation']);

        }

        else

        {

            $request->replace(['password' => bcrypt($request->get('password'))]);

        }

      

        $driver->update($request->all());

        $route = $this->route;

        return redirect(route("$route.index"))->with(['status' => 'success', 'message' => trans('success.admin|user|updated')]);

    }



    /**

     * Remove the specified resource from storage.

     *

     * @param  \App\User  $user

     * @return \Illuminate\Http\Response

     */

    public function destroy(User $driver)

    {



        $route = $this->route;

        if($driver->canDelete())

        {   

            try{

                $driver->delete();

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