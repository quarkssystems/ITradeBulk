<?php



namespace App\Http\Controllers\Api;

use App\Models\LocationZipcode;

use App\Models\LocationCity;

use App\Models\LocationCountry;

use App\Models\LocationState;

use App\Models\UserCompany;

use App\Classes\UserCls;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use DB;





class UserController extends Controller

{



    public $vendorRole = 'VENDOR';

    public $supplierRole = 'SUPPLIER';

    public $driverRole = 'DRIVER';

    public $companyRole = 'COMPANY';

    public $pickerRole = 'PICKER';

    public $dispatcherRole = 'DISPATCHER';


    private $userObj;



    public function __construct(UserCls $userObj)

    {

        $this->userObj = $userObj;

    }

    public function getBanner(Request $request){

        $postData = $request->all();
        $arr_obj = array();     
        $response = $this->userObj->getBanner($postData);

        return response($response);

    }


    public function becomeVendor(Request $request){

        $postData = $request->all();

        $postData['user_type'] = $this->vendorRole;

        $user = $this->userObj->signup($postData);

        return response($user);

        

        

    }



    public function becomesupplier(Request $request){

        $postData = $request->all();

        $postData['user_type'] = $this->supplierRole;

        $user = $this->userObj->signup($postData);

         return response($user);

    }



    public function becomeDriver(Request $request){

        $postData = $request->all();



         /* if($postData['user_type'] = $this->driverRole and $postData['user_type'] != $this->companyRole)

            $data['status'] = 'false';

                  $data['response'] = [

                    'message' => 'User type is wrong. User_type is DRIVER OR COMPANY',

                  ];

             return response()->json($data, 401);  

*/

        $postData['user_type'] = $postData['user_type'];

        $user = $this->userObj->signup($postData);

         return response($user);

    }





    public function changePassword(Request $request){

        $response = $this->userObj->changePassword($request->all());

        return response($response);

    }



    public function forgotPassword(Request $request)

    {

        $response = $this->userObj->forgotPassword($request->all());

        return response($response);

    }



    public function updateToken(Request $request)

    {

        $response = $this->userObj->updateToken($request->all());

      

        return response($response);

    }



    public function getBusinessType(Request $request){

     

        $userCompanyModel = new UserCompany();

        $businessType = $userCompanyModel->getBusinessTypeDropDown();

        $btype = array();

        foreach ($businessType as $key => $value) {

            # code...

            $btype[]= array("Title" => $value);

        }

        $response = $btype;

       

        return response($response);

    }



    public function getCountries(Request $request){

     



        $locationCountryModel = new LocationCountry();

        $countries = $locationCountryModel->getDropdown();

        $country = array();

        foreach ($countries as $key => $value) {

            # code...

            $country[]= array( "Country_id" =>$key ,  "Title" => $value);

        }

        $response = $country;

        return response($response);

    }

    

     public function getState(Request $request){

     



        $country_uuid = $request['country_id'];

        $locationStateModel = new LocationState();

        $states = array();

        if(!is_null($country_uuid)) {

        $states = $locationStateModel->where('country_id', $country_uuid)->where('status','ACTIVE')->pluck('state_name', 'uuid');

        }



        $state_array = array();

        foreach ($states as $key => $value) {

            # code...

            $state_array[]= array( "State_id" =>$key ,  "Title" => $value);

        }

        $response = $state_array;

        return response($response);

    }



     public function getCity(Request $request){

        

        $state_uuid = $request['state_id'];

        $locationCityModel = new LocationCity();

        $cities = array();

        if(!is_null($state_uuid)) {

        $cities = $locationCityModel->where('state_id', $state_uuid)->where('status','ACTIVE')->pluck('city_name', 'uuid');

        }



        $city_array = array();

        foreach ($cities as $key => $value) {

            # code...

            $city_array[]= array( "City_id" =>$key ,  "Title" => $value);

        }

        $response = $city_array;

        return response($response);

    }



    public function getZipcode(Request $request){

        $city_uuid = $request['city_id'];

        $locationZipModel = new LocationZipcode();

        $zip_code = array();

        if(!is_null($city_uuid)) {

            $zip_code = $locationZipModel->where('city_id', $city_uuid)->where('status','ACTIVE')->pluck('uuid',DB::raw("CONCAT(zipcode,'-',zipcode_name) AS name"));

        }



        $zipcode = array();

        foreach ($zip_code as $key => $value) {

            # code...

            $zipcode[]= array( "Zipcode_id" =>$value ,  "Title" => $key);

        }

        $response = $zipcode;

        return response($response);

    }



