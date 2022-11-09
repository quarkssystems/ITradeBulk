<?php



namespace App\Repositories;



use App\Exceptions\CustomeException;

use App\General\General;

use App\General\ChangeOrderStatus;

use App\Models\UserDevices;

use App\Models\UserCompany;

use App\Models\UserBankDetails;

use App\Models\UserDocument;

use App\Models\BankMaster;

use App\Models\BankBranch;

use App\Models\UserTaxDetails;

use App\Models\SalesOrder;

use App\Models\LogisticDetails;

use App\Models\Notification;

use App\Models\WalletTransactions;

use App\Models\Withdrawal;

use App\Models\Product;

use App\Models\Basket;

use App\Models\Banner;

use App\Models\SupplierItemInventory;

use Auth;

use App\Models\OrderstatusUpdate;

use App\Repositories\BaseRepository;

use App\User;

use DB;

use Illuminate\Foundation\Auth\ResetsPasswords;

use Illuminate\Hashing\BcryptHasher;

use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Password;

use App\Models\OrderLogisticQueue;



/**

 * Class UserRepository.

 */

class UserRepository extends BaseRepository

{

    use ResetsPasswords;



    public function model()

    {

        return User::class;

    }



    public function register(array $request)

    {

        if($request['user_type'] == 'VENDOR' || $request['user_type'] == 'SUPPLIER'){

          $userDetail = $request['userDetail'];

         $userDetail = array_merge($userDetail, [

              'zipcode_id' => $request['zipcode_id'],

              'city_id' => $request['city_id'],

              'state_id' => $request['state_id'],

              'country_id' => $request['country_id']

          ]);

       }else{

        $userDetail =  array(

              'address1' => $request['address1'],

              'address2' => $request['address2'],

              'zipcode_id' => $request['zipcode_id'],

              'city_id' => $request['city_id'],

              'state_id' => $request['state_id'],

              'country_id' => $request['country_id']

          );



       }



        $request = array_merge($request,['status' => 'ACTIVE', 'role' => $request['user_type'], 'password' => bcrypt($request["password"])]);



        return DB::transaction(function () use ($request, $userDetail) {

            $userDB = new User;

            if ($userData = $userDB->create($request)) {

                if($request['user_type'] == 'DRIVER' || $request['user_type'] == 'COMPANY') {

                  // print_r('test');die();

                  $userDetail =array_merge($userDetail,['user_id' => $userData->uuid]);

                 // print_r($userDetail);  die;

                   $userData->logisticDetails()->create($userDetail);



                }else if($request['user_type'] == 'VENDOR' || $request['user_type'] == 'SUPPLIER'){

                  // print_r('hi');die();

                    $userData->company()->create($userDetail);

                }

               // print_r('test');die();

               $userData->sendEmailVerificationNotification();

                return $userData;

            }

            throw new CustomeException("Network error. Please try after some time.",500);

        });

    }





     public function registerCompanyDriver(array $request)

    {

       

        $userDetail = $request['userDetail'];

        $request = array_merge($request,['status' => ' ACTIVE', 'role' => $request['user_type'], 'password' => bcrypt($request["password"])]);



        return DB::transaction(function () use ($request, $userDetail) {

            $userDB = new User;

            if ($userData = $userDB->create($request)) {

                $userData->sendEmailVerificationNotification();

                return $userData;

            }

            throw new CustomeException("Network error. Please try after some time.",500);

        });

    }





    public function changePassword($data){

        try{

            $user = Auth::User();

            $user->password = bcrypt($data['new_password']);



            if($user->save()){

                return ['success' => 'Password changed successfully.'];

            }

            return ['error' => 'Network error. Please try after some time.'];

        }catch (\Exception $e){

            throw new CustomeException("Network error. Please try after some time.",500);

        }

    }



    public function forgotPassword($data)

    {



        $userObj = $this->model->where('email', $data['email']);



        if ($userObj->count() != 1) {

            return General::setResponse('other_error', "We can't find a user with that e-mail address");

        } else {

            $user = $userObj->first();

            $token = Password::getRepository()->create($user);

            $user->sendPasswordResetNotification($token);

            return General::setResponse('success', 'A password reset link was sent to your email address.');

        }

    }



    public function updateUserDeviceDetails($data)

    {

       return DB::transaction(function () use ($data) {



            $user_uuid = isset($data['uuid']) ? $data['uuid'] : Auth::user()->uuid;

            $userDevices = new UserDevices;

            /*if($userDevices->where('device_id', $data['device_id'])->count() > 1){

                $userDevices->where('device_id', $data['device_id'])->update(['device_id' => NULL, 'device_token' => NULL]);

            }else */

            if($userDevices->where('user_id', $user_uuid)->count() == 1){

                $userDevices->where('user_id', $user_uuid)->update([

                    'device_id' => $data['device_id'],

                    'device_token' => $data['device_token'],

                    'device_type' => $data['device_type'],

                    'device_os' => $data['device_os'],

                    'device_model' => $data['device_model'],

                    'player_id' => $data['device_token']

                ]);



               //$playerid = AddDeviceInOneSignal($data); //register One Signal

               //$userDevices->where('device_id', $data['device_id'])->update(['player_id' => $playerid]); 



            }else{



                    $userDevices->create([

                    'device_id' => $data['device_id'],

                    'user_id' => $user_uuid,

                    'device_token' => $data['device_token'],

                    'device_type' => $data['device_type'],

                    'device_os' => $data['device_os'],

                    'device_model' => $data['device_model'],

                     'player_id' => $data['device_token']

                ]);

              // $playerid = AddDeviceInOneSignal($data);  //register One Signal

              //die; 

              // $userDevices->where('device_id', $data['device_id'])->update(['player_id' => $playerid]);



            }

            return $userDevices;

        });

        throw new CustomeException("Network error. Please try after some time.",500);

    }



     public function updateUser(array $request)

    {

        return DB::transaction(function () use ($request) {

            

            $user_uuid = $request['user_uuid'];

            unset($request['user_type']);

            unset($request['user_uuid']);

            $userDB = new User;

          return  $userData = $userDB->where('uuid',$user_uuid )->update($request);

          

        });

          throw new CustomeException("Network error. Please try after some time.",500);

    }



    

     public function  getCompanyDetail($uuid)

    {

        $result = array();

        $usercomDB = new UserCompany;

        if($usercomDB->where('owner_user_id',$uuid)->count() > 0 ){

            $result = $usercomDB->where('owner_user_id',$uuid)->first()->toArray(); 

        }

        else

        {

            foreach( $usercomDB->getFillable() as $item)

            {

                $result[$item] = isset($usercomDB->$item) ? $usercomDB->$item : NULL;

            }

        }

        

        return (object)($result);

    }


