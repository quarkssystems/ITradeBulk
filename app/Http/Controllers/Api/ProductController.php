<?php



namespace App\Http\Controllers\Api;



use App\Classes\ProductCls;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;



class ProductController extends Controller

{



    private $productObj;



    public function __construct(ProductCls $productObj)

    {

        $this->productObj = $productObj;

    }





   public function searchProduct(Request $request){

      

        $postData = $request->all();

        $response = $this->productObj->searchProduct($postData);

       

        //$response = $token['response'];

         return response($response);

    }



    public function getUnreadNotification(Request $request){

      

        $postData = $request->all();

        $response = $this->productObj->getUnreadNotification($postData);

       

        //$response = $token['response'];

         return response($response);

    }





    public function arrivalsProduct(Request $request){

      

        $postData = $request->all();

        $response = $this->productObj->arrivalsProduct($postData);

        return response($response);

    }





    public function bestSalesProduct(Request $request){

      

        $postData = $request->all();

        $response = $this->productObj->bestSalesProduct($postData);

        return response($response);

    }



    public function dealsOfDayProduct(Request $request){

      

        $postData = $request->all();

        $response = $this->productObj->dealsOfDayProduct($postData);

        return response($response);

    }

    

    public function bestOfWeekProduct(Request $request){

      

        $postData = $request->all();

        $response = $this->productObj->bestOfWeekProduct($postData);

       

        return response($response);

    }



    public function getCategories(Request $request){



        $postData = $request->all();

        $response = $this->productObj->getCategories($postData);

        return response($response);

    }

    



    public function getManufacture(Request $request){



        $postData = $request->all();

        $response = $this->productObj->getManufacture($postData);

       return response($response);



        

    }





    public function getSupplierOffers(Request $request){



        $postData = $request->all();

        $arr_obj = array();     

        $response = $this->productObj->getSupplierOffers($postData);



        return response($response);



    }



        

    public function getProductDetails(Request $request){



        $postData = $request->all();

        //echo '<pre>'; print_r($postData);die;

        $response = $this->productObj->getProductDetails($postData);

       

        //$response = $token['response'];

         return response(json_encode($response));

    }





    public function getProductByCat(Request $request){



        $postData = $request->all();

        $response = $this->productObj->getProductByCat($postData);

        

         return response(json_encode($response));

    }



    //get product by brand

    

    public function getProductByMan(Request $request){



        $postData = $request->all();

        $response = $this->productObj->getProductByMan($postData);

        

         return response(json_encode($response));

    }



    public function givingrating(Request $request){



        $postData = $request->all();

        $response = $this->productObj->givingrating($postData);

        

         return response($response);

    }



    public function getRatingReview(Request $request){



        $postData = $request->all();

        $response = $this->productObj->getRatingReview($postData);

        

         return response(json_encode($response));

    }    

    

}