      public function uploadUserPhoto(Request $request){

        $token = $this->userObj->uploadUserPhoto($request->all());

       return response($token);

    }



      public function removeUserPhoto(Request $request){

        $token = $this->userObj->removeUserPhoto($request->all());

       return response($token);

    }



     public function updateUserDetails(Request $request){



        $token = $this->userObj->updateUserDetail($request->all());

        return response($token);

    }



    public function updateCompanyDetails(Request $request){



        $token = $this->userObj->updateCompanyDetail($request->all());

         return response($token);

    }



    public function companyDriverDetails(Request $request){



        $token = $this->userObj->companyDriverDetails($request->all());

         return response($token);

    }



    public function updateBankDetails(Request $request){

        

        $token = $this->userObj->updateBankDetail($request->all());

         return response($token);

    }



     public function updateDocument(Request $request){



        $token = $this->userObj->updateDocument($request->all());

       

         return response($token);

    }



     public function removeDocument(Request $request){



        $response = $this->userObj->removeDocument($request->all());

       

          

       return response($response);

    }



     public function updateTax(Request $request){

       

        $token = $this->userObj->updateTax($request->all());

         return response($token);

    }



    

     public function updateDriverVehicle(Request $request){



        $response = $this->userObj->updateDriverVehicle($request->all());

        return response($response);

    }



  

     public function getDriverVehicle(Request $request){

      

        $postData = $request->all();

        $token = $this->userObj->getDriverVehicle($postData);

        $response = $token['response'];



         return response(json_encode($response));

    }



    public function getCompanyDriverlist(Request $request){

      

        $postData = $request->all();



        $response = $this->userObj->getCompanyDriverlist($postData);

       

          return response($response);

    }



      public function getCompanyDriverDetail(Request $request){

      

        $postData = $request->all();



        $response = $this->userObj->getCompanyDriverDetail($postData);

       

          return response($response);

    }

    



     public function getTransport(Request $request){

        

         $postData = $request->all();

        $response =  $this->userObj->getTransport($postData);

        return response($response);

       

    }



     public function getTruckBodyType(Request $request){

     

        $response = $this->userObj->getTruckBodyType();

        return response($response);

    }



     public function getWorktype(Request $request){

     

        $response = $this->userObj->getWorktype();

        return response($response);

    }



     public function getAvailability(Request $request){

     

        $response = $this->userObj->getAvailability();

        return response($response);

    }





      public function getNotificationData(Request $request){

        

         $postData = $request->all();

        $response =  $this->userObj->getNotificationData($postData);

        return response($response);

       

    }



       public function readNotificationData(Request $request){

        

         $postData = $request->all();

        $response =  $this->userObj->readNotificationData($postData);

        return response($response);

       

    }


    public function markAllAsRead(Request $request){
      $postData = $request->all();
      $response = $this->userObj->markAllAsRead($postData);
      return response($response);
    }



      protected function userDetail(Request $request)

    {

 

      $postData = $request->all();

    

      $result = $this->userObj->checkUserId($postData);

       if($result!=''){

             return response()->json($result);  

        }

      $user_uuid = $request['user_uuid'];



      $user = $this->userObj->getUserData($user_uuid);



      $userCompanyDetail = $this->userObj->getCompanyDetail($user['uuid']);

      $userBankDetail = $this->userObj->getBankDetail($user['uuid']);

      $userDocDetail = $this->userObj->getDocDetail($user['uuid'] ,$user['role']);

      $userTaxDetail = $this->userObj->getTaxDetail($user['uuid']);



      return response()->json([

            'status' => 'true',

            'user'=>$user,

            'user_company_detail'=>$userCompanyDetail,

            'user_bank_detail'=>$userBankDetail,

            'user_document_detail'=>$userDocDetail,

            'user_tax_detail'=>$userTaxDetail

        ]);

    }


  public function getITZPaymentInstruction(Request $request) {


    $postData = $request->all();
    $arr_obj = array();     
    $response = $this->userObj->getITZPaymentInstruction($postData);

    return response($response);

  }


}

