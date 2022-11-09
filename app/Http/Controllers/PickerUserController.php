<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\DataGrid;
use App\Http\Requests\FrontSupplierOfferRequest;
use Illuminate\Http\Request;
use App\Models\OfferDeals;
use App\Models\Product;
use App\Models\ArrivalType;
use App\Models\EmailTemplate;
use App\Models\Promotion;
use App\PromoType;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class PickerUserController extends Controller
{
    use DataGrid;

    public $route = 'supplier.picker-users';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, User $user)
    // public function index(Request $request, OfferDeals $offer)
    {

        $filters = [];
        $filters[] = ['title' => 'No'];

        $filters[] = [
            'title' => 'Name',
            'column' => 'first_name',
            'operator' => 'LIKE',
            'sorting' => true,
        ];

        $filters[] = [
            'title' => 'gender',
            'column' => 'gender',
            'operator' => 'LIKE',
            'sorting' => true,
        ];

        $filters[] = [
            'title' => 'email',
            'column' => 'email',
            'operator' => '=',
            'sorting' => true,

        ];

        $filters[] = [
            'title' => 'status',
            'column' => 'switch',

        ];
        // $filters[] = [
        //     'title' => 'mobile',
        //     'column' => 'mobile',
        //     'operator' => '=',
        //     'sorting' => true
        // ];

        $filters[] = [
            'title' => 'Action'
        ];

        $tableName = $user->getTable();
        $url = route($this->route . ".index");
        // $url = route($this->route . ".index");
        $this->setGridModel($user);

        // $this->setScopesWithValue(['userRole' => 'PICKER']);
        // $this->setScopesWithValue(['supplierIdForPicker' => auth()->user()->uuid]);
        $this->setScopesWithValue(['getPickerUser' => auth()->user()->uuid]);

        // $this->setScopesWithValue(['role' => auth()->user()->uuid]);
        $this->setGridRequest($request);
        $this->setFilters($filters);


        $this->setSorting(['sorting_field' => $tableName . '_sorting_field', 'sort' => $tableName . '_sort', 'default_field' => 'created_at', 'default_sort' => 'DESC']);

        $this->setGridUrl($url);

        $this->setGridVariables();


        $data = $this->getGridData();
        $dataGridTitle = $this->gridTitles();
        $dataGridSearch = $this->gridSearch();
        $dataGridPagination = $this->gridPagination($data);

        $todayDate = Carbon::now()->format('Y-m-d');

        $route = $this->route;

        $pageTitle = "MANAGE PICKER USERS";

        $data = tap($data, function ($query) {
            return $query->getCollection()->transform(function ($value) {
                $check = '';
                $cval = 1;

                $onOff = '';
                if ($value->status == 'ACTIVE') {
                    $onOff = 'checked';
                    $cval = 0;
                }

                $check =  '<label class="switchNew">
                    <input type="checkbox" ' . $onOff . ' class="onoff" data-id="' . $value->uuid . '" data-onoff="' . $value->status . '" data-conoff="' . $cval . '">
                    <span class="slider round"></span>
                    </label>';

                $value->switch = $check;
                // Your code here
                return $value;
            });
        });

        if ($request->ajax()) {
            return view('user.pickerUser.grid', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'route', 'todayDate'));
        } else {
            return view('user.pickerUser.index', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'pageTitle', 'route', 'todayDate'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(User $user)
    {

        $route = $this->route;
        $role = auth()->user()->role;

        $pageTitle = "ADD PICKER USER";
        $gender = $user->getGenderDropDown();
        $title = $user->getTitleDropDown();

        return view('user.pickerUser.form', compact('pageTitle', 'route', 'role', 'user', 'gender', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(User $user, Request $request)
    {
        // dd($request->all());
        $validation = $request->validate([
            'title' => 'required',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => [
                'required',
                'min:8',
                'confirmed',
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                'regex:/[@$!%*#?&]/'
            ],
            'gender' => 'required',
        ]);

        $data = $request->all();
        $data['role'] = 'PICKER';
        $data['status'] = 'ACTIVE';
        $data['email_verified_at'] = Carbon::now()->toDateTimeString();
        $data['supplier_id'] = auth()->user()->uuid;
        $data['password'] = bcrypt($request->password);
        $data['latitude'] = auth()->user()->latitude;
        $data['longitude'] = auth()->user()->longitude;
        $route = $this->route;
        $redirectRoute = route("$route.index");
        $user->create($data);


        $userEmail = $request->email;
        $phone = '+88 0123 4567 890, +88 0123 4567 999';
        $facebook_url = 'https://www.facebook.com/';
        $instagram_url = 'https://www.instagram.com/';
        $twitter_url = 'https://www.twitter.com/';
        $pinterest_url = 'https://www.pinterest.com/';

        $email = EmailTemplate::where('name', '=', 'supplier_KYC_pending_notification')->first();

        if (isset($email)) {
            $email->description = str_replace('[CUSTOMER_NAME]', $request['first_name'] . ' ' . $request['last_name'], $email->description);
            $email->description = str_replace('[SITE_NAME]', env('WEBSITE'), $email->description);
            $email->description = str_replace('[PHONE]', $phone, $email->description);
            $email->description = str_replace('[FACEBOOK_URL]', $facebook_url, $email->description);
            $email->description = str_replace('[TWITTER_URL]', $twitter_url, $email->description);
            $email->description = str_replace('[INSTAGRAM_URL]', $instagram_url, $email->description);
            $email->description = str_replace('[PINTEREST_URL]', $pinterest_url, $email->description);
            $email->description = str_replace('[LOGO]', asset('assets/images/logo.png'), $email->description);
        }

        $emailContent = $email->description;

        Mail::send([], [], function ($message) use ($userEmail, $emailContent) {
            $message->to($userEmail)
                ->subject('Supplier - KYC Pending Notification')
                ->setBody($emailContent, 'text/html'); // for HTML rich messages
        });

        return redirect("$redirectRoute")->with(['status' => 'success', 'message' => trans('success.admin|user|created')]);
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
    public function edit($id, User $user)
    {

        $route = $this->route;
        $role = auth()->user()->role;

        $pageTitle = "EDIT PICKER USER";
        $gender = $user->getGenderDropDown();
        $title = $user->getTitleDropDown();
        $user = $user->where('uuid', $id)->first();

        return view('user.pickerUser.edit', compact('pageTitle', 'route', 'role', 'user', 'gender', 'title'));

        // return view('user.pickerUser.edit', compact('offerdeal', 'pageTitle', 'route', 'role', 'suppliers',  'products', 'promoType', 'productsData'));
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

        $validation = $request->validate([
            'title' => 'required',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'gender' => 'required',
        ]);

        $route = $this->route;
        $redirectRoute = route("$route.index");
        $data = $request->except(['_method', '_token', 'save_exit', 'base_price', 'stock_expiry']);
        $usersData = User::where('uuid', $id)->update($data);

        return redirect("$redirectRoute")->with(['status' => 'success', 'message' => trans('success.admin|user|updated')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $route = $this->route;
        // if ($offer->canDelete()) {
        try {

            User::where('uuid', $id)->delete();
            return redirect(route("$route.index"))->with(['status' => 'success', 'message' => trans('success.admin|user|deleted')]);
        } catch (\Exception $exception) {
            return redirect(route("$route.index"))->with(['status' => 'warning', 'message' => 'User can not delete']);
        }
        // } else {
        //     return redirect(route("$route.index"))->with(['status' => 'warning', 'message' => trans('warning.supplier|offerDeals|deleteNotPossible')]);
        // }
    }
}