    public function getBanner($data) {

      $path = '';
      // $imgArr = array();
      $image = array();

      $path = '/assets/frontend/images/banners/Untitled-3.png'; 
      $path = url('/').$path;

      $image = Array('base_url' => $path);
      // print_r($image);die();
      $result = array();

      $banners = Banner::whereNull('deleted_at')->where([['status','Active'],['in_slider','on']])->orderBy('sequence_number','ASC')->get();

    // if(!empty($banners)) {
    //     foreach($banners as $key => $row) {
    //         // $imgArr['base_url'] = $row['image'] ?? '';
    //         array_push($image,$row['image']);
    //     }
    // }

    // print_r($image);die();

      if(count($image)) {   
        $result['status'] ="true";
        $result['data'] =  $image;
      }
      else
      {
        $result['status'] ="false";
        $result['data'] = [];
      }
      return $result;
    }

    public function getITZPaymentInstruction($data) {


      $ITZPaymentInstruction = array();

      $ITZPaymentInstruction = Array('title' => env('APP_NAME').' Payment Instruction' , 'note' => "Please pay the total amount to out bank account and enter your Beneficiary reference number '41076',You can confirnation by using your bank's email payment confiramtion service or please allow one or two working day's to receive and verify your payment." , 'Bank Name' => 'FNB' , 'Account Name' => env('APP_NAME').' Pty Ltd.', 'Account No' => '123456789', 'Branch Code' => '012-342', 'Branch Name' => 'Spring', 'Account Type' => 'Business Cheque Account', 'Beneficiary Ref.' => '41076');
      // $ITZPaymentInstruction = Array('title' => 'Itradezon Payment Instruction' , 'note' => "Please pay the total amount to out bank account and enter your Beneficiary reference number '41076',You can confirnation by using your bank's email payment confiramtion service or please allow one or two working day's to receive and verify your payment." , 'Bank Name' => 'FNB' , 'Account Name' => 'iTradeZon Pty Ltd.', 'Account No' => '123456789', 'Branch Code' => '012-342', 'Branch Name' => 'Spring', 'Account Type' => 'Business Cheque Account', 'Beneficiary Ref.' => '41076');
      // print_r($image);die();
      $result = array();

      if(count($ITZPaymentInstruction)) {   
        $result['status'] ="true";
        $result['data'] =  $ITZPaymentInstruction;
      }
      else
      {
        $result['status'] ="false";
        $result['data'] = [];
      }
      return $result;
    }


  public function addBank($data)
  { 
    return BankMaster::create($data); 
  }

  public function addBankBranch($data)
  { 
    return BankBranch::create($data); 
  }



     public function  getBankDetail($uuid)

    {



        $result = array(); 

         $userbank= new UserBankDetails;

         if($userbank->where('user_id',$uuid)->count() > 0 ){

            $result = $userbank->where('user_id',$uuid)->first()->toArray(); 

            

               foreach($result as $key=>$val){

                    if($key =='account_confirmation_letter_file'){

                        if($val != '')

                       {

                        $arr_doc2 = explode('/',$val);

                        $cnt2 = count($arr_doc2);

                        $result[$key] = $arr_doc2[$cnt2-1]; 

                       

                       }else{

                        $result[$key] = NULL; 

                       }

    

                    }

                

                 }



         }

        else

        {

            foreach( $userbank->getFillable() as $item)

            {

                $result[$item] = isset($userbank->$item) ? $userbank->$item : NULL;

            }

         }



        return (object)($result);

    }



     public function  getAddressDetail($uuid)

    {

       

        $userLogistic = New LogisticDetails;

       

       

         if($userLogistic->where('user_id',$uuid)->count() > 0 )

         {

             $result = $userLogistic->select('address1','address2','country_id','state_id','city_id','zipcode_id')->where('user_id',$uuid)->first()->toArray(); 

         

               return $result;        

         }else{



            return array('address1'=> '','address2'=>'','country_id'=>'','state_id'=>'','city_id'=>'','zipcode_id'=>'');

         }   

     

    }



     public function  getDocDetail($uuid,$role)

    {

       

        $userDocument = New UserDocument;

        $doc = array();

        $result = $userDocument->where('user_id',$uuid)->get()->toArray(); 

         if($userDocument->where('user_id',$uuid)->count() > 0 )

         {

         //  print_r($result);



                foreach($result as $key=>$val){

                       $title = $val['title'];

                       if($val['document_file_one'] != '')

                       {

                         $arr_doc = explode('/',$val['document_file_one']);

                        $cnt = count($arr_doc);

                        $doc[$title] = $arr_doc[$cnt-1] ;

                       }else{

                        $val[$title] = NULL;

                       }



                     }

                     

         }

        else

        {

                if($role == "VENDOR"){



                    foreach( $userDocument->getVendorDocuments() as $item)

                    {

                        $title = $item['title'];

                        $doc[$title] =  NULL;

                    }

                }

                if($role== "SUPPLIER"){



                    foreach( $userDocument->getSupplierDocuments() as $item)

                    {

                        $title = $item['title'];

                        $doc[$title] =  NULL;

                    }

                }

                if($role == "DRIVER"){



                    foreach( $userDocument->getLogisticsDocuments() as $item)

                    {

                        $title = $item['title'];

                        $doc[$title] =  NULL;

                    }



                }



        }

             

     return (object)($doc);

    }



    public function  getTaxDetail($uuid)

    {

        $result = array();    

        $userTax = New UserTaxDetails;

        if($userTax->where('user_id',$uuid)->first()) {

            $result = $userTax->where('user_id',$uuid)->first()->toArray(); 

             foreach($result as $key=>$val){

                    if($key =='passport_document_file'){

                        if($val != '')

                       {

                        $arr_doc1 = explode('/',$val);

                        $cnt1 = count($arr_doc1);

                        $result[$key] = $arr_doc1[$cnt1-1]; 

                       

                       }else{

                        $result[$key] = NULL; 

                       }

    

                    }

                

                 }

            

        }else{



           foreach($userTax->getFillable() as $item)

            {

                $result[$item] = isset($userTax->$item) ? $userTax->$item : NULL;

            }

        }

       

        return (object)($result);

    }



     public function updateUserCompany(array $request)

    {

        return DB::transaction(function () use ($request) {

            $usercomDB = new UserCompany;

            $user_uuid = $request['user_uuid'];

            unset($request['user_type']);

         

          return  $userData = $usercomDB->updateOrCreate(['owner_user_id' => $user_uuid ] ,$request);

          

        });

          throw new CustomeException("Network error. Please try after some time.",500);

    }





      public function companyDriverDetails(array $request)

