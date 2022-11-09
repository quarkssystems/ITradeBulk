<?php

namespace App\Http\Controllers\Api;
use App\Classes\UserCls;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;


class OrderController extends Controller
{


    private $userObj;

    public function __construct(UserCls $userObj)
    {
        $this->userObj = $userObj;
    }


     public function getOrderForSupplier(Request $request){
      
        $postData = $request->all();
        $token = $this->userObj->fetchOrders($postData);
        $response = $token['response'];

         return response(json_encode($response));
    }

    public function getOrderForVendor(Request $request){
      
        $postData = $request->all();
        $token = $this->userObj->fetchOrdersForVender($postData);
        $response = $token['response'];
       
        return response(json_encode($response));
    }

    public function getOrderDetail(Request $request){
      
        $postData = $request->all();
        $token = $this->userObj->fetchOrderDetail($postData);
        $response = $token['response'];
        
        return response($response);
    }

    public function updateOrderstatus(Request $request){
        
          $postData = $request->all();
        $token = $this->userObj->updateOrderStatus($postData);
        //$response = $token['response'];
        
          
       return response($token);
       
    }


    public function packedOrder(Request $request){
      

        $postData = $request->all();
        $token = $this->userObj->packedOrder($postData);
        //$response = $token['response'];
        //$code = $token['code'];
          
       /* if($code == 200)
          {
            $response['status'] = true; 
          }
          else
          {
            $response['status'] = false;
          }*/
          
       return response($token);
       
    }

    public function deliveryOrderPacked(Request $request){
      

        $postData = $request->all();
        $token = $this->userObj->deliveryOrderPacked($postData);
        $response = $token['response'];
        $code = $token['code'];
          
        if($code == 200)
          {
            $response['status'] = true; 
          }
          else
          {
            $response['status'] = false;
          }
          
       return response($response);
       
    }

    


    public function verifyOTP(Request $request){
      
        $postData = $request->all();
        $token = $this->userObj->verifyOTP($postData);
       // $response = $token['response'];
        //$code = $token['code'];
        
        return response($token);
    }

     public function acceptOrderDriver(Request $request){
      
        $postData = $request->all();
        $response = $this->userObj->acceptOrderDriver($postData);
         
        return response($response);
    }
   
     public function rejectOrderDriver(Request $request){
      
        $postData = $request->all();
        $response = $this->userObj->rejectOrderDriver($postData);
         
        return response($response);
    }

     

    public function getSupplierDashbord(Request $request){
      
        $postData = $request->all();
        $response = $this->userObj->getSupplierDashbord($postData);
         
        return response($response);
    }

    public function getDriverDashbord(Request $request){
      
        $postData = $request->all();
        $response = $this->userObj->getDriverDashbord($postData);
         
        return response($response);
    }

    public function getCompanyDriverDashbord(Request $request){
      
        $postData = $request->all();
        $response = $this->userObj->getCompanyDriverDashbord($postData);
         
        return response($response);
    }

    public function getDriverOrder(Request $request){
      
      
        $postData = $request->all();
        $response = $this->userObj->getDriverOrder($postData);
         
        return response($response);
    }

    public function getRecentOrders(Request $request){

      $postData = $request->all();
      $token = $this->userObj->getRecentOrders($postData);
      $response = $token['response'];
       
      return response(json_encode($response));
    }



}
