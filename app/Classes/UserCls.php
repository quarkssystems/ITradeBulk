<?php



namespace App\Classes;



use App\Exceptions\CustomeException;

use App\Repositories\UserRepository;

use App\General\General;

use App\General\APIRequestValidate;

use App\User;

use App\Models\SalesOrder;

use App\Models\OrderstatusUpdate;

use App\Models\LogisticDetails;

use App\Models\VehicleCapacity;

use App\Models\DeliveryVehicleMaster;

use App\Models\Otpgenerate;

use App\Models\Basket;

use App\Models\SupplierItemInventory;

use Illuminate\Support\Facades\Auth;

use JWTFactory;

use JWTAuth;



class UserCls

{



    protected $userValidate;



    public function __construct(UserRepository $user)

    {

        $this->user = $user;

        $this->userValidate = new APIRequestValidate();

    }



    protected function respondWithToken($token)

    {

        return [

            'access_token' => $token,

            'token_type' => 'bearer',

            'expires_in' => auth('api')->factory()->getTTL() * 60

        ];

    }


    public function getBanner($postData)
    {
        try {
             $res = $this->user->getBanner($postData);
             return  $res;
        } catch (\Exception $e) {
            throw new CustomeException("Network error. Please try after some time.",500);
        }
    }

    public function getITZPaymentInstruction($postData)
    {
        try {
             $res = $this->user->getITZPaymentInstruction($postData);
             return  $res;
        } catch (\Exception $e) {
            throw new CustomeException("Network error. Please try after some time.",500);
        }
    }


     public function checkUserRequired($postData)