    {





        return DB::transaction(function () use ($request) {



        

          $user = new User;

          $userLogistic = New LogisticDetails;

          $user_uuid = isset($request['user_uuid']) ?  $request['user_uuid'] : "";

          unset($request['password_confirmation']);

          

          $request = array_merge($request,['status' => 'INACTIVE', 'password' => bcrypt($request["password"]) ]);

          

          if($user_uuid != '')

          {

           

            unset($request['user_uuid']);



            $temp = array('address1' => $request['address1'], 'address2' => $request['address2'], 'zipcode_id' => $request['zipcode_id'], 'city_id' => $request['city_id'], 'state_id' => $request['state_id'],  'country_id' => $request['country_id']); 



               unset($request['address1']);

               unset($request['address2']);

               unset($request['zipcode_id']); 

               unset($request['city_id']);

               unset($request['state_id']);

               unset($request['country_id']);

             $userData = $user->where('uuid',$user_uuid)->update($request);

           

             $userDetail =  array(

              'address1' => $temp['address1'],

              'address2' => $temp['address2'],

              'zipcode_id' => $temp['zipcode_id'],

              'city_id' => $temp['city_id'],

              'state_id' => $temp['state_id'],

              'country_id' => $temp['country_id']

              );

 

              $userLogistic = New LogisticDetails;

              $userLogistic->where('user_id',$user_uuid)->update($userDetail);

              $temp= array();

            

            return  $userData;



          }

          else

          {

            $userData = '';

            $user = new User;

            

            if($user->where('uuid', $request['logistic_company_id'])->first()) {

            $cpy_detail = $user->where('uuid', $request['logistic_company_id'])->first()->toArray();

         

            $request = array_merge($request,array('latitude' => $cpy_detail['latitude'], 'longitude' =>$cpy_detail['longitude']));





            

            $userData = $user->create($request);

            $lastinserted_id = $userData->uuid;





            //get conpany address   

             $temp = $userLogistic->select('address1','address2','zipcode_id','city_id','state_id','country_id')->where('user_id', $request['logistic_company_id'])->first()->toArray(); 

             $userDetail =  array(

              'user_id' => $lastinserted_id,

              'address1' => $temp['address1'],

              'address2' => $temp['address2'],

              'zipcode_id' => $temp['zipcode_id'],

              'city_id' => $temp['city_id'],

              'state_id' => $temp['state_id'],

              'country_id' => $temp['country_id']

              );

            $userData->logisticDetails()->create($userDetail);





          

            }

             return  $userData;

          }

            

          

          

          

        });

          throw new CustomeException("Network error. Please try after some time.",500);

    }







     public function updateUserBankDetail(array $request)

    {

        return DB::transaction(function () use ($request) {

            $userbank= new UserBankDetails;

            $user_uuid = $request['user_uuid'];

            unset($request['user_type']);

           // unset($request['user_uuid']);

        

          return  $userData = $userbank->updateOrCreate(['user_id' => $user_uuid ],$request);

          

        });

          throw new CustomeException("Network error. Please try after some time.",500);

    }



     public function updateUserDocument(array $request)

    {

        return DB::transaction(function () use ($request) {

              

            $userDocument = New UserDocument;

            $user_uuid = $request['user_uuid'];

            

            unset($request['user_type']);

            unset($request['user_uuid']);

           

            foreach($request as $key =>$documentOne)

            {

                

                $data = [];

                if ($documentOne->isValid()) {

                    $documentFile = $userDocument->uploadMedia($documentOne);

                    $document = $documentFile['path'] . $documentFile['name'];

                    $data['document_file_one'] = $document;

                }

                $data['title'] = ucfirst(str_replace('_',' ',$key));

                $data['user_id'] = $user_uuid;

                $userData = $userDocument->updateOrCreate(['user_id' => $user_uuid, 'title' => $data['title']], $data);

                //$keysCreated[] = $key;

            }



          return $userData;

          

        });

          throw new CustomeException("Network error. Please try after some time.",500);

    }





    public function removeUserDocument(array $request)

    {

        return DB::transaction(function () use ($request) {

              

            $userDocument = New UserDocument;

            $user_uuid = $request['user_uuid'];

            $title = $request['title'];



            $userData = $userDocument->where('user_id', $user_uuid)->where('title' , $title)->delete();

            

          return $userData;

          

        });

          throw new CustomeException("Network error. Please try after some time.",500);

    }





    public function updateUserTaxDetail(array $request)

    {

        return DB::transaction(function () use ($request) {

        



            $user_tax_detail = New UserTaxDetails;

            $user_uuid = $request['user_uuid'];

            //if($request['passport_document']->isValid())

            //{



                //$documentFile = $user_tax_detail->uploadMedia($request['passport_document']);

                //$document = $documentFile['path'].$documentFile['name'];

                //$request = array_merge($request ,['passport_document_file' => $document]);

           //}





          return  $userData = $user_tax_detail->updateOrCreate(['user_id' => $user_uuid], $request);

          

        });

          throw new CustomeException("Network error. Please try after some time.",500);

    }



     public function updateDriverVehicle(array $request)

    {

      

        return DB::transaction(function () use ($request) {

        



            $user_logistic_detail = New LogisticDetails;

            $user_uuid = $request['user_uuid'];



          return  $userData = $user_logistic_detail->updateOrCreate(['user_id' => $user_uuid], $request);

          

        });

          throw new CustomeException("Network error. Please try after some time.",500);

    }





    public function updateOrderStatus(array $request)

    { 

      // print_r($request);die();
        return DB::transaction(function () use ($request) {

            

             

             $data = ChangeOrderStatus::orderStatus($request);

             return  $data;

          

        });

          throw new CustomeException("Network error. Please try after some time.",500);

    }





     public function packedOrder(array $request)

    {

        return DB::transaction(function () use ($request) {

            

             

             $data = ChangeOrderStatus::orderPickupPacked($request);

             return  $data;

          

        });

          throw new CustomeException("Network error. Please try after some time.",500);

    }



    public function deliveryOrderPacked(array $request)

    {

        return DB::transaction(function () use ($request) {

            

             

             $data = ChangeOrderStatus::orderDeliveryPacked($request);

             return  $data;

          

        });

          throw new CustomeException("Network error. Please try after some time.",500);

    }





     public function acceptOrderDriver(array $request)

    {

        $data =array();

        return DB::transaction(function () use ($request) {

            

              $queueModel = new OrderLogisticQueue();

              $saleorder = new SalesOrder();

              $driver_uuid = $request['driver_id']; 

              $order_uuid = $request['order_uuid']; 

              

              $occ_order = $queueModel->where('order_id',$order_uuid)->where('status','OCCUPIED')->count();

               

              if($occ_order == 0){



                $queueModel->where('driver_id',$driver_uuid)->where('order_id' ,$order_uuid)->update(['status' => 'OCCUPIED']);

                $saleorder->where('uuid' ,$order_uuid)->update(['logistic_id' => $driver_uuid ]);



                $order_data = $saleorder->where('uuid' ,$order_uuid)->first();



                //otp table need to staus chnage accect



                if($order_data ){



                  $req['order_uuid'] = $order_uuid;   

                  $req['sender_id'] = $order_data->supplier_id; 

                  $req['receiver_id'] = $order_data->logistic_id; 

                //print_r($order_data);

                //die;

                    
                $req['type'] = Notification::DRIVER; 

                ChangeOrderStatus::otpsend($req); //supplier and driver otp

                

                $data = array('Message' => 'success' ,'status' => true);

                    

                } 

                



                      

              }else{



                $data = array('Message' => 'Order occupied' ,'status' => false);

              }



             

             return  $data;

          

        });

          throw new CustomeException("Network error. Please try after some time.",500);

    }



     public function rejectOrderDriver(array $request)

