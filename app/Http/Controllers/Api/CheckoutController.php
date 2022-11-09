<?php



namespace App\Http\Controllers\Api;



use App\Classes\CheckoutCls;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;



class CheckoutController extends Controller

{



    private $productObj;



    public function __construct(CheckoutCls $checkoutObj)

    {

        //$this->middleware('auth:api');

        $this->checkoutObj = $checkoutObj;

    }



   public function addtocart(Request $request){

      

        $postData = $request->all();

        $response  = $this->checkoutObj->addtocart($postData);

        return response($response);

    }



    public function cart(Request $request){

      

        $postData = $request->all();

        $response = $this->checkoutObj->cart($postData);



          if(!empty($response))

        {

            $arr_obj['status'] = "true";

            $arr_obj['data'] = $response;

                

        }

        else{



            $arr_obj['status'] = "false";

            $arr_obj['data'] = [];

            

        }



        //$response = $token['response'];

         return response($arr_obj);

    }





    public function removetocart(Request $request){

      

        $postData = $request->all();

        $response = $this->checkoutObj->removetocart($postData);

        

        return response(json_encode($response));



    }



    public function selectSupplier(Request $request){

      
      

        $postData = $request->all();

        $response = $this->checkoutObj->selectSupplier($postData);

        

        return response(json_encode($response));



    }

    

    public function applyPromocode(Request $request){

      

        $postData = $request->all();

        $response = $this->checkoutObj->applyPromocode($postData);

        

        return response(json_encode($response));



    }

    

    public function getWallet(Request $request){
        $postData = $request->all();
        $response = $this->checkoutObj->getWallet($postData);    
        return response(json_encode($response));
    }

    public function getWithdrawal(Request $request){
        $postData = $request->all();
        $response = $this->checkoutObj->getWithdrawal($postData);
        return response(json_encode($response));
    }

    public function addWallet(Request $request){
        $postData = $request->all();
        $response = $this->checkoutObj->addWallet($postData);
        return response($response);
    } 

    public function addWithdrawalRequest(Request $request){
        $postData = $request->all();
        $response = $this->checkoutObj->addWithdrawalRequest($postData);
        return response($response);
    }  

 

   

    public function getTransactionType(Request $request){

      

        $postData = $request->all();

        $response = $this->checkoutObj->getTransactionType();



        return response($response);

    } 





     public function orderDetails(Request $request){

      

        $postData = $request->all();

        $response = $this->checkoutObj->orderDetails($postData);

        

        

        return response($response);



    } 





     public function orderPlaced(Request $request){

      

        $postData = $request->all();

        $response = $this->checkoutObj->orderPlaced($postData);

        

        return response($response);



    }



    public function orderlist(Request $request){

      

        $postData = $request->all();

        $response = $this->checkoutObj->orderlist($postData);

        

        return response($response);



    } 

    

     public function paymentSummary(Request $request){

      

        $postData = $request->all();

        $response = $this->checkoutObj->paymentSummary($postData);

        

        return response(json_encode($response));



    } 



    public function accountsList(Request $request){

      

        $postData = $request->all();

        

        $response = $this->checkoutObj->accountsList($postData);

        

        return response($response);



    }

    

     public function orderlistTrack(Request $request){

      

        $postData = $request->all();

        

        $response = $this->checkoutObj->orderlistTrack($postData);

        

        return response($response);



    }



    //Logitute latitue update for map

    public function updateLocation(Request $request){

   

        $postData = $request->all();

        $response = $this->checkoutObj->updateLocation($postData);



        return response($response);



    }  



     //Logitute latitue update for map Driver

    public function driverUpdateLocation(Request $request){

   

        $postData = $request->all();

        $response = $this->checkoutObj->driverUpdateLocation($postData);



        return response($response);



    }  

    
    public function repeatOrder(Request $request){
        $postData = $request->all();
        $response = $this->checkoutObj->repeatOrder($postData);

        if(!empty($response))
        {
            $arr_obj['status'] = "true";
            $arr_obj[] = $response;
        }
        else{
            $arr_obj['status'] = "false";
            $arr_obj[] = '';
        }
        // //$response = $token['response'];
         return response($arr_obj);
    }


}