    {



         $v = $this->userValidate->required($postData, array('email', 'password', 'device_type', 'device_token', 'device_id'));



        if ($v->fails()) {



            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];



            return $data;

        }

    }



    public function checkUserStatus($postData)

    {

         return User::where('email',$postData['email'])->where('email_verified_at',NULL)->first();

    }



    public function login($postData)

    {

     



        try {

         $credentials = request(['email', 'password']);

            if (!$token = auth('api')->attempt($credentials)) {

                $data = General::setResponse('unauthorized');

                $data['response'] = [

                    'message' => "Wrong credentials",

                ];

                return $data;

            }

            $token = $this->respondWithToken($token);

            //if($token['status'] === 200){*/

                $user = User::where('email',$postData['email'])->first();

                $this->user->updateUserDeviceDetails(['uuid'=>$user->uuid,'device_id' => $postData['device_id'], 'device_token' => $postData['device_token'], 'device_type'=>$postData['device_type'] ,  'device_model'=>$postData['device_model'],'device_os'=>$postData['device_os'] ,'country'=>$postData['country']]);

           // }

            $data = General::setResponse('success');

            $data['response'] = $token;


            $data['response'] = [


                    'message' => "Registration successfull A verification email has been sent to your email.",
            ];

            return $data;



        } catch (\Exception $e) {
            dd("Test");
            return General::setResponse('other_error', $e->getMessage());

        }

    }



    public function addBank($postData) {

        $v = $this->userValidate->required($postData, array('name','short_name'));
        if ($v->fails()) {
            $data = General::setResponse('validation_error');
            $data['response'] = [
                'message' => $v->errors()->first(),
            ];
            return $data;
        }
        try {
            $result = $this->user->addBank($postData);
            if ($result != '') {
                $data = General::setResponse('success');
                $data['response'] = [
                    'message' => "Bank Added successfully.",
                ];
                return $data;
            } else {
                return General::setResponse('other_error', "Please try after sometime.");
            }
        } catch (\Exception $e) {
            throw new CustomeException("Network error. Please try after some time.",500);
        }

    }

    public function addBankBranch($postData) {

        $v = $this->userValidate->required($postData, array('bank_master_id','branch_name','branch_code','swift_code','address1','address2','country_id','state_id','city_id','zipcode_id'));
        if ($v->fails()) {
            $data = General::setResponse('validation_error');
            $data['response'] = [
                'message' => $v->errors()->first(),
            ];
            return $data;
        }
        try {
            $result = $this->user->addBankBranch($postData);
            if ($result != '') {
                $data = General::setResponse('success');
                $data['response'] = [
                    'message' => "Bank Branch Added successfully.",
                ];
                return $data;
            } else {
                return General::setResponse('other_error', "Please try after sometime.");
            }
        } catch (\Exception $e) {
            throw new CustomeException("Network error. Please try after some time.",500);
        }

    }



    public function signup($postData)

    {

        if($postData['user_type'] == 'DRIVER') {

           

            $error = $this->validateDriverRegisterObject($postData);

            if ($error) {

                return $error;

            }

            //$postData['userDetail'] = $postData['logisticDetails'];

            $postData['userDetail'] = array();

        }else if($postData['user_type'] == 'COMPANY') {

          

            $error = $this->validateCompanyRegisterObject($postData);

            if ($error) {

                return $error;

            }

            //$postData['userDetail'] = $postData['logisticDetails'];

            $postData['userDetail'] = array();

        }else if($postData['user_type'] == 'VENDOR' || $postData['user_type'] == 'SUPPLIER'){

            $error = $this->validateVendorOrSupplierRegisterObject($postData);

            if ($error) {

                return $error;

            }

           $postData['userDetail'] = $postData['company'];

        }

        try {

            $userData = $this->user->register($postData);
            
            if ($userData->id > 0) { 

                return $this->login(['email' => $postData['email'], 'password' => $postData['password'], 'device_id' => $postData['device_id'], 'device_token' => $postData['device_token'], 'device_type' => $postData['device_type'], 'device_model' => $postData['device_model'] , 'device_os' => $postData['device_os'],'country' =>$postData['country'] ]);

            } else {

                return General::setResponse('other_error', $userData['error']);

            }

        } catch (\Exception $e) {

            return General::setResponse('unauthorized', $e->getMessage());

        }

    }



    public function validateVendorOrSupplierRegisterObject(array $postData)

    {

        if (!array_key_exists("company", $postData)) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => "Company Detail is Required.",

            ];

            return $data;

        }



        $v = $this->userValidate->required($postData, array(

            'device_type','device_id','device_token','title', 'first_name', 'last_name', 'email', 'password', 'gender', 'country_id', 'state_id', 'city_id', 'zipcode_id', 'latitude', 'longitude'

        ));

        if ($v->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];

            return $data;

        }

        $v1 = $this->userValidate->email($postData, array('email'));

        if ($v1->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v1->errors()->first(),

            ];

            return $data;

        }

        $v2 = $this->userValidate->same($postData, 'password', 'password_confirmation');

        if ($v2->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v2->errors()->first(),

            ];

            return $data;

        }

        $v3 = $this->userValidate->unique($postData, 'email');

        if ($v3->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => "The email already exists."

            ];

            return $data;

        }

        $v4 = $this->userValidate->required($postData['company'], array(

            'legal_name','trading_name','business_type','founding_year','representative_first_name','representative_last_name','email','phone','address1','product_service_offered'

        ));

        if ($v4->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v4->errors()->first(),

            ];

            return $data;

        }

        return false;

    }



    public function validateDriverRegisterObject(array $postData)

    {

       /* if (!array_key_exists("logisticDetails", $postData)) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => "Logistic Detail is Required.",

            ];

            return $data;

        }*/



       /* $v = $this->userValidate->required($postData, array(

            'device_id','device_token','device_type','title', 'first_name', 'last_name', 'email', 'password', 'gender', 'logistic_type', 'country_id', 'state_id', 'city_id', 'zipcode_id', 'latitude', 'longitude'

        ));*/



        $v = $this->userValidate->required($postData, array(

            'device_id','device_token','device_type','title', 'first_name', 'last_name', 'email', 'password', 'gender', 'logistic_type', 'latitude', 'longitude'

        ));



        if ($v->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];

            return $data;

        }

        $v1 = $this->userValidate->email($postData, array('email'));

        if ($v1->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v1->errors()->first(),

            ];

            return $data;

        }

        $v2 = $this->userValidate->same($postData, 'password', 'password_confirmation');

        if ($v2->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v2->errors()->first(),

            ];

            return $data;

        }

        $v3 = $this->userValidate->unique($postData, 'email');

        if ($v3->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => "The email already exists."

            ];

            return $data;

        }



       /* $v4 = $this->userValidate->required($postData['logisticDetails'], array(

            'phone', 'driving_licence', 'transport_type', 'transport_capacity', 'availability', 'work_type', 'pallets_available', 'pallets_required', 'pallets_deposit', 'address1'

        ));

        if ($v4->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v4->errors()->first(),

            ];

            return $data;

        }*/



        return false;

    }



     public function validateCompanyRegisterObject(array $postData)

    {





        $v = $this->userValidate->required($postData, array('title', 'first_name', 'last_name', 'email', 'password', 'gender', 'logistic_type','transporter_name', 'latitude', 'longitude'

        ));

        //

        if ($v->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];

            return $data;

        }

        $v1 = $this->userValidate->email($postData, array('email'));

        if ($v1->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v1->errors()->first(),

            ];

            return $data;

        }

        $v2 = $this->userValidate->same($postData, 'password', 'password_confirmation');

        if ($v2->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v2->errors()->first(),

            ];

            return $data;

        }

        $v3 = $this->userValidate->unique($postData, 'email');

        if ($v3->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => "The email already exists."

            ];

            return $data;

        }



       

        return false;

    }



    public

    function changePassword($postData)

    {

        $v = $this->userValidate->required($postData, array('old_password', 'new_password', 'confirm_password'));

        if ($v->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];

            return $data;

        }

        $v1 = $this->userValidate->same($postData, 'new_password', 'confirm_password');

        if ($v1->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v1->errors()->first(),

            ];

            return $data;

        }

        $credentials['email'] = Auth::user()->email;

        $credentials['password'] = $postData['old_password'];

        if (!$token = auth('api')->attempt($credentials)) {

            $data = General::setResponse('unauthorized');

            $data['response'] = [

                'message' => "Current password is wrong.",

            ];

            return $data;

        }



        try {

            $userData = $this->user->changePassword($postData);

            if (isset($userData['error'])) {

                return General::setResponse('other_error', $userData['error']);

            } else if (isset($userData['success'])) {

                return General::setResponse('success', $userData['success']);

            }

        } catch (\Exception $e) {

            return General::setResponse('other_error', $e->getMessage());

        }

    }



    public

    function forgotPassword($postData)

    {

        $v = $this->userValidate->required($postData, array('email'));



        if ($v->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];

            return $data;

            //return General::setResponse('validation_error', $v->errors()->getMessages());

        }



        $v1 = $this->userValidate->email($postData, array('email'));

        if ($v1->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v1->errors()->first(),

            ];

            return $data;

        }



        try {

            return $this->user->forgotPassword($postData);

        } catch (\Exception $e) {

            throw new CustomeException("Network error. Please try after some time.",500);

        }

    }



    public

    function updateToken($postData)

    {



        $v = $this->userValidate->required($postData, array('device_id', 'device_token', 'device_type', 'login_user_id'));



        if ($v->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];

            return $data;

        }



        try {

            $deviceUpdate = $this->user->updateUserDeviceDetails($postData);

            if ($deviceUpdate != '') {

                $data = General::setResponse('success');

                $data['response'] = [

                    'message' => "Device token updated successfully.",

                ];

                return $data;

            } else {

                return General::setResponse('other_error', "Please try after sometime.");

            }

        } catch (\Exception $e) {

            return General::setResponse('unauthorized', $e->getMessage());

        }

    }



    /* 

    Basic Detail update param :user_id, title', 'first_name', 'last_name', 'email', 'gender', 'logistic_type', 'country_id', 'state_id', 'city_id', 'zipcode_id', 'latitude', 'longitude'

    */

    public

    function updateUserDetail($postData)

    {

        if(isset($postData['logistic_type'])  && $postData['logistic_type'] == 'COMPANY')

         {

             $v = $this->userValidate->required($postData, array('user_uuid','title', 'first_name', 'last_name',  'gender', 'latitude', 'longitude' ,'transporter_name'

             ));

         }

         elseif(isset($postData['logistic_type'])  && $postData['logistic_type'] == 'DRIVER')

         {



             $v = $this->userValidate->required($postData, array('user_uuid','title', 'first_name', 'last_name',  'gender', 'latitude', 'longitude' ,'logistic_company_id'

             ));



         }

         else

         {

               $v = $this->userValidate->required($postData, array('user_uuid','title', 'first_name', 'last_name',  'gender', 'latitude', 'longitude'

            ));

         } 



     

        if ($v->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];

            return $data;

        }

        

        try {

            $deviceUpdate = $this->user->updateUser($postData);

            if ($deviceUpdate != '') {

                $data = General::setResponse('success');

                $data['response'] = [

                    'message' => "User Basic Detail updated successfully.",

                ];

                return $data;

            } else {

                return General::setResponse('other_error', "Please try after sometime.");

            }

        } catch (\Exception $e) {

            return General::setResponse('unauthorized', $e->getMessage());

        }

    }





    /* 

     @updateCompanyDetail  update param :user_id, title', 'first_name', 'last_name', 'email', 'gender', 'logistic_type', 'country_id', 'state_id', 'city_id', 'zipcode_id', 'latitude', 'longitude'

    */

    public

    function updateCompanyDetail($postData)

    {

         $v = $this->userValidate->required($postData, array('user_uuid','legal_name', 'trading_name', 'business_type',  'founding_year', 'representative_first_name', 'representative_last_name' , 'email','phone', 'address1', 'address2','product_service_offered'

        ));

        if ($v->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];

            return $data;

        }

        

        try {

            $deviceUpdate = $this->user->updateUserCompany($postData);

            if ($deviceUpdate != '') {

                $data = General::setResponse('success');

                $data['response'] = [

                    'message' => "User Company Detail updated successfully.",

                ];

                return $data;

            } else {

                return General::setResponse('other_error', "Please try after sometime.");

            }

        } catch (\Exception $e) {

            return General::setResponse('unauthorized', $e->getMessage());

        }

    }





    public

    function companyDriverDetails($postData)

    {

        if(isset($postData['user_uuid'])){

            

            $v = $this->userValidate->required($postData, array('user_uuid'));

            if ($v->fails()) {

                $data = General::setResponse('validation_error');

                $data['response'] = [

                    'message' => $v->errors()->first(),

                ];

                return $data;

            }



              $error = $this->validateCompanyDriverRegisterObject($postData);

            if ($error) {

                return $error;

            }



            $msg ="Driver updated successfully.";

        }

        else

        {



            $error = $this->validateCompanyDriverRegisterObject($postData);

            if ($error) {

                return $error;

            }

             $msg ="Driver Added successfully.";

        } 





        try {

            $deviceUpdate = $this->user->companyDriverDetails($postData);

            if ($deviceUpdate != '') {

                $data = General::setResponse('success');

                $data['response'] = [

                    'message' => $msg,

                ];

                return $data;

            } else {

                return General::setResponse('other_error', "Please try after sometime.");

            }

        } catch (\Exception $e) {

            return General::setResponse('unauthorized', $e->getMessage());

        }

    }





 public function validateCompanyDriverRegisterObject(array $postData)

    {



         if(!isset($postData['user_uuid'])){



             $v = $this->userValidate->required($postData, array('title', 'first_name', 'last_name', 'email', 'password', 'gender', 'logistic_type','logistic_company_id','transporter_name'

            ));

        }else{





                 $v = $this->userValidate->required($postData, array('title', 'first_name', 'last_name', 'email', 'password', 'gender', 'logistic_type','logistic_company_id','transporter_name','latitude','longitude','address1','address2','country_id','state_id','city_id','zipcode_id'

            ));

  

        }



        //

        if ($v->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];

            return $data;

        }

        if(!isset($postData['user_uuid'])){

            $v1 = $this->userValidate->email($postData, array('email'));

            if ($v1->fails()) {

                $data = General::setResponse('validation_error');

                $data['response'] = [

                    'message' => $v1->errors()->first(),

                ];

                return $data;

            }



            $v2 = $this->userValidate->same($postData, 'password', 'password_confirmation');

            if ($v2->fails()) {

                $data = General::setResponse('validation_error');

                $data['response'] = [

                    'message' => $v2->errors()->first(),

                ];

                return $data;

            }



       }

       if(isset($postData['user_uuid'])){

           $postData = array_merge($postData,array('user_id' => $postData['user_uuid']));

       }

       $v3 = $this->userValidate->unique($postData, 'email');

            if ($v3->fails()) {

                $data = General::setResponse('validation_error');

                $data['response'] = [

                    'message' => "The email already exists."

                ];

                return $data;

            }



        return false;

    }

     public

    function updateBankDetail($postData)

    {


        // print_r($postData);die();
        

         $v = $this->userValidate->required($postData, array('user_uuid','bank_account_name','bank_account_number', 'bank_account_type', 'bank_id',  'bank_branch_id', 'bank_branch_province', 'bank_branch_city', 'bank_branch_postal'

        ));

        if ($v->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];

            return $data;

        }

        

        try {

            $deviceUpdate = $this->user->updateUserBankDetail($postData);

            if ($deviceUpdate != '') {

                $data = General::setResponse('success');

                $data['response'] = [

                    'message' => "User Bank Detail updated successfully.",

                ];

                return $data;

            } else {

                return General::setResponse('other_error', "Please try after sometime.");

            }

        } catch (\Exception $e) {

            return General::setResponse('unauthorized', $e->getMessage());

        }

    }







     public

    function updateDocument($postData)

    {

        $v = $this->userValidate->required($postData, array('user_uuid','user_type'));

        if ($v->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];

            return $data;

        }

        $req = $postData;

        unset($req['user_type']);

        unset($req['user_uuid']);



        $v1 = $this->userValidate->image($req, $req);

        if ($v1->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v1->errors()->first(),

            ];

            return $data;

        }

     

        try {

          $docUpdate = $this->user->updateUserDocument($postData);

            if ($docUpdate != '') {

                $data = General::setResponse('success');

                $data['response'] = [

                    'message' => "User Document updated successfully.",

                ];

                return $data;

            } else {

                return General::setResponse('other_error', "Please try after sometime.");

            }

        } catch (\Exception $e) {

            return General::setResponse('unauthorized', $e->getMessage());

        }

    }





     public

    function removeDocument($postData)

    {

        $v = $this->userValidate->required($postData, array('user_uuid'));

        if ($v->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];

            return $data;

        }



        try {

          $docUpdate = $this->user->removeUserDocument($postData);

            if ($docUpdate != '') {

                $data = General::setResponse('success');

                $data['response'] = [

                    'message' => "User Document Remove successfully.",

                      

                ];

                return $data;

            } else {

                return General::setResponse('other_error', "something went wrong.");

            }

        } catch (\Exception $e) {

            return General::setResponse('unauthorized', $e->getMessage());

        }

    }



      public

    function updateTax($postData)

    {

        

        $v = $this->userValidate->required($postData, array('user_uuid','tax_number','vat_number', 'passport_number', 'passport_document_file',  'verify_tax_details'

        ));

        if ($v->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];

            return $data;

        }

        

        try {

            $deviceUpdate = $this->user->updateUserTaxDetail($postData);

            if ($deviceUpdate != '') {

                $data = General::setResponse('success');

                $data['response'] = [

                    'message' => "User TAX Detail updated successfully.",

                ];

                return $data;

            } else {

                return General::setResponse('other_error', "Please try after sometime.");

            }

        } catch (\Exception $e) {

            return General::setResponse('unauthorized', $e->getMessage());

        }

    }





      public

    function updateDriverVehicle($postData)

    {



        $v = $this->userValidate->required($postData, array('user_uuid','phone','transport_type','vehicle_type' ,'transport_capacity',  'pallet_capacity_standard','availability','work_type'

        ));

        if ($v->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];

            return $data;

        }

        

        try {

            if(isset($postData['address1'])){   unset($postData['address1']); }

            if(isset($postData['address2'])){   unset($postData['address2']); }

            if(isset($postData['country_id'])){ unset($postData['country_id']); }

            if(isset($postData['state_id'])){   unset($postData['state_id']); }

            if(isset($postData['city_id'])){    unset($postData['city_id']); }

            if(isset($postData['zipcode_id'])){ unset($postData['zipcode_id']); }

          



            $deviceUpdate = $this->user->updateDriverVehicle($postData);

            if ($deviceUpdate != '') {

                $data = General::setResponse('success');

                $data['response'] = [

                    'message' => "User Vehicle Detail updated successfully.",

                ];

                return $data;

            } else {

                return General::setResponse('other_error', "Please try after sometime.");

            }

        } catch (\Exception $e) {

            return General::setResponse('unauthorized', $e->getMessage());

        }

    }







    public

    function getDriverVehicle($postData)

    {

         $v = $this->userValidate->required($postData, array('user_uuid'));



        if ($v->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];

            return $data;

        }



        $user_id = $postData['user_uuid'];

        $logistic_details = new LogisticDetails;

        $bodymodel = new VehicleCapacity;  

        $vehiclemodel = new DeliveryVehicleMaster;



        $driver_details = $logistic_details->where('user_id',$user_id)->get();

        

        $driver_vehicle_details = array();    



        foreach ($driver_details as $key => $value) {



           $driver_vehicle_details['user_id'] =  $value->user_id;

           $driver_vehicle_details['phone'] =  $value->phone;

           $driver_vehicle_details['driving_licence'] =  $value->driving_licence;

           $driver_vehicle_details['work_type'] =  $value->work_type;



           $driver_vehicle_details['availability'] =  $value->availability;

           $driver_vehicle_details['vehicle_color'] =  $value->vehicle_color;

           $driver_vehicle_details['vin_number'] =  $value->vin_number;

           $driver_vehicle_details['vehicle_model'] =  $value->vehicle_model;

           $driver_vehicle_details['vehicle_registration_number'] =  $value->vehicle_registration_number;

           $driver_vehicle_details['vehicle_make'] =  $value->vehicle_make;



           $driver_vehicle_details['address1'] =  $value->address1;

           $driver_vehicle_details['address2'] =  $value->address2;



           $driver_vehicle_details['transport_type'] =  $value->transport_type;

           if($value->transport_type == 'Truck'){

                // bodyType

                $body_details = $bodymodel->where('uuid',$value->vehicle_capacity_id)->pluck('name', 'uuid');

                foreach ($body_details as $key_body => $value_body) {

                    # code...

                   

                    $driver_vehicle_details['bodytype'] = array( "uuid" =>$key_body ,  "Title" => $value_body);

                }



                $vehiclecapacity = $vehiclemodel->where('vehicle_capacity_id',$value->vehicle_capacity_id)->pluck('vehicle_type', 'uuid');

              

                foreach ($vehiclecapacity as $ckey => $cvalue) {

                    # code...

                    $driver_vehicle_details['vehiclecapacity'] = array( "uuid" =>$ckey ,  "vehicle_type" => $value->vehicle_type , "capacity" => $value->transport_capacity , 'pallet_capacity_standard' => $value->pallet_capacity_standard );

                }



           }

           else

           {

                 $driver_vehicle_details['vehiclecapacity'] = array( "vehicle_type" => $value->vehicle_type , "capacity" => $value->transport_capacity , 'pallet_capacity_standard' => $value->pallet_capacity_standard );

                

           }



        }

        

        if(empty($driver_vehicle_details))

        {

            $data['response'] = [];

        }

        else

        {

            $data['response'] = $driver_vehicle_details;        }    

        

        return $data;

    }



    public

    function getTransport($postData)

    {

        

        $logistic_details = new LogisticDetails;

        $bodymodel = new VehicleCapacity;  

        $vehiclemodel = new DeliveryVehicleMaster;



        $trasporttype = $vehiclemodel->getTransportTypesDropDown();

        $driver_vehicle_details = array();    



        foreach ($trasporttype as $key => $value) {



           $driver_vehicle_details[$value]['name'] =  $value;



           if($value == 'Truck'){

                // bodyType

                $body_details = $bodymodel->pluck('name', 'uuid');

                foreach ($body_details as $key_body => $value_body) {

                    # code...

                    $bodytype_uuid = $key_body;

                    $driver_vehicle_details[$value]['bodytype'][] = array( "uuid" =>$key_body ,  "Title" => $value_body);

                }

            }

                

            $vehiclecapacity = $vehiclemodel->where('transport_type',$value)->select('vehicle_type','capacity','pallet_capacity_standard','vehicle_capacity_id' ,'uuid')->get();

            foreach ($vehiclecapacity as $ckey => $cvalue) {

                    # code...

                    $driver_vehicle_details[$value]['vehiclecapacity'][] = array( "uuid" =>$cvalue->uuid,  "vehicle_type" => $cvalue->vehicle_type , "capacity" => $cvalue->capacity , 'pallet_capacity_standard' => $cvalue->pallet_capacity_standard  ,'bodytype_uuid' =>$cvalue->vehicle_capacity_id);

            }



        }

        

        if(empty($driver_vehicle_details))

        {

            $data = [];

        }

        else

        {

            $data[]= $driver_vehicle_details;      

        }    

        

        return $data;

       

    }

    



    public

    function getTruckBodyType()

    {



        $bodymodel = new VehicleCapacity;   

        $bodyType = array();

       

        $body_type = $bodymodel->getDropDown();

       

        foreach ($body_type as $key => $value) {

            # code...

            $bodyType[]= array( "uuid" =>$key ,  "Title" => $value);

        }

        return $bodyType;

     }

   



    public

    function getWorktype()

    {

        $logistic_details = new LogisticDetails;     

        $work_type = $logistic_details->getWorkType();

        $availability = $logistic_details->getAvailabilityTypes();

       

        $worktype =array();

        

        $worktype["worktype"] = $work_type;

        $worktype["availability"] = $availability;



        return $worktype;

    }









    public

    function getAvailability()

    {

        $logistic_details = new LogisticDetails;     

        $availability = $logistic_details->getAvailabilityTypesDropDown();

       

        $availability_array =array();

       

        foreach ($availability as $key => $value) {

            # code...

            $availability_array[]= array($value => $value);

        }

       

       

        return $availability_array;

    }   

    public

    function fetchOrders($postData)

    {

        $offset = isset($postData['start_offset']) ? $postData['start_offset'] : 0;

        $limit = isset($postData['limit']) ? $postData['limit'] : 10;



        $v = $this->userValidate->required($postData, array('user_uuid'));



        if ($v->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];

            return $data;

        }



        $salesmodel = new SalesOrder();

        $orderstatus_model = new OrderstatusUpdate();

        $order_array = array(); 

        $order_status = array(); 

        





        $orders = $salesmodel->where('supplier_id',$postData['user_uuid'])->orderBy('created_at', 'desc')->skip($offset)->take($limit)->get();

        foreach($orders as  $key => $order ) {

            $k = 0;

             $order_status = array(); 

            $order_status_data = $orderstatus_model->where('sales_id',$order->uuid)->orderBy('created_at', 'asc')->get();

               foreach ($order_status_data as  $value) {

                   

                    $order_status[$k]['status'] = $value->order_status;

                    $order_status[$k]['date'] = $value->created_at->format('Y-m-d H:i:s');  

                    $k++;   

                }



            $order_array[$key]['order_uuid'] =  $order->uuid;

            $order_array[$key]['order_number'] =  $order->order_number;

            $order_array[$key]['supplier_id'] =  $order->supplier_id;

            $order_array[$key]['user_name'] =  $order->user_name;

            $order_array[$key]['total_price'] =  $order->final_total;

            $order_array[$key]['order_status'] =  $order->order_status;

            $order_array[$key]['payment_status'] =  $order->payment_status;

            $order_array[$key]['order_date'] =  $order->created_at->format("Y-m-d H:i:s");

            $order_array[$key]['order_history']=  $order_status;

        }

        if(empty($order_array))

        {

            $data['response'] = [];

        }

        else

        {

            $data['response'] = $order_array;

        }    

        return $data;

    }



    public

    function fetchOrdersForVender($postData)

    {



        $v = $this->userValidate->required($postData, array('user_uuid'));



        if ($v->fails()) {

            $data = General::setResponse('validation_error');

          

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];

            return $data;

        }



        $salesmodel = new SalesOrder();

        $orderstatus_model = new OrderstatusUpdate();

        $order_array = array(); 

        $order_status = array(); 

        $orders = $salesmodel->where('user_id',$postData['user_uuid'])->orderBy('created_at', 'desc')->get();

        



        foreach($orders as  $key => $order ) {



           $k = 0;

            $order_status = array(); 

            $order_status_data = $orderstatus_model->where('sales_id',$order->uuid)->orderBy('created_at', 'asc')->get();

               foreach ($order_status_data as  $value) {

                   

                    $order_status[$k]['status'] = $value->order_status;

                    $order_status[$k]['date'] = $value->created_at->format('Y-m-d H:i:s');  

                    $k++;   

                }

            $order_array[$key]['order_uuid'] =  $order->uuid;

            $order_array[$key]['order_number'] =  $order->order_number;

            $order_array[$key]['user_id'] =  $order->supplier_id;

            $order_array[$key]['supplier_name'] =  $order->supplier_name;

            $order_array[$key]['total_price'] =  $order->final_total;

            $order_array[$key]['order_status'] =  $order->order_status;

            $order_array[$key]['payment_status'] =  $order->payment_status;

            $order_array[$key]['order_date'] =  $order->created_at->format("Y-m-d H:i:s");

             $order_array[$key]['order_history']=  $order_status;

        }

        if(empty($order_array))

        {

            $data['response'] = [];

        }

        else

        {

            $data['response'] = $order_array;

        }    

        return $data;

    }





    public

    function fetchOrderDetail($postData)

    {



        $v = $this->userValidate->required($postData, array('order_uuid'));



        if ($v->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];

            return $data;

        }

         $orderstatus_model = new OrderstatusUpdate();

        $salesModel = new SalesOrder();

        $basketModel = new Basket();

        $supplierItemInventoryModel = new SupplierItemInventory(); 

        

        $order_array = array(); 

        $order_status =array();

        $orders = $salesModel->where('uuid',$postData['order_uuid'])->orderBy('created_at', 'desc')->get();

        

        //$order_status = array('PACKED' => , ); 

        

        foreach($orders as  $key => $order ) {

             $order_status = array(); 

            $k = 0;

            $order_status_data = $orderstatus_model->where('sales_id',$order->uuid)->orderBy('created_at', 'asc')->get();

               foreach ($order_status_data as  $value) {

                   

                    $order_status[$k]['status'] = $value->order_status;

                    $order_status[$k]['date'] = $value->created_at->format('Y-m-d H:i:s');  

                    $k++;   

                }

        

            $basket = $basketModel->where('order_id', $order->uuid)->first();

            $products_data = $basket->products;

            

            $order_array['order_uuid'] =  $order->uuid;

            $order_array['order_number'] =  $order->order_number;

            $order_array['user_id'] =  $order->supplier_id;

            $order_array['order_number'] =  $order->order_number;

            $order_array['user_name'] =  $order->user_name;

            $order_array['supplier_name'] =  $order->supplier_name;

            $order_array['cart_amount'] =  $order->cart_amount ? $order->cart_amount : 0;

            $order_array['shipment_amount'] =  $order->shipment_amount ? $order->shipment_amount : 0;

            $order_array['discount_amount'] =  $order->discount_amount ? $order->discount_amount : 0;

            $order_array['tax_amount'] =   $order->tax_amount ? $order->tax_amount : 0;

            $order_array['total_price'] =  $order->final_total ? $order->final_total : 0;

            $order_array['total_price'] =  $order->final_total ? $order->final_total : 0;

            $order_array['order_status'] =  $order->order_status;

            $order_array['payment_status'] =  $order->payment_status;

            $order_array['order_date'] =  $order->created_at->format("Y-m-d H:i:s");

            $order_array['delivery_type']=  $order->delivery_type;

            $order_array['order_history']=  $order_status;// array_merge($order_history  ,$orderstatus) ;  

            $order_array['products'] = array();

            $k=0;

            foreach($products_data as $cartProduct){

                    

                $order_array['products'][$k]['product_uuid'] = $cartProduct->product_id; 

                $order_array['products'][$k]['product_image'] = url('/').$cartProduct->product->base_image;

                $order_array['products'][$k]['name'] = $cartProduct->product->name; 

                $order_array['products'][$k]['qty']= $cartProduct->single_qty; 

              

               $supplierLatestRate = $supplierItemInventoryModel->where('product_id', $cartProduct->product_id)->where('user_id', $order->supplier_id)->orderBy('id', 'DESC')->first();

                $order_array['products'][$k]['price']  = $supplierLatestRate->single_price;

                $order_array['products'][$k]['product_price']  = $supplierLatestRate->single_price * $cartProduct->single_qty; 

                  $k++; 

            }

            

        }

        if(empty($order_array))

        {

            $data['response'] = [

                'message' => "result Not Found",

            ];

        }

        else

        {

            $data['response'] = $order_array;

        }    

        return $data;

    }









     public

    function getCompanyDriverlist($postData)

    {

         $v = $this->userValidate->required($postData, array('user_uuid'));



        if ($v->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];

            return $data;

        }

           

         $result = array();

         $result1 =  array();

         $usercomDB = new User;

        $uuid = $postData['user_uuid']; 

        if($usercomDB->where('logistic_company_id',$uuid)->count() > 0 ){

            $result1 = $usercomDB->where('logistic_company_id',$uuid)->with('LogisticDetails')->get()->toArray();

            

           foreach ($result1 as $key => $value) {

                    if($value['image'] != '')

                    {

                       $result1[$key]['image'] = $value['image']; 

                    }

                # code...

            }

        }

       

        if(empty($result1))

        {

           $result['status'] ="false";

           $result['data'] = [];

        }

        else

        {

            $result['status'] ="true";

            $result['data'] = $result1;

              

        }    

        

        

        return $result;

          

    }



     public

    function getCompanyDriverDetail($postData)

    {

         $v = $this->userValidate->required($postData, array('user_uuid'));



        if ($v->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];

            return $data;

        }

           

         $result1 = array();

         $usercomDB = new User;

        $uuid = $postData['user_uuid']; 

        if($usercomDB->where('uuid',$uuid)->count() > 0 ){

            $result = $usercomDB->where('uuid',$uuid)->with('LogisticDetails')->first()->toArray(); 

        }

       

        if(empty($result))

        {

           $result1['status'] ="false";

           $result1['data'] = [];

        }

        else

        {

            $result1['status'] ="true";

            $result1['data'] = $result;

              

        }    

        

        return $result1;

          

    }

    public

    function getCompanyDetail($uuid)

    {

           

     return $this->user->getCompanyDetail($uuid);

           

    }



     public

    function getBankDetail($uuid)

    {

           

     return $this->user->getBankDetail($uuid);

           

    }

    



     public

    function getDocDetail($uuid,$role)

    {

           

     return $this->user->getDocDetail($uuid,$role);

           

    }



     public

    function getAddressDetail($uuid) //Driver only

    {

           

     return $this->user->getAddressDetail($uuid);

           

    }



     public

    function getTaxDetail($uuid)

    {

           

     return $this->user->getTaxDetail($uuid);

           

    }



    public

    function updateOrderStatus($postData)

    {



        $v = $this->userValidate->required($postData, array('order_uuid','order_status'));



        if ($v->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];

            return $data;

        }

        try {



             $orderModel = new SalesOrder; 

             $sales_id = $postData['order_uuid'];

             $orderData = $orderModel->where('uuid',$sales_id)->first();

            if($orderData->order_status != SalesOrder::ORDERPLACED && $postData['order_status'] =='CANCELLED' ) { 
                // if($orderData->order_status != "PLACED" && $postData['order_status'] =='CANCELLED' ) { 



                  $data['status'] = 'false';

                  $data['response'] = [

                    'message' => 'You can not cancel Order. Currently Order status  is '.$orderData->order_status ,

                  ];

                  return $data;



             }

            $orderUpdate = $this->user->updateOrderStatus($postData);



            if ($orderUpdate != '') {

                $data = General::setResponse('success');

                $data['response'] = [

                    'message' => "Order status  updated successfully.",

                ];

                return $data;



            } else {

                return General::setResponse('other_error', "Please try after sometime.");

            }

        } catch (\Exception $e) {

            return General::setResponse('unauthorized', $e->getMessage());

        }

    }





     public

    function packedOrder($postData)

    {



        $v = $this->userValidate->required($postData, array('order_uuid','supplier_id','vendor_id'));



        if ($v->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];

            return $data;

        }

        try {



            $orderUpdate = $this->user->packedOrder($postData);



            if ($orderUpdate != '') {

                $data = General::setResponse('success');

                $data['response'] = [

                    'message' => "notification to supplier and vendor successfully.",

                ];

                return $data;



            } else {

                return General::setResponse('other_error', "Please try after sometime.");

            }

        } catch (\Exception $e) {

            return General::setResponse('unauthorized', $e->getMessage());

        }

    }





     public

    function deliveryOrderPacked($postData)

    {



        $v = $this->userValidate->required($postData, array('order_uuid','supplier_id','vendor_id'));



        if ($v->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];

            return $data;

        }

        try {



            $orderUpdate = $this->user->deliveryOrderPacked($postData);



            if ($orderUpdate != '') {

                $data = General::setResponse('success');

                $data['response'] = [

                    'message' => "notification to driver for accept and reject.",

                ];

                return $data;



            } else {

                return General::setResponse('other_error', "Please try after sometime.");

            }

        } catch (\Exception $e) {

            return General::setResponse('unauthorized', $e->getMessage());

        }

    }





     public

    function verifyOTP($postData)

    {

         

        $v = $this->userValidate->required($postData, array('order_uuid','sender_id','receiver_id','otp'));



        if ($v->fails()) {

            $data = General::setResponse('validation_error');

            $data['status'] = 'false'; 

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];

            

            return $data;

        }

        try {



            $otpmodel = new Otpgenerate;    

            $order = $otpmodel->where('sales_id',$postData['order_uuid'])->where('otp',$postData['otp'] )->count();

          

            if ($order > 0) {

                $data = General::setResponse('success');

                $data['status'] = 'true'; 

           

                $data['response'] = [

                    'message' => "OTP Match",

                ];

                return $data;



            } else {

 

                $data = General::setResponse('other_error');

                $data['status'] = 'false'; 

           

                $data['response'] = [

                    'message' => "OTP Not Match",

                ];

                return $data;



            }

        } catch (\Exception $e) {

            return General::setResponse('unauthorized', $e->getMessage());

        }

    }



    public

    function acceptOrderDriver($postData)

    {



        $v = $this->userValidate->required($postData, array('order_uuid','driver_id'));



        if ($v->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];

            return $data;

        }

        

         try {



            return $orderUpdate = $this->user->acceptOrderDriver($postData);



        } catch (\Exception $e) {

            return General::setResponse('unauthorized', $e->getMessage());

        }

    }



    public

    function rejectOrderDriver($postData)

    {



        $v = $this->userValidate->required($postData, array('order_uuid','driver_id'));



        if ($v->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];

            return $data;

        }

        

         try {



            return $orderUpdate = $this->user->rejectOrderDriver($postData);



        } catch (\Exception $e) {

            return General::setResponse('unauthorized', $e->getMessage());

        }

    }



     public

    function getNotificationData($postData)

    {



        $v = $this->userValidate->required($postData, array('user_uuid'));



        if ($v->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];

            return $data;

        }

        

         try {



            return $orderUpdate = $this->user->getNotificationData($postData);



        } catch (\Exception $e) {

            return General::setResponse('unauthorized', $e->getMessage());

        }

    }





     public

    function ReadNotificationData($postData)

    {



        $v = $this->userValidate->required($postData, array('user_uuid','notification_uuid'));



        if ($v->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];

            return $data;

        }

        

         try {



             $notificationUpdate = $this->user->ReadNotificationData($postData);

             if ($notificationUpdate != '') {

                $data = General::setResponse('success');

                $data['status'] = 'true';

                $data['response'] = [

                    'message' => "Notifications READ successfully.",

                ];

                return $data;

            } else {

                return General::setResponse('other_error', "Please try after sometime.");

            }

        } catch (\Exception $e) {

            return General::setResponse('unauthorized', $e->getMessage());

        }

    }

    public function markAllAsRead($postData)
    {
        $v = $this->userValidate->required($postData, array('user_uuid'));
        if ($v->fails()) {
            $data = General::setResponse('validation_error');
            $data['response'] = [
                'message' => $v->errors()->first(),
            ];
            return $data;
        }
        try {
            $notificationUpdate = $this->user->markAllAsRead($postData);
            if ($notificationUpdate != '') {
                $data = General::setResponse('success');
                $data['status'] = 'true';
                $data['response'] = [
                    'message' => "Notifications READ successfully.",
                ];
                return $data;
            } else {
                return General::setResponse('other_error', "Please try after sometime.");
            }
        } catch (\Exception $e) {
            return General::setResponse('unauthorized', $e->getMessage());
        }
    }

    public

    function uploadUserPhoto($postData)

    {

         $v = $this->userValidate->required($postData, array('user_uuid','image'));

        if ($v->fails()) {

            $data = General::setResponse('validation_error');

            $data['status'] = 'false';

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];

            return $data;

        }

        

        try {

            $deviceUpdate = $this->user->uploadUserPhoto($postData);

            if ($deviceUpdate != '') {

                $data = General::setResponse('success');

                $data['status'] = 'true';

                $data['response'] = [

                    'message' => "User photo updated successfully.",

                ];

                return $data;

            } else {

                return General::setResponse('other_error', "Please try after sometime.");

            }

        } catch (\Exception $e) {

            return General::setResponse('unauthorized', $e->getMessage());

        }

    }



        public

    function RemoveUserPhoto($postData)

    {

         $v = $this->userValidate->required($postData, array('user_uuid'));

        if ($v->fails()) {

            $data = General::setResponse('validation_error');

            //$data['status'] = 'false';

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];

            return $data;

        }

        

        try {

            $deviceUpdate = $this->user->RemoveUserPhoto($postData);

            if ($deviceUpdate != '') {

                $data = General::setResponse('success');

               // $data['status'] = 'true';

                $data['response'] = [

                    'message' => "User photo Remove successfully.",

                ];

                return $data;

            } else {

                return General::setResponse('other_error', "Please try after sometime.");

            }

        } catch (\Exception $e) {

            return General::setResponse('unauthorized', $e->getMessage());

        }

    }

 

        public function checkUserId($postData)

    {



         $v = $this->userValidate->required($postData, array('user_uuid'));



        if ($v->fails()) {



            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];



            return $data;

        }

    }





    function getUserData($uuid){

      $result = User::where('uuid',$uuid)->first()->toArray();

     



     if($result['role'] == "DRIVER" or $result['role']  =='COMPANY')

       {

          $address_of_driver = $this->getAddressDetail($result['uuid']);  

          $result = array_merge($result,$address_of_driver);

       } 

      

       foreach($result as $key=>$val){

                    if($key == 'image'){



                        if($val != '')

                       {

                        $arr_doc = explode('/',$val);

                        $cnt = count($arr_doc);

                        $result[$key] = $arr_doc[$cnt-1]; 

                       

                       }else{

                        $result[$key] = NULL; 

                       }

    

                    }

                

                 }

              return $result;   

    }



    public

    function getSupplierDashbord($postData)

    {



        $v = $this->userValidate->required($postData, array('user_uuid'));



        if ($v->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];

            return $data;

        }

        

         try {



            return $this->user->getSupplierDashbord($postData);



        } catch (\Exception $e) {

            return General::setResponse('unauthorized', $e->getMessage());

        }

    }



    public function getDriverDashbord($postData)
    {
        $v = $this->userValidate->required($postData, array('user_uuid'));
        if ($v->fails()) {
            $data = General::setResponse('validation_error');
            $data['response'] = [
                'message' => $v->errors()->first(),
            ];
            return $data;
        }
        try {
            return $this->user->getDriverDashbord($postData);
        } catch (\Exception $e) {
            return General::setResponse('unauthorized', $e->getMessage());
        }
    }

    public function getCompanyDriverDashbord($postData)
    {
        $v = $this->userValidate->required($postData, array('user_uuid'));
        if ($v->fails()) {
            $data = General::setResponse('validation_error');
            $data['response'] = [
                'message' => $v->errors()->first(),
            ];
            return $data;
        }
        try {
            return $this->user->getCompanyDriverDashbord($postData);
        } catch (\Exception $e) {
            return General::setResponse('unauthorized', $e->getMessage());
        }
    }

    public function getRecentOrders($postData)

    {
        $salesOrder = New SalesOrder;

        $v = $this->userValidate->required($postData, array('user_uuid'));
        if ($v->fails()) {
            $data = General::setResponse('validation_error');
            $data['response'] = [
                'message' => $v->errors()->first(),
            ];
            return $data;
        }
        $orderstatus_model = new OrderstatusUpdate();
        $order_array = array(); 
        $order_status = array(); 
        $orders = SalesOrder::leftjoin('baskets','baskets.order_id','=','sales_orders.uuid')->where('sales_orders.user_id',$postData['user_uuid'])->where('sales_orders.order_status','=','DELIVERED')->where('sales_orders.payment_status','=','COMPLETED')->orderBy('sales_orders.created_at', 'desc')->get();
        // print_r($orders);die();
        foreach($orders as  $key => $order ) {

            $orderData = $salesOrder->where('uuid',$order->order_id)->first();
            // print_r($orderData->order_number);die();

            $k = 0;
            $order_status = array(); 
            $order_status_data = $orderstatus_model->where('sales_id',$order->order_id)->orderBy('created_at', 'asc')->get();
            foreach ($order_status_data as  $value) {
                $order_status[$k]['status'] = $value->order_status;
                $order_status[$k]['date'] = $value->created_at->format('Y-m-d H:i:s');  
                $k++;   
            }
            $order_array[$key]['basket_uuid'] =  $order->uuid;
            $order_array[$key]['order_uuid'] =  $order->order_id;
            $order_array[$key]['order_number'] = $orderData->order_number;
            $order_array[$key]['user_id'] =  $order->user_id;
            $order_array[$key]['supplier_uuid'] =  $order->supplier_id;
            $order_array[$key]['supplier_name'] =  $order->supplier_name;
            $order_array[$key]['total_price'] =  $order->final_total;
            $order_array[$key]['order_status'] =  $order->order_status;
            $order_array[$key]['payment_status'] =  $order->payment_status;
            $order_array[$key]['order_date'] =  $order->created_at->format('Y-m-d H:i:s');
            $order_array[$key]['order_history']=  $order_status;
        }
        if(empty($order_array)) {
            $data['response'] = [];
        }
        else {
            $data['response'] = $order_array;
        }    
        return $data;
    }

    public

    function getDriverOrder($postData)

    {



        $v = $this->userValidate->required($postData, array('user_uuid','user_type'));



        if ($v->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];

            return $data;

        }

        

         try {



            return $this->user->getDriverOrder($postData);



        } catch (\Exception $e) {

            return General::setResponse('unauthorized', $e->getMessage());

        }

    }

      

      



}