    {

        return DB::transaction(function () use ($request) {

               

              $data =array();  

              $queueModel = new OrderLogisticQueue();

              $driver_uuid = $request['driver_id']; 

              $order_uuid = $request['order_uuid']; 

              

              $queueModel->where('order_id' ,$order_uuid)->where('driver_id', $driver_uuid)->update(['status' => 'REJECT']);

               

               /*   $req['order_uuid'] = $order_uuid;   

                  $req['sender_id'] = $order_data->supplier_id; 

                  $req['receiver_id'] = $order_data->driver_uuid; 

                ChangeOrderStatus::otpsend($req); //supplier and driver otp

               */ 

                $data = array('Message' => 'success' ,'status' => true);

                    

               

             return  $data;

          

        });

          throw new CustomeException("Network error. Please try after some time.",500);

    }



    public function ReadNotificationData($data)

    {

       $user_id = $data['user_uuid'];

       $notify_id = $data['notification_uuid'];

       $notification = new Notification;

       return $notifications = $notification->where('user_id',$user_id)->where('uuid',$notify_id)->update(['status'=>'READ']);

       

    }   


    public function markAllAsRead($data)

    {

       $user_id = $data['user_uuid'];

       $notification = new Notification;

       return $notifications = $notification->where('user_id',$user_id)->update(['status'=>'READ']);

    }  



    public function getNotificationData($data)

    {

       $user_id = $data['user_uuid'];

       $offset = isset($data['start_offset']) ? $data['start_offset'] : 0;

       $limit = isset($data['limit']) ? $data['limit'] : 10; 

       

       $notification = new Notification;

       $user = new  User;

       $orderLogisticQueueModel = New OrderLogisticQueue;

       $salesOrder  = new SalesOrder;



       $user_role = $user->where('uuid',$user_id)->pluck('role')->first();



       $notifications = $notification->where('user_id',$user_id);

       $notifications = $notifications->orderBy('created_at','desc');

       $notifications = $notifications->skip($offset)->take($limit);

       $notifications_arr = $notifications->get();

       $notify_arr = array();



         foreach($notifications_arr as $kn => $notify) 

              {



                $orderno = $salesOrder->where('uuid',$notify->order_id)->first();



                $notify_arr[$kn]['uuid'] = $notify->uuid;

                $notify_arr[$kn]['notification'] = $notify->notification;

                $notify_arr[$kn]['user_id'] = $notify->user_id;

                $notify_arr[$kn]['order_id'] = $notify->order_id;

                $notify_arr[$kn]['order_no'] = '#'.$orderno->order_number;

                $notify_arr[$kn]['read_status'] = $notify->status;

                $notify_arr[$kn]['created_at'] = $notify->created_at->format('Y-m-d H:i:s');

                

                if($user_role =='DRIVER'){







                  $driver_accept = $orderLogisticQueueModel->where('driver_id', $notify->user_id)->where('order_id',$notify->order_id )->pluck('status')->first();

                 

                  if($driver_accept == 'ACCEPT' ){ //  display button for accept and reject 



                      $is_order_accepted = $orderLogisticQueueModel->where('order_id',$notify->order_id )->where('status','OCCUPIED')->first();

                       if($is_order_accepted){

                          $notify_arr[$kn]['occupied'] = 'false';    

                       }else{

                          $notify_arr[$kn]['accectable'] = 'true';  

                       } 

                      

                  }

                  if($driver_accept == 'OCCUPIED' ){ //after accepted

                    $notify_arr[$kn]['occupied'] = 'true';

                  }

                  if($driver_accept == 'REJECT' ){ // u reject order

                    $notify_arr[$kn]['reject'] = 'true';

                  }

                }

               }



       //echo '<pre>'; print_r($categories_arr); die;





       /*echo '<pre>';

       print_r($notifications_arr);

       die;*/



       

         //echo "<pre>"; print_r($data_category); exit;

         if(count($notify_arr) > 0){

            $result['status'] ="true";

            $result['data'] = $notify_arr;

         } else {

           $result['status'] ="false";

           $result['data'] = [];

         }

              

        return $result;

   }



    public function uploadUserPhoto(array $request)

    {

        return DB::transaction(function () use ($request) {

            $user_uuid = $request['user_uuid'];

              $userDB = new User;

              unset($request['user_uuid']);

            //  unset($request['login_user_id']);

            if($request['image']->isValid())

            {

                $documentFile = $userDB->uploadMedia($request['image']);

                $document = $documentFile['path'].$documentFile['name'];

                $request = array_merge($request ,['image' => $document]);

           }



          return  $userData = $userDB->where('uuid',$user_uuid )->update($request);

          

        });

          throw new CustomeException("Network error. Please try after some time.",500);

    }



      public function removeUserPhoto(array $request)

    {

        return DB::transaction(function () use ($request) {

              $user_uuid = $request['user_uuid'];

              $userDB = new User;



             return  $userData = $userDB->where('uuid',$user_uuid )->update(['image'=>NULL]);

          

        });

          

          throw new CustomeException("Network error. Please try after some time.",500);

    }



     

    public function getDriverDashbord(array $request)
    {
     return DB::transaction(function () use ($request) {
            $user_uuid = $request['user_uuid'];
            $salesOrder  = new SalesOrder;
            $basketModel = new Basket;
            $orderstatus_model = new OrderstatusUpdate();
            $walletTransactions = new WalletTransactions;
            $supplierItemInventoryModel = new SupplierItemInventory; 

          $notifyModel = new Notification;

          $unread_notification = $notifyModel->where('user_id',$user_uuid)->where('status','UNREAD')->count();

          // $walletTotal  = WalletTransactions::where('user_id',$user_uuid)->where('remarks','DELIVERED PRODUCT')->approved()->get();
          // $driver_earned_amount = $walletTotal->sum('credit_amount');

          // $creditedAmount = WalletTransactions::leftjoin('users', 'wallet_transactions.user_id','=','users.uuid')->select(DB::raw("SUM(credit_amount) as creditAmount"))->where('wallet_transactions.user_id','=',$user_uuid)->first();

          // $debitedAmount = WalletTransactions::leftjoin('users', 'wallet_transactions.user_id','=','users.uuid')->select(DB::raw("SUM(debit_amount) as debitAmount"))->where('wallet_transactions.user_id','=',$user_uuid)->first();

          // $withdrawalAmount = Withdrawal::leftjoin('users', 'withdrawals.user_id','=','users.uuid')->select(DB::raw("SUM(amount) as amount"))->where('withdrawals.user_id','=',$user_uuid)->where('withdrawals.status','=','PENDING')->first();

          // $driver_earned_amount = $creditedAmount->creditAmount - $debitedAmount->debitAmount - $withdrawalAmount->amount;

          // $driver_earned_amount = auth()->user()->wallet_balance;

          	$user = new User;
            $user_balance = $user->where('uuid',$user_uuid)->first();
            $driver_earned_amount = $user_balance->wallet_balance;

          // print_r($driver_earned_amount);die();

          $total_delivery = $salesOrder::where('logistic_id',$user_uuid)->get();
          $driver_total_delivery = $total_delivery->count('*');

           $orders = $salesOrder->where('logistic_id',$user_uuid )->orderBy('created_at','desc')->limit(5)->get();
           $totalorder = $orders->count();
             
           $processedOrders = $salesOrder->where('logistic_id',$user_uuid )->where('order_status', '!=' ,"DELIVERED")->where('order_status', '!=' ,"CANCELLED")->count();
           // print_r($processedOrders);die();

           $completedOrders = $salesOrder->where('logistic_id',$user_uuid )->where('order_status', '=' ,"DELIVERED")->where('payment_status', '=' ,"COMPLETED")->count();
           // print_r($completedOrders);die();


           $order_array =array();
           $order_status  =array();
           $i=0;

            foreach($orders as  $key => $order ) {
            $k = 0;
            $order_status_data = $orderstatus_model->where('sales_id',$order->uuid)->orderBy('created_at', 'asc')->get();

            foreach ($order_status_data as  $value) {
                $order_status[$k]['status'] = $value->order_status;
                $order_status[$k]['date'] = $value->created_at->format('Y-m-d H:i:s');  
                $k++;   
            }

            $basket = $basketModel->where('order_id', $order->uuid)->first();
            $products_data = $basket->products;

            $order_array[$i]['order_uuid'] =  $order->uuid;
            $order_array[$i]['order_number'] =  $order->order_number;
            $order_array[$i]['user_id'] =  $order->supplier_id;
            $order_array[$i]['order_number'] =  $order->order_number;
            $order_array[$i]['user_name'] =  $order->user_name;
            $order_array[$i]['supplier_name'] =  $order->supplier_name;
            $order_array[$i]['cart_amount'] =  $order->cart_amount ? $order->cart_amount : 0;
            $order_array[$i]['shipment_amount'] =  $order->shipment_amount ? $order->shipment_amount : 0;
            $order_array[$i]['discount_amount'] =  $order->discount_amount ? $order->discount_amount : 0;
            $order_array[$i]['tax_amount'] =   $order->tax_amount ? $order->tax_amount : 0;
            $order_array[$i]['total_price'] =  $order->final_total ? $order->final_total : 0;
            $order_array[$i]['total_price'] =  $order->final_total ? $order->final_total : 0;
            $order_array[$i]['order_status'] =  $order->order_status;
            $order_array[$i]['payment_status'] =  $order->payment_status;
            $order_array[$i]['order_date'] =  $order->created_at->format("Y-m-d H:i:s");
            $order_array[$i]['delivery_type']=  $order->delivery_type;
            $order_array[$i]['order_history']=  $order_status;// array_merge($order_history  ,$orderstatus) ;  
            $order_array[$i]['products'] = array();
            $k=0;
            foreach($products_data as $cartProduct){
                $order_array[$i]['products'][$k]['product_uuid'] = $cartProduct->product_id; 
                $order_array[$i]['products'][$k]['product_image'] = url('/') .$cartProduct->product->base_image;
                $order_array[$i]['products'][$k]['name'] = $cartProduct->product->name; 
                $order_array[$i]['products'][$k]['qty']= $cartProduct->single_qty; 
               $supplierLatestRate = $supplierItemInventoryModel->where('product_id', $cartProduct->product_id)->where('user_id', $order->supplier_id)->orderBy('id', 'DESC')->first();
                $order_array[$i]['products'][$k]['price']  = $supplierLatestRate->single_price;
                $order_array[$i]['products'][$k]['product_price']  = $supplierLatestRate->single_price * $cartProduct->single_qty; 
                $k++; 
            }
            $i++;
        } 
        $result =array();
         if($totalorder > 0){
            $result['status'] ="true";
            $result['process_order_total'] =$processedOrders;
            $result['completed_order_total'] =$completedOrders;
            $result['driver_earned_amount'] = $driver_earned_amount;
            $result['driver_total_delivery'] = $driver_total_delivery;
            $result['unread_notification'] = $unread_notification;
            $result['latest-orders-driver'] =$order_array;
         } else {
           $result['status'] ="false";  
           $result['process_order_total'] = 0;
           $result['completed_order_total'] = 0;
           $result['driver_earned_amount'] = 0;
           $result['driver_total_delivery'] = 0;
           $result['unread_notification'] = $unread_notification;
           $result['latest-orders-driver'] = [];
         }
        return $result;
        });
        throw new CustomeException("Network error. Please try after some time.",500);
    }



    public function getCompanyDriverDashbord(array $request)
    {

      // print_r($request);die();

     return DB::transaction(function () use ($request) {
            $user_uuid = $request['user_uuid'];
            $user = new User;
            $salesOrder  = new SalesOrder;
            $basketModel = new Basket;
            $orderstatus_model = new OrderstatusUpdate();
            $walletTransactions = new WalletTransactions;
            $supplierItemInventoryModel = new SupplierItemInventory; 

          $transporter = $user->where('logistic_company_id',$user_uuid )->where('logistic_type','COMPANY')->orderBy('created_at','desc')->limit(5)->get();
          $totalTransporter = $transporter->count();

          // $creditedAmount = WalletTransactions::leftjoin('users', 'wallet_transactions.user_id','=','users.uuid')->select(DB::raw("SUM(credit_amount) as creditAmount"))->where('users.logistic_company_id','=',$user_uuid)->first();

          // $debitedAmount = WalletTransactions::leftjoin('users', 'wallet_transactions.user_id','=','users.uuid')->select(DB::raw("SUM(debit_amount) as debitAmount"))->where('wallet_transactions.user_id','=',$user_uuid)->first();

          // $withdrawalAmount = Withdrawal::leftjoin('users', 'withdrawals.user_id','=','users.uuid')->select(DB::raw("SUM(amount) as amount"))->where('withdrawals.user_id','=',$user_uuid)->where('withdrawals.status','=','PENDING')->first();

          // print_r($withdrawalAmount);die();
          // $transportCompany_earned_amount = $creditedAmount->creditAmount - $debitedAmount->debitAmount - $withdrawalAmount->amount;

          // $transportCompany_earned_amount = auth()->user()->wallet_balance;

         	$user = new User;
            $user_balance = $user->where('uuid',$user_uuid)->first();
            $transportCompany_earned_amount = $user_balance->wallet_balance;

          $notifyModel = new Notification;

          $unread_notification = $notifyModel->where('user_id',$user_uuid)->where('status','UNREAD')->count();

          // $walletTotal  = WalletTransactions::where('user_id',$user_uuid)->where('remarks','DELIVERED PRODUCT')->approved()->get();
          // $transportCompany_earned_amount = $walletTotal->sum('credit_amount');

          $total_delivery = $salesOrder::where('logistic_id',$user_uuid)->get();
          $driver_total_delivery = $total_delivery->count('*'); 

          $orders = SalesOrder::leftjoin('users', 'sales_orders.logistic_id','=','users.uuid')->where('users.logistic_company_id','=',$user_uuid)->get();
          $totalorder = $orders->count();

          $pendingOrders = SalesOrder::leftjoin('users', 'sales_orders.logistic_id','=','users.uuid')->where('users.logistic_company_id','=',$user_uuid)->where('payment_status','PENDING')->get();
          $totalPendingOrder = $pendingOrders->count();

          $completedOrders = SalesOrder::leftjoin('users', 'sales_orders.logistic_id','=','users.uuid')->where('users.logistic_company_id','=',$user_uuid)->where('payment_status','COMPLETED')->get();
          $totalCompletedOrder = $completedOrders->count();

        //    $order_array =array();
        //    $order_status  =array();
        //    $i=0;

        //     foreach($orders as  $key => $order ) {
        //     $k = 0;
        //     $order_status_data = $orderstatus_model->where('sales_id',$order->uuid)->orderBy('created_at', 'asc')->get();

        //     foreach ($order_status_data as  $value) {
        //         $order_status[$k]['status'] = $value->order_status;
        //         $order_status[$k]['date'] = $value->created_at->format('Y-m-d H:i:s');  
        //         $k++;   
        //     }

        //     $basket = $basketModel->where('order_id', $order->uuid)->first();
        //     $products_data = $basket->products;

        //     $order_array[$i]['order_uuid'] =  $order->uuid;
        //     $order_array[$i]['order_number'] =  $order->order_number;
        //     $order_array[$i]['user_id'] =  $order->supplier_id;
        //     $order_array[$i]['order_number'] =  $order->order_number;
        //     $order_array[$i]['user_name'] =  $order->user_name;
        //     $order_array[$i]['supplier_name'] =  $order->supplier_name;
        //     $order_array[$i]['cart_amount'] =  $order->cart_amount ? $order->cart_amount : 0;
        //     $order_array[$i]['shipment_amount'] =  $order->shipment_amount ? $order->shipment_amount : 0;
        //     $order_array[$i]['discount_amount'] =  $order->discount_amount ? $order->discount_amount : 0;
        //     $order_array[$i]['tax_amount'] =   $order->tax_amount ? $order->tax_amount : 0;
        //     $order_array[$i]['total_price'] =  $order->final_total ? $order->final_total : 0;
        //     $order_array[$i]['total_price'] =  $order->final_total ? $order->final_total : 0;
        //     $order_array[$i]['order_status'] =  $order->order_status;
        //     $order_array[$i]['payment_status'] =  $order->payment_status;
        //     $order_array[$i]['order_date'] =  $order->created_at->format("Y-m-d H:i:s");
        //     $order_array[$i]['delivery_type']=  $order->delivery_type;
        //     $order_array[$i]['order_history']=  $order_status;// array_merge($order_history  ,$orderstatus) ;  
        //     $order_array[$i]['products'] = array();
        //     $k=0;
        //     foreach($products_data as $cartProduct){
        //         $order_array[$i]['products'][$k]['product_uuid'] = $cartProduct->product_id; 
        //         $order_array[$i]['products'][$k]['product_image'] = url('/') .$cartProduct->product->base_image;
        //         $order_array[$i]['products'][$k]['name'] = $cartProduct->product->name; 
        //         $order_array[$i]['products'][$k]['qty']= $cartProduct->single_qty; 
        //        $supplierLatestRate = $supplierItemInventoryModel->where('product_id', $cartProduct->product_id)->where('user_id', $order->supplier_id)->orderBy('id', 'DESC')->first();
        //         $order_array[$i]['products'][$k]['price']  = $supplierLatestRate->single_price;
        //         $order_array[$i]['products'][$k]['product_price']  = $supplierLatestRate->single_price * $cartProduct->single_qty; 
        //         $k++; 
        //     }
        //     $i++;
        // } 
        $result =array();

        // if(isset($totalTransporter) && $totalTransporter == 0) {
        //   $result['totalTransporter'] = 0;
        // } else {
        //   $result['totalTransporter'] = $totalTransporter;
        // }

          if($totalorder > 0){
            $result['status'] ="true";
	          $result['totalTransporter'] = $totalTransporter;
            $result['transportCompany_earned_amount'] = $transportCompany_earned_amount;
            $result['totalorder'] = $totalorder;
            $result['totalPendingOrder'] = $totalPendingOrder;
            $result['totalCompletedOrder'] = $totalCompletedOrder;
            // $result['driver_total_delivery'] = $driver_total_delivery;
            // $result['unread_notification'] = $unread_notification;
            // $result['latest-orders-driver'] =$order_array;
          } else {
            $result['status'] ="false";
	           $result['totalTransporter'] = $totalTransporter;  
            $result['transportCompany_earned_amount'] = 0;
            $result['totalorder'] = 0;
            $result['totalPendingOrder'] = 0;
            $result['totalCompletedOrder'] = 0;
            // $result['driver_total_delivery'] = 0;
            // $result['unread_notification'] = 0;
            // $result['latest-orders-driver'] = [];
          }
          
        return $result;
        });
        throw new CustomeException("Network error. Please try after some time.",500);
    }



    public function getSupplierDashbord(array $request)
    {
     return DB::transaction(function () use ($request) {
            $user_uuid = $request['user_uuid'];
            $salesOrder  = new SalesOrder;
            $basketModel = new Basket;
            $orderstatus_model = new OrderstatusUpdate();
            $walletTransactions = new WalletTransactions;
            $supplierItemInventoryModel = new SupplierItemInventory; 
            
            $user = new User;
            $user_balance = $user->where('uuid',$user_uuid)->first();
            $supplier_earned_amount = $user_balance->wallet_balance;
            // print_r($user_balance->wallet_balance);die();

          $notifyModel = new Notification;

          $unread_notification = $notifyModel->where('user_id',$user_uuid)->where('status','UNREAD')->count();

          // $walletTotal  = WalletTransactions::where('user_id',$user_uuid)->where('remarks','SUPPILER SELL PRODUCT')->approved()->get();
          // $supplier_earned_amount = $walletTotal->sum('credit_amount');
          // print_r(auth()->user()->wallet_balance);die();

          // $creditedAmount = WalletTransactions::leftjoin('users', 'wallet_transactions.user_id','=','users.uuid')->select(DB::raw("SUM(credit_amount) as creditAmount"))->where('wallet_transactions.user_id','=',$user_uuid)->first();

          // $debitedAmount = WalletTransactions::leftjoin('users', 'wallet_transactions.user_id','=','users.uuid')->select(DB::raw("SUM(debit_amount) as debitAmount"))->where('wallet_transactions.user_id','=',$user_uuid)->first();

          // $withdrawalAmount = Withdrawal::leftjoin('users', 'withdrawals.user_id','=','users.uuid')->select(DB::raw("SUM(amount) as amount"))->where('withdrawals.user_id','=',$user_uuid)->where('withdrawals.status','=','PENDING')->first();

          // $supplier_earned_amount = auth()->user()->wallet_balance;

          // $supplier_earned_amount = $creditedAmount->creditAmount - $debitedAmount->debitAmount - $withdrawalAmount->amount;
          // print_r($supplier_earned_amount);die();
          
          $product = new Product;
          $productscount = $product->whereHas('supplierStock', function($q) use ($user_uuid){
              $q->where('user_id','=',$user_uuid);
              $q->where('single', '>', 0);
              $q->where('single_price', '>', 0);
          });
          $productscount = $productscount->where("status", "ACTIVE");
          $productscount = $productscount->count();

          $processedOrders = $salesOrder->where('supplier_id',$user_uuid )->where('order_status', '!=' ,"DELIVERED")->where('order_status', '!=' ,"CANCELLED")->count();

          $completedOrders = $salesOrder->where('supplier_id',$user_uuid )->where('order_status', '=' ,"DELIVERED")->where('payment_status', '=' ,"COMPLETED")->count();

          // $orders = $salesOrder->where('supplier_id',$user_uuid )->where('order_status', '!=' ,"DELIVERED")->where('order_status', '!=' ,"CANCELLED")->orderBy('created_at','desc')->limit(5)->get();

          $orders = $salesOrder->where('supplier_id',$user_uuid )->orderBy('created_at','desc')->limit(5)->get();
          $totalorder = $orders->count();
             

          $order_array =array();
          $order_status  =array();
          $i=0;

          foreach($orders as  $key => $order ) {
            $k = 0;
            $order_status_data = $orderstatus_model->where('sales_id',$order->uuid)->orderBy('created_at', 'asc')->get();

            foreach ($order_status_data as  $value) {
                $order_status[$k]['status'] = $value->order_status;
                $order_status[$k]['date'] = $value->created_at->format('Y-m-d H:i:s');  
                $k++;   
            }

            $basket = $basketModel->where('order_id', $order->uuid)->first();
            // print_r($order->uuid);die();
            $products_data = $basket->products;

            $order_array[$i]['order_uuid'] =  $order->uuid;
            $order_array[$i]['order_number'] =  $order->order_number;
            $order_array[$i]['user_id'] =  $order->supplier_id;
            $order_array[$i]['order_number'] =  $order->order_number;
            $order_array[$i]['user_name'] =  $order->user_name;
            $order_array[$i]['supplier_name'] =  $order->supplier_name;
            $order_array[$i]['cart_amount'] =  $order->cart_amount ? $order->cart_amount : 0;
            $order_array[$i]['shipment_amount'] =  $order->shipment_amount ? $order->shipment_amount : 0;
            $order_array[$i]['discount_amount'] =  $order->discount_amount ? $order->discount_amount : 0;
            $order_array[$i]['tax_amount'] =   $order->tax_amount ? $order->tax_amount : 0;
            $order_array[$i]['total_price'] =  $order->final_total ? $order->final_total : 0;
            $order_array[$i]['total_price'] =  $order->final_total ? $order->final_total : 0;
            $order_array[$i]['order_status'] =  $order->order_status;
            $order_array[$i]['payment_status'] =  $order->payment_status;
            $order_array[$i]['order_date'] =  $order->created_at->format("Y-m-d H:i:s");
            $order_array[$i]['delivery_type']=  $order->delivery_type;
            $order_array[$i]['order_history']=  $order_status;// array_merge($order_history  ,$orderstatus) ;  
            $order_array[$i]['products'] = array();
            $k=0;
            foreach($products_data as $cartProduct){
                $order_array[$i]['products'][$k]['product_uuid'] = $cartProduct->product_id; 
                $order_array[$i]['products'][$k]['product_image'] = url('/') .$cartProduct->product->base_image;
                $order_array[$i]['products'][$k]['name'] = $cartProduct->product->name; 
                $order_array[$i]['products'][$k]['qty']= $cartProduct->single_qty; 
                $supplierLatestRate = $supplierItemInventoryModel->where('product_id', $cartProduct->product_id)->where('user_id', $order->supplier_id)->orderBy('id', 'DESC')->first();
                // $order_array[$i]['products'][$k]['price']  = $supplierLatestRate->single_price;
                // $order_array[$i]['products'][$k]['product_price']  = $supplierLatestRate->single_price * $cartProduct->single_qty; 
                $k++; 
            }
            $i++;
        } 
        $result =array();
         if($totalorder > 0){
            $result['status'] ="true";
            $result['total_product'] = $productscount;
            $result['total_revenue'] = $supplier_earned_amount;
            $result['process_order_total'] =$processedOrders;
            $result['completed_order_total'] =$completedOrders;
            $result['unread_notification'] = $unread_notification;
            $result['latest_orders_supplier'] = $order_array;
         } else {
          $result['status'] ="false";
            $result['total_product'] = 0;
            $result['total_revenue'] = 0;
            $result['process_order_total'] = 0;
            $result['completed_order_total'] = 0;
            $result['unread_notification'] = 0;
            $result['latest_orders_supplier'] = [];
         }
        return $result;
        });
        throw new CustomeException("Network error. Please try after some time.",500);
    }



    //  public function getSupplierDashbord(array $request)

    // {

       

    //     return DB::transaction(function () use ($request) {

    //         $user_uuid = $request['user_uuid'];

    //         $salesOrder  = new SalesOrder;

    //         $basketModel = new Basket;

    //         $notifyModel = new Notification;

    //         $supplierItemInventoryModel = new SupplierItemInventory; 

    //         $orderstatus_model = new OrderstatusUpdate();



    //         $unread_notification = $notifyModel->where('user_id',$user_uuid)->where('status','UNREAD')->count();



    //        $orders = $salesOrder->where('supplier_id',$user_uuid )->where('order_status', '!=' ,"DELIVERED")->where('order_status', '!=' ,"CANCELLED")->orderBy('created_at','desc')->limit(5)->get();



       

    //         $i=0;

    //         foreach($orders as  $key => $order ) {



    //         $k = 0;

    //         $order_status_data = $orderstatus_model->where('sales_id',$order->uuid)->orderBy('created_at', 'asc')->get();

    //            foreach ($order_status_data as  $value) {

                   

    //                 $order_status[$k]['status'] = $value->order_status;

    //                 $order_status[$k]['date'] = $value->created_at->format('Y-m-d H:i:s');  

    //                 $k++;   

    //             }

        

    //         $basket = $basketModel->where('order_id', $order->uuid)->first();

    //         $products_data = $basket->products;

    //         $products_count = $basket->products->count();

    //         $order_array[$i]['order_uuid'] =  $order->uuid;

    //         $order_array[$i]['order_number'] =  $order->order_number;

    //         $order_array[$i]['user_id'] =  $order->supplier_id;

    //         $order_array[$i]['order_number'] =  $order->order_number;

    //         $order_array[$i]['user_name'] =  $order->user_name;

    //         $order_array[$i]['supplier_name'] =  $order->supplier_name;

    //         $order_array[$i]['cart_amount'] =  $order->cart_amount ? $order->cart_amount : 0;

    //         $order_array[$i]['shipment_amount'] =  $order->shipment_amount ? $order->shipment_amount : 0;

    //         $order_array[$i]['discount_amount'] =  $order->discount_amount ? $order->discount_amount : 0;

    //         $order_array[$i]['tax_amount'] =   $order->tax_amount ? $order->tax_amount : 0;

    //         $order_array[$i]['total_price'] =  $order->final_total ? $order->final_total : 0;

    //         $order_array[$i]['total_price'] =  $order->final_total ? $order->final_total : 0;

    //         $order_array[$i]['order_status'] =  $order->order_status;

    //         $order_array[$i]['payment_status'] =  $order->payment_status;

    //         $order_array[$i]['order_date'] =  $order->created_at->format("Y-m-d H:i:s");

    //         $order_array[$i]['delivery_type']=  $order->delivery_type;

    //         $order_array[$i]['order_history']=  $order_status;// array_merge($order_history  ,$orderstatus) ;  

    //         $order_array[$i]['products'] = array();
    //         // print_r($order_array);die();
    //         $k=0;

    //         if($products_count > 0){

    //           foreach($products_data as $cartProduct){

                      

    //               $order_array[$i]['products'][$k]['product_uuid'] = $cartProduct->product_id; 

    //               $order_array[$i]['products'][$k]['product_image'] =url('/') .$cartProduct->product->base_image;

    //               $order_array[$i]['products'][$k]['name'] = $cartProduct->product->name; 

    //               $order_array[$i]['products'][$k]['qty']= $cartProduct->single_qty; 

                

    //              $supplierLatestRate = $supplierItemInventoryModel->where('product_id', $cartProduct->product_id)->where('user_id', $order->supplier_id)->orderBy('id', 'DESC')->first();

    //               $order_array[$i]['products'][$k]['price']  = $supplierLatestRate->single_price;

    //               $order_array[$i]['products'][$k]['product_price']  = $supplierLatestRate->single_price * $cartProduct->single_qty; 

    //                 $k++; 

    //           }

    //        }

    //         $i++;

    //     }

    //         $totalorder = $orders->count();  



          

    //       $orders1 = $salesOrder->where('supplier_id',$user_uuid )->where('order_status',"DELIVERED")->get();

    //       $totalorder1 = $orders1->count();  





    //       $orders2 = $salesOrder->where('supplier_id',$user_uuid )->where('order_status', '!=' ,"DELIVERED")->where('order_status', '!=' ,"CANCELLED")->get();

    //       $totalorder2 = $orders2->count();    





    //       $walletTransactions = new WalletTransactions;

    //          $walletTotal  = WalletTransactions::where('user_id',$user_uuid)->where('remarks','SELL PRODUCT')->approved()->get();

    //       $creditedAmount = $walletTotal->sum('credit_amount');



    //       $product = new Product;

    //        $productscount = $product->whereHas('supplierStock', function($q) use ($user_uuid){

    //           $q->where('user_id','=',$user_uuid);

    //           $q->where('single', '>', 0);

    //           $q->where('single_price', '>', 0);

    //       });

    //        $productscount = $productscount->where("status", "ACTIVE");

    //       $productscount = $productscount->count();





    //     $result =array();

    //      if($totalorder > 0){

    //         $result['status'] ="true";

    //         $result['total_product'] = $productscount;

    //         $result['total_revenue'] = $creditedAmount;

    //         $result['process_order_total'] =$totalorder2;

    //         $result['completed_order_total'] =$totalorder1;

    //         $result['unread_notification'] = $unread_notification;

    //         $result['latest_orders_supplier'] = $order_array;

    //      } else {

    //        $result['status'] ="false";

    //        $result['total_product'] = 0;

    //         $result['total_revenue'] = 0;

    //         $result['process_order_total'] =0;

    //         $result['completed_order_total'] = 0;

    //         $result['unread_notification'] = 0;

    //         $result['latest_orders_supplier'] = [];

    //      }

          

    //     return $result;

    //     });

          

    //       throw new CustomeException("Network error. Please try after some time.",500);

    // }



     public function getDriverOrder(array $request)

    {



        return DB::transaction(function () use ($request) {

            $user_uuid = $request['user_uuid'];

            $user_type = $request['user_type'];

            $salesOrder  = new SalesOrder;

            $user  = new User;

            $orderstatus_model = new OrderstatusUpdate();

            $basketModel = new Basket;

             $supplierItemInventoryModel = new SupplierItemInventory; 



             if($user_type == 'COMPANY')

             {



             $driverIds = $user->where('logistic_company_id',$user_uuid)->pluck('uuid')->toArray(); 

             $orders = $salesOrder->whereIn('logistic_id',$driverIds)->orderBy('created_at','desc')->get();



            }

           else

           {

             

             $orders = $salesOrder->where('logistic_id',$user_uuid )->orderBy('created_at','desc')->get();

           }

            $order_array =array();

            $order_status  =array();

             $i=0;

            foreach($orders as  $key => $order ) {



            $k = 0;

            $order_status  =array();

            $order_status_data = $orderstatus_model->where('sales_id',$order->uuid)->orderBy('created_at', 'asc')->get();

               foreach ($order_status_data as  $value) {

                   

                    $order_status[$k]['status'] = $value->order_status;

                    $order_status[$k]['date'] = $value->created_at->format('Y-m-d H:i:s');  

                    $k++;   

                }

        

            $basket = $basketModel->where('order_id', $order->uuid)->first();

            $products_data = $basket->products;

            $order_array[$i]['order_uuid'] =  $order->uuid;

            $order_array[$i]['order_number'] =  $order->order_number;

            $order_array[$i]['user_id'] =  $order->supplier_id;

            $order_array[$i]['order_number'] =  $order->order_number;

            $order_array[$i]['user_name'] =  $order->user_name;

            $order_array[$i]['supplier_name'] =  $order->supplier_name;

            $order_array[$i]['cart_amount'] =  $order->cart_amount ? $order->cart_amount : 0;

            $order_array[$i]['shipment_amount'] =  $order->shipment_amount ? $order->shipment_amount : 0;

            $order_array[$i]['discount_amount'] =  $order->discount_amount ? $order->discount_amount : 0;

            $order_array[$i]['tax_amount'] =   $order->tax_amount ? $order->tax_amount : 0;

            $order_array[$i]['total_price'] =  $order->final_total ? $order->final_total : 0;

            $order_array[$i]['total_price'] =  $order->final_total ? $order->final_total : 0;

            $order_array[$i]['order_status'] =  $order->order_status;

            $order_array[$i]['payment_status'] =  $order->payment_status;

            $order_array[$i]['order_date'] =  $order->created_at->format("Y-m-d H:i:s");

            $order_array[$i]['delivery_type']=  $order->delivery_type;

            $order_array[$i]['order_history']=  $order_status;// array_merge($order_history  ,$orderstatus) ;  

            $order_array[$i]['products'] = array();

            $k=0;

            foreach($products_data as $cartProduct){

                    

                $order_array[$i]['products'][$k]['product_uuid'] = $cartProduct->product_id; 

                $order_array[$i]['products'][$k]['product_image'] =url('/').$cartProduct->product->base_image;

                $order_array[$i]['products'][$k]['name'] = $cartProduct->product->name; 

                $order_array[$i]['products'][$k]['qty']= $cartProduct->single_qty; 

              

               $supplierLatestRate = $supplierItemInventoryModel->where('product_id', $cartProduct->product_id)->where('user_id', $order->supplier_id)->orderBy('id', 'DESC')->first();

                $order_array[$i]['products'][$k]['price']  = $supplierLatestRate->single_price;

                $order_array[$i]['products'][$k]['product_price']  = $supplierLatestRate->single_price * $cartProduct->single_qty; 

                  $k++; 

            }

            $i++;

        } 



          $totalorder = $orders->count();  

          

        $result =array();

         if($totalorder > 0){

            $result['status'] ="true";

            $result['data'] = $order_array;

         } else {

           $result['status'] ="false";

           $result['data'] = [];

         }

          

        return $result;

        });

          

          throw new CustomeException("Network error. Please try after some time.",500);

    }



}